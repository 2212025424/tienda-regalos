<?php

class SessionManager {
    
    public static function start_sesion($usuario){
        if(session_id() == ''){
            session_start();
        }
        $_SESSION['usuario'] = $usuario;
    }
    
    public static function stop_sesion(){
        if(session_id()==''){
            session_start();
        }
        if(isset($_SESSION['usuario'])){
            unset($_SESSION['usuario']);
        }
        session_destroy();
    }
    
    public static function isset_sesion (){
        if(session_id() == ''){
            session_start();
        }
        if (isset($_SESSION['usuario'])) {
            return true;
        } else {
            return false;
        }
    }
}

?>