<?php

class Redirect {

	public static function redirectTo ($url) {
		header('Location:'. $url, true, 301);
        exit();
	}

}

?>