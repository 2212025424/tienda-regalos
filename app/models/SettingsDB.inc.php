<?php

class SettingsDB {

    private static $configKey = 'general_information';

    public static function getGeneralInformation ($connection) {
        if (isset($connection)) {
            try {
                
                $sql = 'SELECT mission, vision, fb_link, wp_number FROM settings LIMIT 1';
                $sentencia = $connection->prepare($sql);
                $sentencia->execute();

                $data = $sentencia->fetch();

                $response = json_encode(array('error' => false, 'message' => 'Se ha extraído de forma exitosa.','data' => $data));
                
            } catch (PDOException $e) {
                $response = json_encode(array('error' => true, 'message' => $e->getMessage(),'data' => null));
            }
        }else {
            $response = json_encode(array('error' => true, 'message' => 'No hay conexión a la base de datos','data' => null));
        }

        return $response;
    }

    public static function updateDataLinks ($connection, $fb, $wp) {
        if (isset($connection)) {
            try {
                
                $sql = 'UPDATE settings SET wp_number = :p_wp, fb_link = :p_fb  WHERE reference = :p_configkey';
                $sentencia = $connection->prepare($sql);                
                $sentencia->bindParam(':p_wp', $wp, PDO::PARAM_STR);
                $sentencia->bindParam(':p_fb', $fb, PDO::PARAM_STR);
                $sentencia->bindParam(':p_configkey', self::$configKey, PDO::PARAM_STR);
                $sentencia->execute();

                $response = json_encode(array('error' => false, 'message' => 'Se ha actualizado de forma exitosa.','data' => null));
                
            } catch (PDOException $e) {
                $response = json_encode(array('error' => true, 'message' => $e->getMessage(),'data' => null));
            }
        }else {
            $response = json_encode(array('error' => true, 'message' => 'No hay conexión a la base de datos','data' => null));
        }

        return $response;
    }

    public static function updateDataContent ($connection, $mission, $vision) {
        if (isset($connection)) {
            try {
                
                $sql = 'UPDATE settings SET mission = :p_mission, vision = :p_vision  WHERE reference = :p_configkey';
                $sentencia = $connection->prepare($sql);                
                $sentencia->bindParam(':p_mission', $mission, PDO::PARAM_STR);
                $sentencia->bindParam(':p_vision', $vision, PDO::PARAM_STR);
                $sentencia->bindParam(':p_configkey', self::$configKey, PDO::PARAM_STR);
                $sentencia->execute();

                $response = json_encode(array('error' => false, 'message' => 'Se ha actualizado de forma exitosa.','data' => null));
                
            } catch (PDOException $e) {
                $response = json_encode(array('error' => true, 'message' => $e->getMessage(),'data' => null));
            }
        }else {
            $response = json_encode(array('error' => true, 'message' => 'No hay conexión a la base de datos','data' => null));
        }

        return $response;
    }

    public static function getCredentials ($connection) {
        if (isset($connection)) {
            try {
                
                $sql = 'SELECT username, pass FROM settings LIMIT 1';
                $sentencia = $connection->prepare($sql);
                $sentencia->execute();
                $data = $sentencia->fetch();
                
                $response = json_encode(array('error' => false, 'message' => 'Se ha extraído de forma exitosa.', 'data' => $data));

            } catch (PDOException $e) {
                $response = json_encode(array('error' => true, 'message' => $e->getMessage(),'data' => null));
            }
        }else {
            $response = json_encode(array('error' => true, 'message' => 'No hay conexión a la base de datos','data' => null));
        }

        return $response;
    }
    
    public static function updateDataCredentials ($connection, $username, $password) {
        if (isset($connection)) {
            try {
                
                $sql = 'UPDATE settings SET username = :p_username, pass = :p_pass  WHERE reference = :p_configkey';
                $sentencia = $connection->prepare($sql);                
                $sentencia->bindParam(':p_username', $username, PDO::PARAM_STR);
                $sentencia->bindParam(':p_pass', $password, PDO::PARAM_STR);
                $sentencia->bindParam(':p_configkey', self::$configKey, PDO::PARAM_STR);
                $sentencia->execute();

                $response = json_encode(array('error' => false, 'message' => 'Se han actualizado las credenciales.','data' => null));
                
            } catch (PDOException $e) {
                $response = json_encode(array('error' => true, 'message' => $e->getMessage(),'data' => null));
            }
        }else {
            $response = json_encode(array('error' => true, 'message' => 'No hay conexión a la base de datos','data' => null));
        }

        return $response;
    }

    
}