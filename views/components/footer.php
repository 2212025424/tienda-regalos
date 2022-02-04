<?php
include_once 'app/models/ConnectionDB.inc.php';
include_once 'app/models/SettingsDB.inc.php';

Connection::open_connection();
$response = json_decode(SettingsDB::getGeneralInformation(Connection::get_connection()));
Connection::close_connection();

if (!$response->error && $response->data) {
    $data = $response->data;
    $ROUTE_WP = ($data->wp_number) ? 'https://api.whatsapp.com/send/?phone='.$data->wp_number.'&text=Hola, me gustaría obtener más información acerca de &app_absent=0' : '';
    $ROUTE_FB = ($data->fb_link) ? $data->fb_link : ''; ?>

<footer class="bg-black-color border-top-main">
    <div class="wrap-container py-5">
        <div class="row">
            <div class="col-md-6 mt-3 px-2">
                <h2 class="content-subtitle color-white">Misión</h2>
                <h3 class="content-description color-gray"><?php echo $data->mission; ?></h3>
            </div>
            <div class="col-md-6 mt-3 px-2">
                <h2 class="content-subtitle color-white">Visión</h2>
                <h3 class="content-description color-gray"><?php echo $data->vision; ?></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mt-3 px-2">
                <h3 class="content-subtitle color-white">Nos ubicamos en:</h3>
                <h6 class="content-description color-gray">AV. 5 de Mayo N. 308 CENTRO CP.74160 Huejotzingo, Puebla,
                    México.</h6>
            </div>
            <div class="col-md-6 d-flex align-items-center mt-3 px-2">
                <div class="d-flex align-items-center p-2">
                    <div class="wrap-social-item bg-white-hover">
                        <a href="<?php echo $ROUTE_WP; ?>" target="_blank"><i class="color-blue icon-whatsapp"></i></a>
                    </div>
                    <h5 class="content-description color-gray">WhatsApp</h5>
                </div>
                <div class="d-flex align-items-center">
                    <div class="wrap-social-item bg-white-hover">
                        <a href="<?php echo $ROUTE_FB; ?>" target="_blank"><i class="color-blue icon-facebook"></i></a>
                    </div>
                    <h5 class="content-description color-gray">Facebook</h5>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php } ?>