<?php

class ProductValidator {
	
	private $alias;
	private $brand;
	private $category;
	private $information;
	private $cost;
	private $price;
	private $discount;
	private $quantity;
	private $min;

	function __construct($alias,$brand,$category,$information,$cost,$price,$discount,$quantity,$min) {
		$this->alias = $alias;
		$this->brand = $brand;
		$this->category = $category;
		$this->information = $information;
		$this->cost = $cost;
		$this->price = $price;
		$this->discount = $discount;
		$this->quantity = $quantity;
		$this->min = $min;
	}

	private function clean_string ($string) {
		$string = trim($string);
        $string = preg_replace(['/\s+/','/^\s|\s$/'],[' ',''], $string);
        return $string;
	}

	private function clean_number ($number) {
        $number = str_replace(' ', '', $number);
        return $number;
	}

	private function validate_atribute ($name, $string, $limiteInf, $limiteSup) {
		if (!empty($string)) {
            if ($limiteInf && mb_strlen($string, 'utf8')<$limiteInf) {
                $response = array('error' => true, 'message' => 'Atributo ['.$name.'] es muy corto.');
            } else if ($limiteSup && mb_strlen($string, 'utf8')>$limiteSup) {
                $response = array('error' => true, 'message' => 'Atributo ['.$name.'] es muy largo.');
            }else {
                $response = array('error' => false, 'message' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'Atributo ['.$name.'] no puede quedar vacio.');
        }

        return json_encode($response);
	}

	private function validate_currency ($name, $currency, $limiteInfE, $limiteSup) {
		$limiteInf = ($limiteInfE == 0) ? $limiteInfE + 1 : $limiteInfE;
		$currency = ($limiteInfE == 0) ? $currency + 1 : $currency;
		
		if ($currency !== "") {
			if (!is_numeric($currency)) {
				$response = array('error' => true, 'message' => 'Atributo ['.$name.'] debe ser numérico.');
			} else if ($limiteInf && ($currency < $limiteInf)) {
                $response = array('error' => true, 'message' => 'Atributo ['.$name.'] debe ser mayor que ['.$limiteInfE.'].');
            } else if ($limiteSup && $currency>$limiteSup) {
                $response = array('error' => true, 'message' => 'Atributo ['.$name.'] debe ser menor que ['.$limiteSup.'].');
            } else {
                $response = array('error' => false, 'message' => null);
            }
        }else {
            $response = array('error' => true, 'message' => 'Atributo ['.$name.'] no puede quedar vacio.');
        }

        return json_encode($response);
	}

	private function is_valid_alias () {
		$this->alias = $this->clean_string($this->alias);
		return $this->validate_atribute('Nombre', $this->alias, 10, 20);
	}

	private function is_valid_brand () {
		$this->brand = $this->clean_string($this->brand);
		return $this->validate_atribute('Marca', $this->brand, 3, 20);
	}

	private function is_valid_category () {
		$this->category = $this->clean_string($this->category);
		return $this->validate_atribute('Categoría', $this->category, 20, null);
	}

	private function is_valid_information () {
		$this->information = $this->clean_string($this->information);
		return $this->validate_atribute('Descripción', $this->information, 5, 50);
	}

	private function is_valid_cost () {
		$this->cost = $this->clean_number($this->cost);
		return $this->validate_currency('Costo', $this->cost, 0, null);
	}

	private function is_valid_price () {
		$this->price = $this->clean_number($this->price);
		return $this->validate_currency('Precio', $this->price, 0, null);
	}

	private function is_valid_discount () {
		$this->discount = $this->clean_number($this->discount);
		return $this->validate_currency('Descuento', $this->discount, 0, 100);
	}

	private function is_valid_quantity () {
		$this->quantity = $this->clean_number($this->quantity);
		return $this->validate_currency('Cantidad', $this->quantity, 0, null);
	}

	private function is_valid_min () {
		$this->min = $this->clean_number($this->min);
		return $this->validate_currency('Stock', $this->min, 0, null);
	}

	public function get_alias () {
		return $this->alias;
	}

	public function get_brand () {
		return $this->brand;
	}

	public function get_category () {
		return $this->category;
	}

	public function get_information () {
		return $this->information;
	}

	public function get_cost () {
		return $this->cost;
	}

	public function get_price () {
		return $this->price;
	}

	public function get_discount () {
		return $this->discount;
	}

	public function get_quantity () {
		return $this->quantity;
	}

	public function get_min () {
		return $this->min;
	}

	public function allDataIsCorrect () {
		if (json_decode($this->is_valid_alias())->error) {
			return $this->is_valid_alias();
		} else if (json_decode($this->is_valid_brand())->error) {
			return $this->is_valid_brand();
		} else if (json_decode($this->is_valid_information())->error) {
			return $this->is_valid_information();
		} else if (json_decode($this->is_valid_cost())->error) {
			return $this->is_valid_cost();
		} else if (json_decode($this->is_valid_price())->error) {
			return $this->is_valid_price();
		} else if (json_decode($this->is_valid_discount())->error) {
			return $this->is_valid_discount();
		} else if (json_decode($this->is_valid_quantity())->error) {
			return $this->is_valid_quantity();
		} else if (json_decode($this->is_valid_min())->error) {
			return $this->is_valid_min();
		} else {
			return json_encode(array('error' => false, 'message' => null));
		}
	}
}

?>