<?php
include_once 'app/models/ConnectionDB.inc.php';
include_once 'app/models/ProductDB.inc.php';

Connection::open_connection();
$ninios = json_decode(ProductDB::getProductsByCategory(Connection::get_connection(), 'categoria_0001', 20));
$damas = json_decode(ProductDB::getProductsByCategory(Connection::get_connection(), 'categoria_0002', 20));
$caballeros = json_decode(ProductDB::getProductsByCategory(Connection::get_connection(), 'categoria_0003', 20));
$destacados = json_decode(ProductDB::getFeaturedProducts(Connection::get_connection(), 8));
$promociones = json_decode(ProductDB::getPromotedProducts(Connection::get_connection()));
Connection::close_connection();

include_once 'layouts/html-opening.php';
include_once 'views/components/navigation-bar.php';
?>
<header class="main-header-image" id="document_pesentation">
    <div class="main-header-image_text">
        <h1 class="main-header-image_title">Bienvenido a Regalos y Novedades Liz</h1>
        <h5 class="main-header-image_description">Sorpresas para cada ocasión</h5>
    </div>
</header>

<?php if (!$destacados->error && $destacados->data) { ?>
<section class="bg-white-variation" id="document_productos-destacados">
    <div class="wrap-container py-5">
        <h2 class="conten-title color-primary pb-2">Productos destacados</h2>
        <div class="wrapper-grid-products">
            <?php foreach ($destacados->data as $key => $destacado) {?>
            <div class="wrap-product activator-animation-transparency-end activator-animation-show-text">
                <div class="wrap-product_img">
                    <img class="wrap-product_img-250 animation-transparency-end"
                        src="<?php echo ROUTE_DYNAMIC_IMAGES.$destacado->img_url; ?>">
                    <div class="wrap-product-featured_icon"><i class="demo-icon icon-star"></i></div>
                    <div class="wrap-product-featured_description animation-show-text">
                        <h1 class="wrap-product_title font-bold"><?php echo $destacado->alias; ?></h1>
                    </div>
                </div>
            </div>
            <?php }?>
        </div>
    </div>
</section>
<?php } ?>


<?php if (!$promociones->error && $promociones->data) { ?>
<section id="document_promociones">
    <div class="wrap-container py-5">
        <h2 class="conten-title color-primary pb-2">Las mejores promociones</h2>
        <div class="wrap-uniform-grid">
            <?php foreach ($promociones->data as $key => $promocion) { ?>
            <div class="wrap-product activator-animation-transparency-end activator-animation-show-text">
                <div class="wrap-product_img">
                    <img class="wrap-product_img-250 animation-transparency-end"
                        src="<?php echo ROUTE_DYNAMIC_IMAGES.$promocion->img_url; ?>">
                    <div class="wrap-product-featured_icon"><i class="demo-icon icon-star"></i></div>
                    <div class="wrap-product-featured_description animation-show-text">
                        <h1 class="wrap-product_title font-bold"><?php echo $promocion->alias; ?></h1>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php }?>


<?php if (!$ninios->error && $ninios->data) { ?>
<section class="bg-white-variation" id="document_categoria-ninos">
    <div class="wrap-container py-5">
        <h2 class="conten-title color-primary pb-2">Lo mejor para niños</h2>
        <div class="slider-configuration">
            <?php foreach ($ninios->data as $key => $ninio) { ?>
            <div class="p-1">
                <div class="wrap-product">
                    <div class="wrap-product_img">
                        <img class="wrap-product_img-normal" src="<?php echo ROUTE_DYNAMIC_IMAGES.$ninio->img_url; ?>">
                    </div>
                    <div class="wrap-product_body">
                        <h1 class="wrap-product_title"><?php echo $ninio->alias; ?></h1>
                        <h5 class="wrap-product_description"><?php echo $ninio->brand; ?></h5>
                        <h6 class="wrap-product_discount">$ <?php echo number_format($ninio->price, 2, '.', ','); ?> -
                            <?php echo $ninio->discount_percentage; ?>%</h6>
                        <h4 class="wrap-product_price">$
                            <?php echo number_format($ninio->price - (($ninio->discount_percentage * $ninio->price)/100), 2, '.', ','); ?>
                        </h4>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php } ?>


<?php if (!$damas->error && $damas->data) { ?>
<section id="document_categoria-damas">
    <div class="wrap-container py-5">
        <h2 class="conten-title color-primary pb-2">Sección para damas</h2>
        <div class="slider-configuration">
            <?php foreach ($damas->data as $key => $dama) { ?>
            <div class="p-1">
                <div class="wrap-product">
                    <div class="wrap-product_img">
                        <img class="wrap-product_img-normal" src="<?php echo ROUTE_DYNAMIC_IMAGES.$dama->img_url; ?>">
                    </div>
                    <div class="wrap-product_body">
                        <h1 class="wrap-product_title"><?php echo $dama->alias; ?></h1>
                        <h5 class="wrap-product_description"><?php echo $dama->brand; ?></h5>
                        <h6 class="wrap-product_discount">$ <?php echo number_format($dama->price, 2, '.', ','); ?> -
                            <?php echo $dama->discount_percentage; ?>%</h6>
                        <h4 class="wrap-product_price">$
                            <?php echo number_format($dama->price - (($dama->discount_percentage * $dama->price)/100), 2, '.', ','); ?>
                        </h4>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php } ?>


<?php if (!$caballeros->error && $caballeros->data) { ?>
<section class="bg-white-variation" id="document_categoria-caballeros">
    <div class="wrap-container py-5">
        <h2 class="conten-title color-primary pb-2">Sección para caballeros</h2>
        <div class="slider-configuration">
            <?php foreach ($caballeros->data as $key => $caballero) { ?>
            <div class="p-1">
                <div class="wrap-product">
                    <div class="wrap-product_img">
                        <img class="wrap-product_img-normal"
                            src="<?php echo ROUTE_DYNAMIC_IMAGES.$caballero->img_url; ?>">
                    </div>
                    <div class="wrap-product_body">
                        <h1 class="wrap-product_title"><?php echo $caballero->alias; ?></h1>
                        <h5 class="wrap-product_description"><?php echo $caballero->brand; ?></h5>
                        <h6 class="wrap-product_discount">$ <?php echo number_format($caballero->price, 2, '.', ','); ?>
                            -
                            <?php echo $caballero->discount_percentage; ?>%</h6>
                        <h4 class="wrap-product_price">$
                            <?php echo number_format($caballero->price - (($caballero->discount_percentage * $caballero->price)/100), 2, '.', ','); ?>
                        </h4>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php } ?>

<?php
$files_js_routes [] = (ROUTE_DYNAMIC_JS."home.js");

include_once 'views/components/footer.php';
include_once 'layouts/html-closing.php';
?>