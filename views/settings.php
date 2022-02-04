<?php
include_once 'app/cross/SessionManager.inc.php';
include_once 'app/cross/Redirect.inc.php';
if (!SessionManager::isset_sesion()) {Redirect::redirectTo(ROUTE_LOGIN);}

include_once 'layouts/html-opening.php';
include_once 'views/components/admin-navigation-bar.php';
?>


<section class="bg-white-variation min-height-90vh py-3">
    <div class="wrap-container pt-2 pb-5">
        <div class="bg-white p-4 rounded">
            <h1 class="mb-4 content-subtitle color-primary font-bold">Ajustes del sitio</h1>
            <div class="pb-5">
                <h5>Actualizar datos de acceso</h5>
                <div class="col-lg-6 col-md-8 col-sm-11">
                    <form autocomplete="off" method="POST" id="form-data-credentials">
                        <div class="row">
                            <div class="col-sm-6 p-1">
                                <div class="form-group mb-3">
                                    <label for="form-data-credentials_username">Nuevo nombre de usuario</label>
                                    <input type="text" class="form-control" name="username"
                                        id="form-data-credentials_username" placeholder="Ingresa nombre de usuario">
                                    <small id="emailHelp" class="form-text text-muted">Requerido</small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exampleInputPassword1">Contraseña actual</label>
                                    <input type="password" class="form-control" name="current_pass"
                                        id="exampleInputPassword1" placeholder="Ingresa tu contraseña">
                                    <small id="emailHelp" class="form-text text-muted">Requerido</small>
                                </div>
                            </div>
                            <div class="col-sm-6 p-1">
                                <div class="form-group mb-3">
                                    <label for="exampleInputPassword1">Nueva contraseña</label>
                                    <input type="password" class="form-control" name="pass_1" id="exampleInputPassword1"
                                        placeholder="Ingresa tu contraseña">
                                    <small id="emailHelp" class="form-text text-muted">Requerido</small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="exampleInputPassword1">Nueva contraseña</label>
                                    <input type="password" class="form-control" name="pass_2" id="exampleInputPassword1"
                                        placeholder="Ingresa tu contraseña">
                                    <small id="emailHelp" class="form-text text-muted">Requerido</small>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn bg-primary-color">Actualizar datos</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="py-5">
                <h5>Actualizar contenido</h5>
                <div class="col-lg-6 col-md-8 col-sm-11">
                    <form autocomplete="off" method="POST" id="form-data-content">
                        <div class="form-group mb-3">
                            <label for="form-data-content_mission">Edita la misión</label>
                            <textarea class="form-control" name="mission" id="form-data-content_mission"
                                rows="4"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="form-data-content_vision">Edita la visión</label>
                            <textarea class="form-control" name="vision" id="form-data-content_vision"
                                rows="4"></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn bg-primary-color">Actualizar contenido</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="pt-5">
                <h5>Actualizar link de redes sociales</h5>
                <div class="col-lg-6 col-md-8 col-sm-11">
                    <form autocomplete="off" method="POST" id="form-data-links">
                        <div class="row mb-3">
                            <div class="col-8 p-1">
                                <input type="text" class="form-control" placeholder="Facebook link" name="fb_link"
                                    id="form-data-links_fb">
                            </div>
                            <div class="col-4 p-1">
                                <input type="number" min="0" class="form-control" placeholder="Whatsapp number"
                                    name="wp_number" id="form-data-links_wp">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn bg-primary-color">Actualizar enlaces</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<?php
$files_js_routes[] = (ROUTE_DYNAMIC_JS."settings.js");

include_once 'views/components/footer.php';
include_once 'layouts/html-closing.php';
?>