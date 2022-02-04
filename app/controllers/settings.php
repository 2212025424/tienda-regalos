<?php
include_once '../cross/config.inc.php';
include_once '../cross/Validador.inc.php';
include_once '../models/ConnectionDB.inc.php';
include_once '../models/SettingsDB.inc.php';

function getContentData () {
    Connection::open_connection();
    $response = SettingsDB::getGeneralInformation(Connection::get_connection());
    Connection::close_connection();
    return $response;
}

function updateDataLinks () {

    if (isset($_POST['fb_link']) && isset($_POST['wp_number'])) {
        $fb = json_decode(Validator::validate_string($_POST['fb_link'], 15, null));
        $wp = json_decode(Validator::validate_telephone($_POST['wp_number'], 12, 12));

        if (!$fb->error && !$wp->error) {
            Connection::open_connection();
            $response = SettingsDB::updateDataLinks(Connection::get_connection(), $fb->data, $wp->data);
            Connection::close_connection();
        }else {
            $response = json_encode(array('error' => true, 'message' => 'Los datos ingresados no son correctos.', 'data' => null));
        }   
    } else {
        $response = json_encode(array('error' => true, 'message' => 'Error de configuracion de formularios.', 'data' => null));
    }

    return $response;
}

function updateDataContent () {

    if (isset($_POST['mission']) && isset($_POST['vision'])) {
        $mission = json_decode(Validator::validate_string($_POST['mission'], null, null));
        $vision = json_decode(Validator::validate_string($_POST['vision'], null, null));

        if (!$mission->error && !$vision->error) {
            Connection::open_connection();
            $response = SettingsDB::updateDataContent(Connection::get_connection(), $mission->data, $vision->data);
            Connection::close_connection();
        }else {
            $response = json_encode(array('error' => true, 'message' => 'Los datos ingresados no son correctos.', 'data' => null));
        }
    } else {
        $response = json_encode(array('error' => true, 'message' => 'Error de configuracion de formularios.', 'data' => null));
    }

    return $response;
}

function updateDataCredentials () {

    if (isset($_POST['username']) && isset($_POST['current_pass']) && isset($_POST['pass_1']) && isset($_POST['pass_2'])) {
        $user = json_decode(Validator::validate_string($_POST['username'], null, null));
        $c_pass = $_POST['current_pass'];
        $pass_1 = $_POST['pass_1'];
        $pass_2 = $_POST['pass_2'];
        
        $pass = json_decode(Validator::validate_credentials($pass_1, $pass_2, 5, 10));
        
        if (!$user->error) {
            if (!$pass->error) {
            
                Connection::open_connection();
                $response = json_decode(SettingsDB::getCredentials(Connection::get_connection()));
                Connection::close_connection();
    
                if (!$response->error) {
                    if (password_verify($c_pass, $response->data->pass)) {
                        Connection::open_connection();  
                        $response = SettingsDB::updateDataCredentials(Connection::get_connection(), $user->data, password_hash($pass->data, PASSWORD_DEFAULT));
                        Connection::close_connection();
                    }else {
                        $response = json_encode(array('error' => true, 'message' => 'La contraseña actual no es correcta.', 'data' => null));
                    }
                }else {
                    $response = json_encode($response);
                }
    
            }else {
                $response = json_encode($pass);
            }
            
        }else {
            $response = json_encode(array('error' => true, 'message' => 'Los datos ingresados no son correctos.', 'data' => null));
        }
        
    } else {
        $response = json_encode(array('error' => true, 'message' => 'Error de configuracion de formularios.', 'data' => null));
    }

    return $response;
}

if (isset($_POST['request']) && !empty($_POST['request'])) {
    switch ($_POST['request']) {
        case 'getContentData':
            echo getContentData();
            break;
        case 'updateDataLinks':
            echo updateDataLinks();
            break;
        case 'updateDataContent':
            echo updateDataContent();
            break;
        case 'updateDataCredentials':
            echo updateDataCredentials();
            break;
    }
}else {
    echo json_encode(array('error' => true, 'message' => 'Error en la configuracion AJAX.', 'data' => null));
}



?>