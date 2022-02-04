<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo ROUTE_CSS; ?>bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo ROUTE_TOOLS; ?>fontello/css/fontello.css" rel="stylesheet">
    <link href="<?php echo ROUTE_CSS; ?>slick.css" rel="stylesheet">
    <link href="<?php echo ROUTE_CSS; ?>slick-theme.css" rel="stylesheet">
    <link href="<?php echo ROUTE_CSS; ?>alertify.min.css" rel="stylesheet">
    <link href="<?php echo ROUTE_CSS; ?>alertify-theme-default.min.css" rel="stylesheet">
    <link href="<?php echo ROUTE_CSS; ?>main-styles.css" rel="stylesheet">
    <link href="<?php echo ROUTE_CSS; ?>styles-modifier.css" rel="stylesheet">
    <link href="<?php echo ROUTE_CSS; ?>stylized-components.css" rel="stylesheet">
    <link rel="shortcut icon" href="<?php echo ROUTE_STATIC_IMAGES; ?>icon.svg">
    <?php 
		$titulo = (isset($titulo_documento) ? $titulo_documento : 'Regalos y Novedades Liz | Tienda de regalos en Puebla | Sorpresas para cada ocación')
		?>
    <title><?php echo $titulo ?></title>
</head>

<body>
    <div class="main-wrap-content">