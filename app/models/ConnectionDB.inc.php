<?php

class Connection {
    
    private static $connection;
    
    public static function open_connection(){
        if (!isset(self::$connection)){
            try {
                self::$connection = new PDO('mysql:host='.SERVER_DB.'; dbname='.NAME_DB, USER_DB, PASSWORD_DB);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->exec("SET CHARACTER SET utf8");
            } catch (PDOException $ex) {
                //print "ERROR: ".$ex -> getMessage(). "<br>";
                //die();
            }
        }
    }
    
    public static function close_connection (){
        if (isset(self::$connection)){
            self::$connection = null;
        }
    }
    
    public static function get_connection (){
        return self::$connection;
    }
    
}