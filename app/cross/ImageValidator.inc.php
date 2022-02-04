<?php

class ImageValidator {

    private $allowed_extensions = array('jpg', 'jpeg', 'gif', 'png', 'tif', 'tiff', 'bmp');
    private $allowed_size = 1000000; // bytes
    
    private $image_extension;
    private $new_image_name;
    private $tmp_name;
    private $original_name;
    private $size_image;
    private $base_url;

    function __construct ($tmp_name, $original_name, $size_image, $base_url) {
        $this->tmp_name = $tmp_name;
        $this->original_name = $original_name;
        $this->size_image = $size_image;
        $this->base_url = $base_url;

        if (!empty($original_name)) {
            $array = explode('.',$original_name);
            $this->image_extension = strtolower(end($array));
            $this->new_image_name = strtolower(time().'.'.$this->image_extension);
        }
    }

    public function get_new_url () {
        return $this->base_url.'/'.$this->new_image_name;
    }

    public function validateImage () {

        if (empty($this->tmp_name)) {
            $response = array('error' => true, 'message' => 'Agrega una imagen.');
        }else if (!in_array($this->image_extension, $this->allowed_extensions)) {
            $response = array('error' => true, 'message' => 'Formato de imagen no permitido.');
        } else if ($this->size_image > $this->allowed_size) {
            $response = array('error' => true, 'message' => 'Sube imágenes menores a 1000kb.');
        } else if ((!file_exists("../../".TARGET_DYNAMIC_IMAGES.$this->base_url)) && (!mkdir("../../".TARGET_DYNAMIC_IMAGES.$this->base_url, 0777, true))) {
            $response = array('error' => true, 'message' => 'No se ha creado el fichero, reintenta.');
        }else if (!move_uploaded_file($this->tmp_name, "../../".TARGET_DYNAMIC_IMAGES.$this->base_url.'/'.$this->new_image_name)) {
            $response = array('error' => true, 'message' => 'No se ha movido la imagen, reintenta.');
        }else {
            $response = array('error' => false, 'message' => null);
        }

        return json_encode($response);
    }

}

?>