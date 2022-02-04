<?php

include_once '../models/ConnectionDB.inc.php';
include_once '../models/ProductDB.inc.php';
include_once '../models/SaleDB.inc.php';

class SaleValidator {

    private $reference;
    private $required_q;
    private $current_q;
    private $subtotal;
    private $total;

    function __construct ($reference, $required_q) {
        $this->reference = $reference;
        $this->required_q = $required_q;
    }

    function get_reference () {
        return $this->reference;
    }

    function get_subtotal () {
        return $this->subtotal;
    }

    function get_total () {
        return $this->total;
    }

    function get_required_q () {
        return $this->required_q;
    }

    function get_current_q () {
        return $this->current_q;
    }

    public function validateSale () {
        Connection::open_connection();
        $response = json_decode(ProductDB::getProductByReference(Connection::get_connection(), $this->reference));
        Connection::close_connection();

        if ($response->error or !$response->data) {
            $response = array('error' => true, 'message' => 'No se ha encontrado el producto.');
        }else if ($response->data->quantity < $this->required_q) {
            $response = array('error' => true, 'message' => 'Lo solicitado excede el stock.');
        }else {
            $this->current_q = $response->data->quantity;
            $this->total = ($response->data->offer > 0) ? ($response->data->price - ($response->data->discount_percentage * $response->data->price / 100)) * $this->required_q : $response->data->price * $this->required_q;
            $this->subtotal = $response->data->price * $this->required_q;
            $response = array('error' => false, 'message' => 'Todo cool.');
        }

        return json_encode($response);
    }

    public function validateUpdateSale () {
        Connection::open_connection();
        $response1 = json_decode(ProductDB::getProductByReference(Connection::get_connection(), $this->reference));
        $response2 = json_decode(SaleDB::getQuantityProduct(Connection::get_connection(), $this->reference));
        Connection::close_connection();

        $response = ($response1->error) ? $response1 : $response2;

        if ($response->error or !$response1->data) {
            $response = array('error' => true, 'message' => 'Selecciona un producto.');
        }else if (($response1->data->quantity + $response2->data) < $this->required_q) {
            $response = array('error' => true, 'message' => 'Lo solicitado excede el stock.');
        }else {
            $this->current_q = ($response1->data->quantity + $response2->data);
            $this->total = ($response1->data->offer > 0) ? ($response1->data->price - ($response1->data->discount_percentage * $response1->data->price / 100)) * $this->required_q : $response1->data->price * $this->required_q;
            $this->subtotal = $response1->data->price * $this->required_q;
            $response = array('error' => false, 'message' => 'Todo cool.');
        }

        return json_encode($response);
    }
}

?>