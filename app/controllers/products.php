<?php
include_once '../cross/config.inc.php';
include_once '../cross/Validador.inc.php';
include_once '../cross/ProductValidator.inc.php';
include_once '../cross/ImageValidator.inc.php';
include_once '../models/ConnectionDB.inc.php';
include_once '../models/ProductDB.inc.php';

function getProducts () {
    $page = (isset($_POST['page']) && !empty($_POST['page']) && is_numeric($_POST['page']) && $_POST['page'] >= 0) ? (int) $_POST['page'] : 0;
    $limit = (isset($_POST['limit']) && !empty($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] >= 0) ? (int) $_POST['limit'] : 10;

    Connection::open_connection();
    $response = ProductDB::getProducts(Connection::get_connection(), $page, $limit);
    Connection::close_connection();
    
    return $response;
}

function highlightProduct () {
    $reference = (isset($_POST['reference'])) ? $_POST['reference'] : '';
    
    Connection::open_connection();
    $response = ProductDB::highlightProduct(Connection::get_connection(), $reference);
    Connection::close_connection();
    
    return $response;
}

function removeProduct () {
    $reference = (isset($_POST['reference'])) ? $_POST['reference'] : '';
    
    Connection::open_connection();
    $response = ProductDB::removeProduct(Connection::get_connection(), $reference);
    Connection::close_connection();
    
    return $response;
}

function issetNameProduct () {
    $alias = (isset($_POST['alias'])) ? $_POST['alias'] : '';

    Connection::open_connection();
    $response = ProductDB::issetNameProduct(Connection::get_connection(), $alias);
    Connection::close_connection();
    
    return $response;   
}

function addProduct () {
    $alias = (isset($_POST['alias'])) ? $_POST['alias'] : '';
    $brand = (isset($_POST['brand'])) ? $_POST['brand'] : '';
    $category = (isset($_POST['category'])) ? $_POST['category'] : '';
    $information = (isset($_POST['information'])) ? $_POST['information'] : '';
    $cost = (isset($_POST['cost'])) ? $_POST['cost'] : '';
    $price = (isset($_POST['price'])) ? $_POST['price'] : '';
    $discount = (isset($_POST['discount'])) ? $_POST['discount'] : '';
    $quantity = (isset($_POST['quantity'])) ? $_POST['quantity'] : '';
    $min = (isset($_POST['min-stock'])) ? $_POST['min-stock'] : '';
    $image = (isset($_FILES['image']['tmp_name'])) ? $_FILES['image']['tmp_name'] : '';

    $validador_p = new ProductValidator($alias, $brand, $category, $information, $cost, $price, $discount, $quantity, $min);
    $validador_i = new ImageValidator($image, $_FILES['image']['name'], $_FILES['image']['size'], explode("/", $validador_p->get_category())[1]);
    
    if (json_decode($validador_p->allDataIsCorrect())->error) {
        $response = $validador_p->allDataIsCorrect();
    } else if (json_decode($validador_i->validateImage())->error) {
        $response = $validador_i->validateImage();
    } else {
        $obj_pro = json_encode(array('category_ref' => explode("/", $validador_p->get_category())[0],'reference' => 'pro_'.time().'_ref','img_url' => $validador_i->get_new_url(),'alias' => $validador_p->get_alias(),'brand' => $validador_p->get_brand(),'information' => $validador_p->get_information(),'cost' => $validador_p->get_cost(),'price' => $validador_p->get_price(),'discount_percentage' => $validador_p->get_discount(),'quantity' => $validador_p->get_quantity(),'min_stock' =>$validador_p->get_min()));

        Connection::open_connection();
        $response = ProductDB::addProduct(Connection::get_connection(), $obj_pro);
        Connection::close_connection();
    }

    return $response;
}

function getProductForUpdate () {
    $reference = (isset($_POST['reference']) ? $_POST['reference'] : '');

    Connection::open_connection();
    $response = ProductDB::getProductByReference(Connection::get_connection(), $reference);
    Connection::close_connection();

    return $response;
}

function apdateProduct () {
    $reference = (isset($_POST['reference'])) ? $_POST['reference'] : '';
    $alias = (isset($_POST['alias'])) ? $_POST['alias'] : '';
    $brand = (isset($_POST['brand'])) ? $_POST['brand'] : '';
    $category = (isset($_POST['category'])) ? $_POST['category'] : '';
    $information = (isset($_POST['information'])) ? $_POST['information'] : '';
    $cost = (isset($_POST['cost'])) ? $_POST['cost'] : '';
    $price = (isset($_POST['price'])) ? $_POST['price'] : '';
    $discount = (isset($_POST['discount'])) ? $_POST['discount'] : '';
    $quantity = (isset($_POST['quantity'])) ? $_POST['quantity'] : '';
    $min = (isset($_POST['min-stock'])) ? $_POST['min-stock'] : '';
    $image = (isset($_FILES['image']['tmp_name'])) ? $_FILES['image']['tmp_name'] : '';
    $response = "";

    if (!empty($reference)) {
        $validador_p = new ProductValidator($alias, $brand, $category, $information, $cost, $price, $discount, $quantity, $min);
        $obj_pro = json_encode(array('category_ref' => explode("/", $validador_p->get_category())[0],'reference' => $reference,'img_url' => '','alias' => $validador_p->get_alias(),'brand' => $validador_p->get_brand(),'information' => $validador_p->get_information(),'cost' => $validador_p->get_cost(),'price' => $validador_p->get_price(),'discount_percentage' => $validador_p->get_discount(),'quantity' => $validador_p->get_quantity(),'min_stock' =>$validador_p->get_min()));
        $response = $validador_p->allDataIsCorrect();

        if (!json_decode($response)->error && !empty($image)) {
            $validador_i = new ImageValidator($image, $_FILES['image']['name'], $_FILES['image']['size'], explode("/", $validador_p->get_category())[1]);
            $response = $validador_i->validateImage();
            if (!json_decode($response)->error){
                $obj_pro = json_encode(array('category_ref' => explode("/", $validador_p->get_category())[0],'reference' => $reference,'img_url' => $validador_i->get_new_url(),'alias' => $validador_p->get_alias(),'brand' => $validador_p->get_brand(),'information' => $validador_p->get_information(),'cost' => $validador_p->get_cost(),'price' => $validador_p->get_price(),'discount_percentage' => $validador_p->get_discount(),'quantity' => $validador_p->get_quantity(),'min_stock' =>$validador_p->get_min()));
            }
        }

        if (!json_decode($response)->error) {
            Connection::open_connection();
            $response = ProductDB::apdateProduct(Connection::get_connection(), $obj_pro);
            Connection::close_connection();
        }
    }

    return $response;

}

if (isset($_POST['request']) && !empty($_POST['request'])) {
    switch ($_POST['request']) {
        case 'getProducts':
            echo getProducts();
            break;
        case 'highlightProduct':
            echo highlightProduct();
            break;
        case 'removeProduct':
            echo removeProduct();
            break;
        case 'issetNameProduct':
            echo issetNameProduct();
            break;
        case 'addProduct':
            echo addProduct();
            break;
        case 'getProductForUpdate':
            echo getProductForUpdate();
            break;
        case 'apdateProduct':
            echo apdateProduct();
            break;
    }
}else {
    echo json_encode(array('error' => true, 'message' => 'Error en la configuracion AJAX.', 'data' => null));
}

?>