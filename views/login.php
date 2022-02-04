<?php
include_once 'app/models/ConnectionDB.inc.php';
include_once 'app/models/SettingsDB.inc.php';
include_once 'app/cross/SessionManager.inc.php';
include_once 'app/cross/Redirect.inc.php';

if (SessionManager::isset_sesion()) {
    Redirect::redirectTo(ROUTE_SALES);
}

if (isset($_POST['login']) && isset($_POST['username']) && isset($_POST['pass'])) {
    Connection::open_connection();
    $response = json_decode(SettingsDB::getCredentials(Connection::get_connection()));
    Connection::close_connection();

    if (!$response->error) {
        if ($_POST['username'] == $response->data->username && password_verify($_POST['pass'], $response->data->pass)) {
            SessionManager::start_sesion($_POST['username']);
            Redirect::redirectTo(ROUTE_SALES);
        }
    }
}

include_once 'layouts/html-opening.php';
include_once 'views/components/navigation-bar.php';
?>

<section class="container-fluid bg-white-variation min-height-90vh py-5">
    <div class="row d-flex justify-content-center">
        <div class="col-sm-11 col-md-6 col-lg-5">
            <h2 class="conten-title color-primary pb-2">Iniciar sesión</h2>
            <div>
                <form autocomplete="off" action="<?php echo ROUTE_LOGIN; ?>" method="POST">
                    <div class="form-group mb-4">
                        <label for="user-name">Nombre de usuario</label>
                        <input type="text" class="form-control" name="username" placeholder="Ingresa nombre de usuario">
                        <small id="emailHelp" class="form-text text-muted">Requerido</small>
                    </div>
                    <div class="form-group mb-4">
                        <label for="exampleInputPassword1">Contraseña</label>
                        <input type="password" name="pass" class="form-control" id="exampleInputPassword1"
                            placeholder="Ingresa tu contraseña">
                        <small id="emailHelp" class="form-text text-muted">Requerido</small>
                    </div>
                    <button type="submit" name="login" class="btn bg-primary-color">Ingresar</button>
                </form>
                <h6><?php echo json_decode($response)->message; ?></h6>
            </div>
        </div>
    </div>
</section>


<?php
include_once 'views/components/footer.php';
include_once 'layouts/html-closing.php';
?>