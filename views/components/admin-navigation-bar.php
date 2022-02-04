<?php
include_once 'app/models/ConnectionDB.inc.php';
include_once 'app/models/SettingsDB.inc.php';

Connection::open_connection();
$response = json_decode(SettingsDB::getGeneralInformation(Connection::get_connection()));
Connection::close_connection();

$ROUTE_WP = ''; $ROUTE_FB = '';
if (!$response->error && $response->data) {
    $data = $response->data;
    $ROUTE_WP = ($data->wp_number) ? 'https://api.whatsapp.com/send/?phone='.$data->wp_number.'&text=Hola, me gustaría obtener más información acerca de &app_absent=0' : '';
    $ROUTE_FB = $data->fb_link;
}
?>

<nav class="navbar navbar-expand-sm fixed-top navbar-dark shadow-down">
    <div class="container-fluid main-wrap-content">
        <a class="navbar-brand" href="<?php echo ROUTE_HOME; ?>" target="_blanck">Ver sitio</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo ROUTE_SETTINGS; ?>">Ajustes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo ROUTE_PRODUCTS; ?>">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo ROUTE_SALES; ?>">Ventas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo ROUTE_LOGOUT; ?>">Salir</a>
                </li>
            </ul>
            <div class="d-flex">
                <div class="wrap-social-item bg-white">
                    <a class="color-primary" href="<?php echo $ROUTE_WP; ?>" target="_blank"><i
                            class="icon-whatsapp"></i></a>
                </div>
                <div class="wrap-social-item bg-white">
                    <a class="color-primary" href="<?php echo $ROUTE_FB; ?>" target="_blank"><i
                            class="icon-facebook"></i></a>
                </div>
            </div>
        </div>
    </div>
</nav>