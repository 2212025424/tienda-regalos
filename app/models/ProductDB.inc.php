<?php

class ProductDB {

    static $outStock = 0;
    static $available = 1;
    static $deleted = 2;

    private static $limitFeaturedProducts = 8;

    static function getTotalProduts ($connection) {

        if (isset($connection)) {
            try {
                $sql = 'SELECT COUNT(*) as total_products FROM product WHERE position!=:p_deleted';
                
                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_deleted', self::$deleted, PDO::PARAM_INT);

                $sentencia->execute();
                $data = $sentencia->fetch();

                $response = array('error' => false, 'message' => 'Se ha extraído de forma exitosa.', 'data' => $data['total_products']);
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => false, 'message' => 'No hay conexión a la base de datos','data' => null);
        }
        
        return json_encode($response);
    }

    static function getFeaturedProducts ($connection, $limit) {

        if (isset($connection)) {
            try {
                $sql = 'SELECT alias, img_url FROM product p INNER JOIN featured f ON f.product_ref = p.reference WHERE p.position!=:p_deleted ORDER BY f.date_add ASC LIMIT :p_limit';
                
                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_deleted', self::$deleted, PDO::PARAM_INT);
                $sentencia->bindParam(':p_limit', $limit, PDO::PARAM_INT);
                
                $sentencia->execute();
                $data = $sentencia->fetchAll();

                $response = array('error' => false, 'message' => 'Se ha extrído de forma correcta.','data' => $data);
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }
        
        return json_encode($response);
    }

    static function getPromotedProducts ($connection) {

        if (isset($connection)) {
            try {
                $sql = 'SELECT * FROM product WHERE offer=1 AND position!=:p_deleted ORDER BY date_add DESC';
                
                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_deleted', self::$deleted, PDO::PARAM_INT);
                
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

    static function getProductsByCategory ($connection, $category, $limit) {

        if (isset($connection)) {
            try {
                $sql = 'SELECT * FROM product WHERE category_ref = :p_category AND position != :p_deleted ORDER BY date_add DESC LIMIT :p_limit';
                
                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_category', $category, PDO::PARAM_STR);
                $sentencia->bindParam(':p_deleted', self::$deleted, PDO::PARAM_INT);
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

    static function getProducts ($connection, $page, $limit) {

        $offset = $page*$limit;
        $limit = $limit;

        if (isset($connection)) {
            try {

                $sql = 'SELECT r.category_ref, r.reference, r.img_url, r.alias, r.brand, r.information, r.cost, r.price, r.offer, r.discount_percentage, r.quantity, r.min_stock, r.date_add, r.category_url, r.category_name, f.product_ref AS featured FROM (SELECT p.category_ref, p.reference, p.img_url, p.alias AS alias, p.brand, p.information, p.cost, p.price, p.offer, p.discount_percentage, p.quantity, p.min_stock, p.date_add, c.category_url, c.alias AS category_name FROM (SELECT * FROM product WHERE position != :p_deleted) p INNER JOIN category c ON p.category_ref=c.reference ORDER BY p.date_add DESC LIMIT :p_page, :p_limit) r LEFT JOIN featured f ON r.reference = f.product_ref ORDER BY r.date_add DESC';

                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_deleted', self::$deleted, PDO::PARAM_INT);
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

    static function searchProductBy ($connection, $query, $page, $limit) {

        $offset = $page*$limit;
        $query = '%'.$query.'%';

        if (isset($connection)) {
            try {
                //$sql = 'SELECT * FROM (SELECT * FROM product WHERE position != :p_deleted) AS t WHERE t.alias LIKE :p_query OR t.brand LIKE :p_query OR t.information LIKE :p_query OR t.price LIKE :p_query ORDER BY t.date_add DESC LIMIT :p_page, :p_limit';
                $sql = 'SELECT r.category_ref, r.reference, r.img_url, r.alias, r.brand, r.information, r.cost, r.price, r.offer, r.discount_percentage, r.date_add, r.quantity, r.min_stock, r.category_url, r.category_name, f.product_ref AS featured FROM (SELECT p.category_ref, p.reference, p.img_url, p.alias AS alias, p.brand, p.information, p.cost, p.price, p.offer, p.discount_percentage, p.quantity, p.min_stock, p.date_add, c.category_url, c.alias AS category_name FROM (SELECT * FROM product WHERE position != :p_deleted) p INNER JOIN category c ON p.category_ref=c.reference ORDER BY p.date_add DESC) r LEFT JOIN featured f ON r.reference = f.product_ref WHERE r.alias LIKE :p_query OR WHERE r.brand LIKE :p_query OR WHERE r.information LIKE :p_query OR WHERE r.price LIKE :p_query ORDER BY r.date_add DESC LIMIT :p_page, :p_limit';
                
                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_deleted', self::$deleted, PDO::PARAM_INT);
                $sentencia->bindParam(':p_query', $query, PDO::PARAM_STR);
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

    static function deleteFeaturedProduct ($connection, $reference) {
        
        if (isset($connection)) {
            try {

                $sql = 'DELETE FROM featured WHERE product_ref = :p_reference';
                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_reference', $reference, PDO::PARAM_STR);
                $sentencia->execute();

                $response = array('error' => false, 'message' => 'Se ha eliminado de forma exitosa.', 'data' => null);
                
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }

        return json_encode($response);
    }

    static function deleteProduct ($connection, $reference) {

        if (isset($connection)) {
            try {
                
                $sql = 'UPDATE product SET position = :p_deleted WHERE reference = :p_reference';
                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_deleted', self::$deleted, PDO::PARAM_INT);
                $sentencia->bindParam(':p_reference', $reference, PDO::PARAM_STR);
                $sentencia->execute();

                $response = array('error' => false, 'message' => 'Se ha eliminado de forma exitosa.', 'data' => null);

            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }
        
        return json_encode($response);
    }

    static function removeProduct ($connection, $reference) {

        if (isset($connection)) {
            try {
                
                $connection->beginTransaction();
                $res_tem1 = json_decode(self::deleteFeaturedProduct($connection, $reference));
                $res_tem2 = json_decode(self::deleteProduct($connection, $reference));

                $response = ($res_tem1->error) ? $res_tem1 : $res_tem2;
                ($res_tem1->error or $res_tem2->error) ? $connection->rollBack() : $connection->commit();

            } catch (PDOException $e) {
                $connection->rollBack();
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }
        
        return json_encode($response);
    }

    static function getTotalFeaturedProducts ($connection) {

        if (isset($connection)) {
            try {
                $sql = 'SELECT COUNT(*) as total_products FROM featured';
                
                $sentencia = $connection->prepare($sql);

                $sentencia->execute();
                $data = $sentencia->fetch();

                $response = array('error' => false, 'message' => 'Se ha contado extraído de forma exitosa.', 'data' => $data['total_products']);
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos','data' => null);
        }
        
        return json_encode($response);
    }

    static function getReferenceFirstFeaturedProduct ($connection) {

        if (isset($connection)) {
            try {
                $sql = 'SELECT product_ref FROM featured ORDER BY date_add ASC LIMIT 1';
                
                $sentencia = $connection->prepare($sql);
                $sentencia->execute();
                $data = $sentencia->fetch();

                $response = array('error' => false, 'message' => 'Se ha extrído de forma correcta.', 'data' => $data['product_ref']);
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos','data' => null);
        }
        
        return json_encode($response);
    }

    static function addFeaturedProduct ($connection, $reference) {

        if (isset($connection)) {
            try {
                $sql = 'INSERT INTO featured (product_ref, date_add) VALUES (:p_reference, NOW());';
                
                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_reference', $reference, PDO::PARAM_STR);
                $sentencia->execute();
                
                if ($sentencia->rowCount() > 0) {
                    $response = array('error' => false, 'message' => 'Se ha destacado de forma exitosa.', 'data' => null);
                }else {
                    $response = array('error' => true, 'message' => 'No se ha podido destacar.', 'data' => null);
                }
                
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos','data' => null);
        }
        
        return json_encode($response);
    }

    static function highlightProduct ($connection, $reference) {

        if (isset($connection)) {
            try {

                $connection->beginTransaction();

                $totalFeatured = json_decode(self::getTotalFeaturedProducts($connection));
                $firstFeatured = json_decode(self::getReferenceFirstFeaturedProduct($connection));

                $response = ($totalFeatured->error) ? $totalFeatured : $firstFeatured;

                if (!$response->error && $totalFeatured->data > self::$limitFeaturedProducts) {
                   $response = json_decode(self::deleteFeaturedProduct($connection, $firstFeatured->data));
                } 
                if (!$response->error) {
                    $response = json_decode(self::addFeaturedProduct($connection, $reference));
                }

                ($response->error) ? $connection->rollBack() : $connection->commit();

            } catch (PDOException $e) {
                $connection->rollBack();
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }
        
        return json_encode($response);
    }
    
    public static function addProduct ($connection, $objProduct) {
        $product = json_decode($objProduct);
        $position = ($product->quantity > 0) ? self::$available: self::$outStock;
        $offer = ($product->discount_percentage > 0) ? 1 : 0;

        if (isset($connection)) {
            try {
                $sql = 'INSERT INTO `product` (`category_ref`, `reference`, `img_url`, `alias`, `brand`, `information`, `cost`, `price`, `offer`, `discount_percentage`, `position`, `quantity`, `min_stock`, `date_add`) VALUES (:p_category_ref, :p_reference, :p_img_url, :p_alias, :p_brand, :p_information, :p_cost, :p_price, :p_offer, :p_discount_percentage, :p_position, :p_quantity, :p_min_stock, now())';

                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_category_ref', $product->category_ref, PDO::PARAM_STR);
                $sentencia->bindParam(':p_reference', $product->reference, PDO::PARAM_STR);
                $sentencia->bindParam(':p_img_url', $product->img_url, PDO::PARAM_STR);
                $sentencia->bindParam(':p_alias', $product->alias, PDO::PARAM_STR);
                $sentencia->bindParam(':p_brand', $product->brand, PDO::PARAM_STR);
                $sentencia->bindParam(':p_information', $product->information, PDO::PARAM_STR);
                $sentencia->bindParam(':p_cost', $product->cost, PDO::PARAM_STR);
                $sentencia->bindParam(':p_price', $product->price, PDO::PARAM_STR);
                $sentencia->bindParam(':p_offer', $offer, PDO::PARAM_INT);
                $sentencia->bindParam(':p_discount_percentage', $product->discount_percentage, PDO::PARAM_INT);
                $sentencia->bindParam(':p_position', $position, PDO::PARAM_INT);
                $sentencia->bindParam(':p_quantity', $product->quantity, PDO::PARAM_INT);
                $sentencia->bindParam(':p_min_stock', $product->min_stock, PDO::PARAM_INT);

                $sentencia->execute();
                
                $response = array('error' => false, 'message' => 'Se ha insertdo de forma exitosa.', 'data' => null);
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }

        return json_encode($response);
    }

    static function issetNameProduct ($connection, $alias) {

        if (isset($connection)) {
            try {
                $sql = 'SELECT * FROM product WHERE alias = :p_query AND position != :p_deleted';
                
                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_query', $alias, PDO::PARAM_STR);
                $sentencia->bindParam(':p_deleted', self::$deleted, PDO::PARAM_INT);
                $sentencia->execute();
                
                if (count($sentencia->fetchAll()) > 0) {
                    $response = array('error' => false, 'message' => 'El nombre asignado ya existe.', 'data' => 1);
                }else {
                    $response = array('error' => false, 'message' => 'El nombre asignado no existe.', 'data' => null);
                }
                
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos','data' => null);
        }
        
        return json_encode($response);
    }

    static function getProductByReference ($connection, $reference) {

        if (isset($connection)) {
            try {
                $sql = 'SELECT * FROM product WHERE reference = :p_reference LIMIT 1';
                
                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_reference', $reference, PDO::PARAM_STR);
                
                $sentencia->execute();
                $data = $sentencia->fetch();
                
                $response = array('error' => false, 'message' => 'Se ha extrído de forma correcta.', 'data' => $data);
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos','data' => null);
        }
        
        return json_encode($response);
    }

    static function apdateProduct ($connection, $objProduct) {
        $product = json_decode($objProduct);
        $position = ($product->quantity > 0 && $product->quantity >= $product->min_stock) ? self::$available: self::$outStock;
        $offer = ($product->discount_percentage > 0) ? 1 : 0;

        if (isset($connection)) {
            try {                
                $sql = ($product->img_url != '') ? 'UPDATE product SET category_ref = :p_category_ref,img_url = :p_img_url,alias = :p_alias,brand = :p_brand,information = :p_information,cost = :p_cost,price = :p_price,offer = :p_offer,discount_percentage = :p_discount_percentage,position = :p_position,quantity = :p_quantity,min_stock = :p_min_stock WHERE reference = :p_reference':'UPDATE product SET category_ref = :p_category_ref,alias = :p_alias,brand = :p_brand,information = :p_information,cost = :p_cost,price = :p_price,offer = :p_offer,discount_percentage = :p_discount_percentage,position = :p_position,quantity = :p_quantity, min_stock = :p_min_stock WHERE reference = :p_reference';

                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_category_ref', $product->category_ref, PDO::PARAM_STR);
                if ($product->img_url != '') {$sentencia->bindParam(':p_img_url', $product->img_url, PDO::PARAM_STR);}
                $sentencia->bindParam(':p_alias', $product->alias, PDO::PARAM_STR);
                $sentencia->bindParam(':p_brand', $product->brand, PDO::PARAM_STR);
                $sentencia->bindParam(':p_information', $product->information, PDO::PARAM_STR);
                $sentencia->bindParam(':p_cost', $product->cost, PDO::PARAM_STR);
                $sentencia->bindParam(':p_price', $product->price, PDO::PARAM_STR);
                $sentencia->bindParam(':p_offer', $offer, PDO::PARAM_INT);
                $sentencia->bindParam(':p_discount_percentage', $product->discount_percentage, PDO::PARAM_INT);
                $sentencia->bindParam(':p_position', $position, PDO::PARAM_INT);
                $sentencia->bindParam(':p_quantity', $product->quantity, PDO::PARAM_INT);
                $sentencia->bindParam(':p_min_stock', $product->min_stock, PDO::PARAM_INT);
                $sentencia->bindParam(':p_reference', $product->reference, PDO::PARAM_STR);

                $sentencia->execute();
                
                $response = array('error' => false, 'message' => 'Se ha actualizado de forma exitosa.', 'data' => null);
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }

        return json_encode($response);
    }

    static function getProductsForSale ($connection, $limit) {

        if (isset($connection)) {
            try {
                $sql = 'SELECT p.reference, p.alias, p.quantity, p.date_add, p.cost, p.price FROM product p LEFT JOIN current_sale c ON p.reference=c.reference_pro WHERE p.position = :p_available and c.reference_pro is NULL ORDER BY date_add DESC LIMIT :p_limit';  

                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_available', self::$available, PDO::PARAM_INT);
                $sentencia->bindParam(':p_limit', $limit, PDO::PARAM_INT);

                $sentencia->execute();
                $data = $sentencia->fetchAll();
                
                $response = array('error' => false, 'message' => 'Se ha extraído de forma exitosa.', 'data' => $data);
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }

        return json_encode($response);
    }

    static function searchProductsForSale ($connection, $alias, $limit) {
        $query = '%'.$alias.'%';
        if (isset($connection)) {
            try {
                $sql = 'SELECT p.reference, p.alias, p.quantity, p.date_add, p.cost, p.price FROM product p LEFT JOIN current_sale c ON p.reference=c.reference_pro WHERE p.position = :p_available and c.reference_pro is NULL and alias LIKE :p_query ORDER BY date_add DESC LIMIT :p_limit';

                $sentencia = $connection->prepare($sql);
                $sentencia->bindParam(':p_available', self::$available, PDO::PARAM_INT);
                $sentencia->bindParam(':p_query', $query, PDO::PARAM_STR);
                $sentencia->bindParam(':p_limit', $limit, PDO::PARAM_INT);

                $sentencia->execute();
                $data = $sentencia->fetchAll();
                
                $response = array('error' => false, 'message' => 'Se ha extraído de forma exitosa.', 'data' => $data);
            } catch (PDOException $e) {
                $response = array('error' => true, 'message' => $e->getMessage(),'data' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'No hay conexión a la base de datos.','data' => null);
        }

        return json_encode($response);
    }

}

?>