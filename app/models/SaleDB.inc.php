<?php

include_once 'ProductDB.inc.php';

class SaleDB {

    static function getSales ($connection, $page, $limit) {

        $offset = $page*$limit;
        $limit = $limit;

        if (isset($connection)) {
            try {
                //$sql = 'SELECT s.reference, s.date_day, s.hour, s.position, r.subtotal, r.total, r.consumed FROM sale_information s LEFT JOIN (SELECT reference_sal, SUM(quantity) AS consumed, SUM(subtotal) AS subtotal, SUM(total) AS total FROM sale_details) r ON s.reference = r.reference_sal ORDER BY S.date_day DESC, s.hour DESC LIMIT :p_page, :p_limit';
                $sql = 'SELECT s.reference, s.date_day, s.hour, s.position, r.subtotal, r.total, r.consumed FROM sale_information s INNER JOIN (SELECT reference_sal, SUM(quantity) AS consumed, SUM(subtotal) AS subtotal, SUM(total) AS total FROM sale_details GROUP BY (reference_sal)) r ON s.reference=r.reference_sal ORDER BY s.date_day DESC, s.hour DESC LIMIT :p_page, :p_limit';
                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_page', $offset , PDO::PARAM_INT);
                $sentencia->bindParam(':p_limit', $limit, PDO::PARAM_INT);
                
                $sentencia->execute();
                $data = $sentencia->fetchAll();

                $response = array('error' => false, 'message' => 'Se ha extraído de forma correcta.','data' => $data);
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }
        
        return json_encode($response);
    }
    
    static function getSaleDetails ($connection, $reference) {

        if (isset($connection)) {
            try {
                $sql = 'SELECT s.quantity, s.subtotal, s.total, p.alias FROM sale_details s INNER JOIN product p ON p.reference=s.reference_pro WHERE reference_sal = :p_reference';
                
                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_reference', $reference , PDO::PARAM_STR);
                $sentencia->execute();
                $data = $sentencia->fetchAll();

                $response = array('error' => false, 'message' => 'Se ha extraído de forma correcta.','data' => $data);
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }
        
        return json_encode($response);
    }

    static function addTemporalSale ($connection, $reference, $subtotal, $total, $quantity, $required) {
        $position = ($quantity - $required > 0) ? ProductDB::$available : ProductDB::$outStock;
        if (isset($connection)) {
            try {
                $connection->beginTransaction();

                $sql1 = 'INSERT INTO `current_sale`(`reference_pro`, `quantity`, `subtotal`, `total`, `date_add`) VALUES (:p_reference, :p_quantity, :p_subtotal, :p_total, now())';
                $sql2 = 'UPDATE product SET quantity = quantity - :p_dismit, position = :p_position WHERE reference = :p_reference';

                $sentencia1 = $connection->prepare($sql1);
                $sentencia1->bindParam(':p_reference', $reference , PDO::PARAM_STR);
                $sentencia1->bindParam(':p_quantity', $required , PDO::PARAM_INT);
                $sentencia1->bindParam(':p_subtotal', $subtotal, PDO::PARAM_STR);
                $sentencia1->bindParam(':p_total', $total, PDO::PARAM_STR);
                $sentencia1->execute();

                $sentencia2 = $connection->prepare($sql2);
                $sentencia2->bindParam(':p_dismit', $required, PDO::PARAM_INT);
                $sentencia2->bindParam(':p_position', $position, PDO::PARAM_INT);
                $sentencia2->bindParam(':p_reference', $reference, PDO::PARAM_STR);
                
                $sentencia2->execute();

                $connection->commit();

                $response = array('error' => false, 'message' => 'Se ha insertado de forma correcta.','data' => null);
            } catch (PDOException $e) {
                $connection->rollBack();
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }
        
        return json_encode($response);
    }

    static function getTemporalSales ($connection) {

        if (isset($connection)) {
            try {
                $sql = 'SELECT p.reference, p.alias, p.quantity, c.quantity AS required_q, c.subtotal, c.total FROM current_sale c INNER JOIN product p ON c.reference_pro=p.reference ORDER BY c.date_add DESC';
                
                $sentencia = $connection->prepare($sql);
                
                $sentencia->execute();
                $data = $sentencia->fetchAll();

                $response = array('error' => false, 'message' => 'Se ha extraído de forma correcta.','data' => $data);
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }
        
        return json_encode($response);
    }

    static function removeFromTemporalSale ($connection, $reference) {

        if (isset($connection)) {
            try {
                $connection->beginTransaction();

                $sql1 = 'SELECT quantity FROM current_sale WHERE reference_pro = :p_reference';
                $sql2 = 'UPDATE product SET quantity = quantity + :p_new_q, position = 1 WHERE reference = :p_reference';
                $sql3 = 'DELETE FROM current_sale WHERE reference_pro = :p_reference';
                
                $sentencia1 = $connection->prepare($sql1);
                $sentencia1->bindParam(':p_reference', $reference, PDO::PARAM_STR);
                $sentencia1->execute();
                $cantidad = $sentencia1->fetch();

                $sentencia2 = $connection->prepare($sql2);
                $sentencia2->bindParam(':p_new_q', $cantidad['quantity'], PDO::PARAM_INT);
                $sentencia2->bindParam(':p_reference', $reference, PDO::PARAM_STR);
                $sentencia2->execute();

                $sentencia3 = $connection->prepare($sql3);
                $sentencia3->bindParam(':p_reference', $reference, PDO::PARAM_STR);
                $sentencia3->execute();

                $connection->commit();

                $response = array('error' => false, 'message' => 'Se ha eliminado de forma correcta.','data' => null);
            } catch (PDOException $e) {
                $connection->rollBack();
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }
        
        return json_encode($response);
    }

    static function getQuantityProduct ($connection, $reference) {
        if (isset($connection)) {
            try {
                $sql = 'SELECT quantity FROM current_sale WHERE reference_pro = :p_reference';
                
                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_reference', $reference, PDO::PARAM_STR);
                $sentencia->execute();
                $data = $sentencia->fetch();

                $response = array('error' => false, 'message' => 'Se ha extraído de forma correcta.','data' => $data['quantity']);
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }
        
        return json_encode($response);
    }

    static function updateTemporalSale ($connection, $reference, $subtotal, $total, $quantity, $required) {
        $position = ($quantity - $required > 0) ? ProductDB::$available : ProductDB::$outStock;
        $final_q = $quantity - $required;
        if (isset($connection)) {
            try {
                $connection->beginTransaction();
                
                $sql1 = 'UPDATE current_sale SET quantity = :p_quantity, subtotal = :p_subtotal, total = :p_total, date_add = now() WHERE reference_pro = :p_reference';
                $sql2 = 'UPDATE product SET quantity = :p_dismit, position = :p_position WHERE reference = :p_reference';

                $sentencia1 = $connection->prepare($sql1);
                $sentencia1->bindParam(':p_quantity', $required , PDO::PARAM_INT);
                $sentencia1->bindParam(':p_subtotal', $subtotal, PDO::PARAM_STR);
                $sentencia1->bindParam(':p_total', $total, PDO::PARAM_STR);
                $sentencia1->bindParam(':p_reference', $reference , PDO::PARAM_STR);
                $sentencia1->execute();

                $sentencia2 = $connection->prepare($sql2);
                $sentencia2->bindParam(':p_dismit', $final_q, PDO::PARAM_INT);
                $sentencia2->bindParam(':p_position', $position, PDO::PARAM_INT);
                $sentencia2->bindParam(':p_reference', $reference, PDO::PARAM_STR);
                $sentencia2->execute();

                $connection->commit();

                $response = array('error' => false, 'message' => 'Se ha actualizado de forma correcta.','data' => null);
            } catch (PDOException $e) {
                $connection->rollBack();
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }
        
        return json_encode($response);
    }

    function getTotalTemporalSale ($connection) {

        if (isset($connection)) {
            try {

                $sql = "SELECT SUM(total) as total from current_sale";
                $sentencia=$connection->prepare($sql);
                $sentencia->execute();

                $data = $sentencia->fetch();

                $response = array('error' => false, 'message' => 'Se ha contabilizado de forma correcta.','data' => $data['total']);

            } catch (PDOException $e) {
                $connection->rollBack();
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }

        return json_encode($response);
    }

    function closeTemporalSale ($connection) {

        if (isset($connection)) {
            try {

                $saleKey = "sale_".time()."_ref";

                $connection->beginTransaction();

                $sql1 = 'SELECT * FROM current_sale';
                $sql2 = 'INSERT INTO sale_information (reference, date_day, hour, position) VALUES(:p_reference, now(), now(), 1)';
                $sql3 = 'INSERT INTO sale_details (reference_sal, reference_pro, quantity, subtotal, total) 
                VALUES (:p_reference_s, :p_reference_p, :p_quantity, :p_subtotal, :p_total)';
                $sql4 = 'DELETE FROM current_sale';

                $sentencia1 = $connection->prepare($sql1);
                $sentencia1->execute();
                $products = $sentencia1->fetchAll();

                $sentencia2 = $connection->prepare($sql2);
                $sentencia2->bindParam(':p_reference', $saleKey, PDO::PARAM_STR);
                $sentencia2->execute();

                foreach ($products as $prod) {
                    $sentencia3 = $connection->prepare($sql3);
                    $sentencia3->bindParam(':p_reference_s', $saleKey, PDO::PARAM_STR);
                    $sentencia3->bindParam(':p_reference_p', $prod['reference_pro'], PDO::PARAM_STR);
                    $sentencia3->bindParam(':p_quantity', $prod['quantity'], PDO::PARAM_INT);
                    $sentencia3->bindParam(':p_subtotal', $prod['subtotal'], PDO::PARAM_STR);
                    $sentencia3->bindParam(':p_total', $prod['total'], PDO::PARAM_STR);
                    $sentencia3->execute();
                }

                $sentencia4 = $connection->prepare($sql4);
                $sentencia4->execute();

                $connection->commit();

                $response = array('error' => false, 'message' => 'Se ha gurdado de forma correcta.','data' => null);

            } catch (PDOException $e) {
                $connection->rollBack();
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }

        return json_encode($response);
    }

    static function cancelAllSale ($connection, $reference) {
        if (isset($connection)) {
            try {
                $connection->beginTransaction();

                $sql = 'SELECT quantity, reference_pro FROM sale_details WHERE reference_sal = :p_reference';
                $sql0 = 'UPDATE product SET quantity = quantity + :p_quantity, position = 1 WHERE reference = :p_reference';
                $sql1 = 'DELETE FROM sale_details WHERE reference_sal = :p_reference';
                $sql2 = 'DELETE FROM sale_information WHERE reference = :p_reference';

                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_reference', $reference, PDO::PARAM_STR);
                $sentencia->execute();
                $products = $sentencia->fetchAll();

                foreach ($products as $prod) {
                    $sentencia0 = $connection->prepare($sql0);
                    $sentencia0->bindParam(':p_quantity', $prod['quantity'], PDO::PARAM_INT);
                    $sentencia0->bindParam(':p_reference', $prod['reference_pro'], PDO::PARAM_STR);
                    $sentencia0->execute();
                }

                $sentencia1 = $connection->prepare($sql1);
                $sentencia1->bindParam(':p_reference', $reference, PDO::PARAM_STR);
                $sentencia1->execute();

                $sentencia2 = $connection->prepare($sql2);
                $sentencia2->bindParam(':p_reference', $reference, PDO::PARAM_STR);
                $sentencia2->execute();

                $connection->commit();

                $response = array('error' => false, 'message' => 'Se ha eliminado de forma exitosa','data' => null);
            } catch (PDOException $e) {
                $connection->rollBack();
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }

        return json_encode($response);
    }

}

?>