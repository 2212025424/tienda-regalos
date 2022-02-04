<?php
include_once '../cross/config.inc.php';
include_once '../models/ConnectionDB.inc.php';
include_once '../models/SaleDB.inc.php';
include_once '../models/ProductDB.inc.php';
include_once '../cross/SaleValidator.inc.php';

function getSales () {
    $page = (isset($_POST['page']) && !empty($_POST['page']) && is_numeric($_POST['page']) && $_POST['page'] >= 0) ? (int) $_POST['page'] : 0;
    $limit = (isset($_POST['limit']) && !empty($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] >= 0) ? (int) $_POST['limit'] : 10;

    Connection::open_connection();
    $response = SaleDB::getSales(Connection::get_connection(), $page, $limit);
    Connection::close_connection();
    
    return $response;
}

function getProductsForSale () {
    $limit = (isset($_POST['limit']) && !empty($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] > 0) ? (int) $_POST['limit'] : 5;

    Connection::open_connection();
    $response = ProductDB::getProductsForSale(Connection::get_connection(), $limit);
    Connection::close_connection();

    return $response;
}

function searchProductsForSale () {
    $limit = (isset($_POST['limit']) && !empty($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] > 0) ? (int) $_POST['limit'] : 5;
    $alias = (isset($_POST['alias']) && !empty($_POST['alias'])) ? $_POST['alias'] : '';

    Connection::open_connection();
    $response = ProductDB::searchProductsForSale(Connection::get_connection(), $alias, $limit);
    Connection::close_connection();

    return $response;
}

function addTemporalSale () {
    $reference = (isset($_POST['reference']) && !empty($_POST['reference'])) ? $_POST['reference'] : '';

    $validador = new SaleValidator($reference, 1);
    $response = $validador->validateSale();
    $validado = json_decode($response);

    if (!$validado->error) {
        Connection::open_connection();
        $response = SaleDB::addTemporalSale(Connection::get_connection(), $validador->get_reference(), $validador->get_subtotal(), $validador->get_total(), $validador->get_current_q(), $validador->get_required_q());
        Connection::open_connection();
    }

    return $response;
}

function getTemporalSales () {

    Connection::open_connection();
    $response = SaleDB::getTemporalSales(Connection::get_connection());
    Connection::close_connection();

    return $response;
}

function removeFromTemporalSale () {
    $reference = (isset($_POST['reference'])) ? $_POST['reference'] : '';
    
    Connection::open_connection();
    $response = SaleDB::removeFromTemporalSale(Connection::get_connection(), $reference);
    Connection::close_connection();

    return $response;
}

function updateTemporalSale () {
    $reference = (isset($_POST['reference'])) ? $_POST['reference'] : '';
    $quantity = (isset($_POST['quantity'])) ? (int) $_POST['quantity'] : 1;
    
    $validador = new SaleValidator($reference, $quantity);
    $response = $validador->validateUpdateSale();
    $validado = json_decode($response);

    if (!$validado->error) {
        Connection::open_connection();
        $response = SaleDB::updateTemporalSale(Connection::get_connection(), $validador->get_reference(), $validador->get_subtotal(), $validador->get_total(), $validador->get_current_q(), $validador->get_required_q());
        Connection::open_connection();
    }

    return $response;
}

function getTotalTemporalSale () {
    Connection::open_connection();
    $response = SaleDB::getTotalTemporalSale(Connection::get_connection());
    Connection::open_connection();

    return $response;
}

function closeTemporalSale () {
    Connection::open_connection();
    $response = SaleDB::closeTemporalSale(Connection::get_connection());
    Connection::open_connection();

    return $response;
}

function getSaleDetails () {
    $reference = ($_POST['reference']) ? $_POST['reference'] : '';

    Connection::open_connection();
    $response = SaleDB::getSaleDetails(Connection::get_connection(), $reference);
    Connection::open_connection();

    return $response;
}

function cancelAllSale () {
    $reference = ($_POST['reference']) ? $_POST['reference'] : '';

    Connection::open_connection();
    $response = SaleDB::cancelAllSale(Connection::get_connection(), $reference);
    Connection::open_connection();

    return $response;
}

if (isset($_POST['request']) && !empty($_POST['request'])) {
    switch ($_POST['request']) {
        case 'getSales':
            echo getSales();
            break;
        case 'getProductsForSale':
            echo getProductsForSale();
            break;
        case 'searchProductsForSale':
            echo searchProductsForSale();
            break;
        case 'addTemporalSale':
            echo addTemporalSale();
            break;
        case 'getTemporalSales':
            echo getTemporalSales();
            break;
        case 'removeFromTemporalSale':
            echo removeFromTemporalSale();
            break;
        case 'updateTemporalSale':
            echo updateTemporalSale();
            break;
        case 'getTotalTemporalSale':
            echo getTotalTemporalSale();
            break;
        case 'closeTemporalSale':
            echo closeTemporalSale();
            break;
        case 'getAllSaleDetails':
            echo getSaleDetails();
            break;
        case 'cancelAllSale':
            echo cancelAllSale();
            break;
        
    }
}else {
    echo json_encode(array('error' => true, 'message' => 'Error en la configuracion AJAX.', 'data' => null));
}

?>