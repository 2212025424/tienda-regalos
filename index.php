<?php

include_once 'app/cross/config.inc.php';

$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

switch ($current_url) {
	case ROUTE_HOME: 
		$ruta_elegida = 'views/home.php';
		break;
	case ROUTE_LOGIN:
		$ruta_elegida = 'views/login.php';
		break;
	case ROUTE_SETTINGS:
		$ruta_elegida = 'views/settings.php';
		break;
	case ROUTE_PRODUCTS:
		$ruta_elegida = 'views/products.php';
		break;
	case ROUTE_SALES:
		$ruta_elegida = 'views/sales.php';
		break;
	case ROUTE_LOGOUT:
		$ruta_elegida = 'views/logout.php';
		break;
	default: 
		$ruta_elegida = 'views/home.php';
		break;
}

include_once $ruta_elegida;


/*

# HTID:18647242: DO NOT REMOVE OR MODIFY THIS LINE AND THE LINES BELOW
php_value display_errors 1
# DO NOT REMOVE OR MODIFY THIS LINE AND THE LINES ABOVE HTID:18647242:

RewriteCond %{HTTPS} !=on
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301,NE]
Header always set Content-Security-Policy "upgrade-insecure-requests;"
*/

?>