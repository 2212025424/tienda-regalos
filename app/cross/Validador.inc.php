<?php

class Validator {

    static function validate_string ($cadena, $limiteInf, $limiteSup) {
        $cadena = trim($cadena);
        $cadena = preg_replace(['/\s+/','/^\s|\s$/'],[' ',''], $cadena);
        
        if (!empty($cadena)) {
            if ($limiteInf && mb_strlen($cadena, 'utf8')<$limiteInf) {
                $response = json_encode(array('error' => true, 'message' => 'Fuera del limite inferior.', 'data' => $cadena));
            } else if ($limiteSup && mb_strlen($cadena, 'utf8')>$limiteSup) {
                $response = json_encode(array('error' => true, 'message' => 'Fuera del limite inferior.', 'data' => $cadena));
            }else {
                $response = json_encode(array('error' => false, 'message' => null, 'data' => $cadena));
            }
        }else {
            $response = json_encode(array('error' => true, 'message' => 'Campo vacio.', 'data' => $cadena));
        }

        return $response;
    }
    
    static function validate_telephone ($number, $limiteInf, $limiteSup) {
        $cadena = str_replace(' ', '', $number);
        
        if (!empty($cadena)) {
            if (!is_numeric($cadena)) {
                $response = json_encode(array('error' => true, 'message' => 'Se requiere un número.', 'data' => $cadena));
            } else if ($limiteInf && mb_strlen($cadena, 'utf8')<$limiteInf) {
                $response = json_encode(array('error' => true, 'message' => 'Fuera del limite inferior.', 'data' => $cadena));
            } else if ($limiteSup && mb_strlen($cadena, 'utf8')>$limiteSup) {
                $response = json_encode(array('error' => true, 'message' => 'Fuera del limite inferior.', 'data' => $cadena));
            }else {
                $response = json_encode(array('error' => false, 'message' => null, 'data' => $cadena));
            }
        }else {
            $response = json_encode(array('error' => true, 'message' => 'Campo vacio.', 'data' => $cadena));
        }

        return $response;
    }
    
    static function validate_credentials ($pass1, $pass2, $limiteInf, $limiteSup) {

        if (strcmp($pass1, $pass2) == 0) {
            if (mb_strlen($pass1, 'utf8')>$limiteSup) {
                $response = json_encode(array('error' => true, 'message' => 'La contraseña es muy larga.', 'data' => null));
            }else if (mb_strlen($pass1, 'utf8')<$limiteInf) {
                $response = json_encode(array('error' => true, 'message' => 'La contraseña es muy corta.', 'data' => null));
            }else {
                $response = json_encode(array('error' => false, 'message' => null, 'data' => $pass1));
            }
        }else {
            $response = json_encode(array('error' => true, 'message' => 'Las contraseñas no coinciden.', 'data' => $pass1));
        }

        return $response;
    }
}

?>