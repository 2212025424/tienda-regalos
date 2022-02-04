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
            <div class="mb-4 d-flex justify-content-between">
                <h1 class="content-subtitle color-primary font-bold">Ventas realizadas</h1>
                <div>
                    <button type="button" class="btn bg-primary-color color-white shadow-none" data-toggle="modal"
                        data-target="#modal-add-sale">Agregar venta</button>
                </div>
            </div>

            <div class="modal fade" id="modal-add-sale" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Realiza tus ventas</h5>
                        </div>
                        <div class="modal-body">

                            <form id="form-update-item-sales">
                                <div><input name="reference" id="form-update-item-sales-reference" type="hidden"></div>
                                <div class="grid-two-cols-1fr-150px">
                                    <div><input name="alias" id="form-update-item-sales-alias" type="text"
                                            class="form-control shadow-none"
                                            placeholder="Selecciona un producto para editar" readonly>
                                    </div>
                                    <div><input name="quantity" id="form-update-item-sales-quantity" type="number"
                                            class="form-control shadow-none" placeholder="Cantidad">
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-sm btn-success shadow-none mt-2">Actualizar
                                        cantidad</button>
                                </div>
                            </form>

                            <input id="input-to-search-product" type="text" class="form-control shadow-none"
                                placeholder="Buscar producto">

                            <div class="mt-3">
                                <div>
                                    <table class="table table-sm table-bordered">
                                        <thead class="bg-white-variation rounded-top">
                                            <tr>
                                                <th class="text-center" scope="col">Nombre</th>
                                                <th class="text-center" scope="col">Stock actual</th>
                                                <th class="text-center" scope="col">Costo</th>
                                                <th class="text-center" scope="col">Precio</th>
                                                <th class="text-center" scope="col" colspan="2">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-products"></tbody>
                                    </table>
                                </div>
                                <div>
                                    <table class="table table-sm table-bordered">
                                        <thead class="bg-white-variation rounded-top">
                                            <tr>
                                                <th class="text-center" scope="col">Nombre</th>
                                                <th class="text-center" scope="col">solicitados</th>
                                                <th class="text-center" scope="col">Subtotal</th>
                                                <th class="text-center" scope="col">Total</th>
                                                <th class="text-center" scope="col" colspan="2">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-temporal-sales"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="action-close-sale" type="button" class="btn btn-success shadow-none">Cerrar
                                venta</button>
                            <button type="button" class="btn btn-secondary shadow-none" data-dismiss="modal">Cerrar
                                ventana</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="madal-see-sale-details" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Detalle de venta</h5>
                        </div>
                        <div class="modal-body">
                            <table class="table table-sm table-bordered">
                                <thead class="bg-white-variation rounded-top">
                                    <tr>
                                        <th class="text-center" scope="col">Producto</th>
                                        <th class="text-center" scope="col">Cantidad</th>
                                        <th class="text-center" scope="col">Subtotal</th>
                                        <th class="text-center" scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="table-see-sale-details"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary shadow-none" data-dismiss="modal">Cerrar
                                ventana</button>
                        </div>
                    </div>
                </div>
            </div>

            <nav class="mb-0 pb-0">
                <ul class="pagination justify-content-center">
                    <li class="page-item">
                        <button class="page-link shadow-none" id="previous-page">Anterior</button>
                    </li>
                    <li class="page-item pl-5px pr-5px">
                        <div class="form-group">
                            <select class="form-control shadow-none" id="limitElemnts">
                                <option value="5">Mostrar 5</option>
                                <option value="10">Mostrar 10</option>
                                <option value="20">Mostrar 20</option>
                                <option value="50">Mostrar 50</option>
                            </select>
                        </div>
                    </li>
                    <li class="page-item">
                        <button class="page-link shadow-none" id="next-page">Siguiente</button>
                    </li>
                </ul>
            </nav>

            <div class="mt-0 pt-0 over-flow-x-auto scrollbar-x scrollbar-style">
                <table class="table">
                    <thead class="bg-white-variation rounded-top">
                        <tr>
                            <th scope="col">Productos</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">Total</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Hora</th>
                            <th class="text-center" scope="col" colspan="3">Opciones</th>
                        </tr>
                    </thead>
                    <tbody id="table-sales"></tbody>
                </table>
            </div>

            <nav class="mb-0 pb-0 mt-3">
                <ul class="pagination justify-content-center">
                    <li class="page-item bg-white-variation"><a class="page-link" id="current-page">Página 1</a></li>
                </ul>
            </nav>

        </div>
    </div>
</section>


<?php
$files_js_routes[] = (ROUTE_DYNAMIC_JS."sales.js");
include_once 'views/components/footer.php';
include_once 'layouts/html-closing.php';
?>