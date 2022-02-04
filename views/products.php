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
                <h1 class="content-subtitle color-primary font-bold">Lista de productos</h1>
                <div>
                    <button type="button" class="btn bg-primary-color color-white shadow-none" data-toggle="modal"
                        data-target="#modal-add-product">Agregar producto</button>
                </div>
            </div>

            <div class="modal fade" id="modal-add-product" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar producto</h5>
                        </div>
                        <div class="modal-body">
                            <form id="form-add" autocomplete="off" enctype="multipart/form-data">
                                <div class="mb-2">
                                    <input type="text" name="alias" class="form-control shadow-none" id="form-add-name"
                                        placeholder="Ingresa el nombre" required>
                                </div>
                                <div class="grid-two-cols mb-2">
                                    <div>
                                        <input type="text" name="brand" class="form-control shadow-none"
                                            id="form-add-brand" placeholder="Marca" required>
                                    </div>
                                    <div>
                                        <select name="category" class="form-control shadow-none" id="form-add-category"
                                            required>
                                            <option value="" selected>Selecciona categoría</option>
                                            <option value="categoria_0001/ninios">Niños</option>
                                            <option value="categoria_0002/damas">Damas</option>
                                            <option value="categoria_0003/caballeros">Caballeros</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <textarea name="information" class="form-control shadow-none"
                                        id="form-add-information" rows="2" placeholder="Información o descripción"
                                        required></textarea>
                                </div>
                                <div class="grid-tree-cols">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">$</div>
                                        </div>
                                        <input type="number" name="cost" min="0" step="0.01"
                                            class="form-control shadow-none" id="form-add-cost" placeholder="Costo real"
                                            required>
                                    </div>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">$</div>
                                        </div>
                                        <input type="number" name="price" min="0" step="0.01"
                                            class="form-control shadow-none" id="form-add-price"
                                            placeholder="Precio venta" required>
                                    </div>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">-%</div>
                                        </div>
                                        <input type="number" name="discount" value="0" min="0" step="1"
                                            class="form-control shadow-none" id="form-add-cost" placeholder="Descuento"
                                            required>
                                    </div>
                                </div>
                                <div class="grid-two-cols">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">C</div>
                                        </div>
                                        <input type="number" name="quantity" min="0" step="1"
                                            class="form-control shadow-none" id="form-add-quantity"
                                            placeholder="Cantidad actual" required>
                                    </div>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">C</div>
                                        </div>
                                        <input type="number" name="min-stock" min="0" step="1"
                                            class="form-control shadow-none" id="form-add-min-stock"
                                            placeholder="Stock mínimo" required>
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <input type="file" name="image" accept="image/*" class="form-control-file"
                                        id="form-add-img" required>
                                </div>
                                <div class="text-center">
                                    <button type="send" class="btn btn-primary shadow-none">Agregar producto</button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary shadow-none"
                                data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="modal-apdate-product" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabelA">Actualizar producto</h5>
                        </div>
                        <div class="modal-body">
                            <form id="form-apdate" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="reference" id="form-apdate-reference"
                                    class="form-control shadow-none" placeholder="Ingresa el nombre" required>
                                <div class="mb-2">
                                    <input type="text" name="alias" class="form-control shadow-none"
                                        id="form-apdate-alias" placeholder="Ingresa el nombre" required>
                                </div>
                                <div class="grid-two-cols mb-2">
                                    <div>
                                        <input type="text" name="brand" class="form-control shadow-none"
                                            id="form-apdate-brand" placeholder="Marca" required>
                                    </div>
                                    <div>
                                        <select name="category" class="form-control shadow-none"
                                            id="form-apdate-category" required>
                                            <option value="" selected>Selecciona categoría</option>
                                            <option value="categoria_0001/ninios">Niños</option>
                                            <option value="categoria_0002/damas">Damas</option>
                                            <option value="categoria_0003/caballeros">Caballeros</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <textarea name="information" class="form-control shadow-none"
                                        id="form-apdate-information" rows="2" placeholder="Información o descripción"
                                        required></textarea>
                                </div>
                                <div class="grid-tree-cols">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">$</div>
                                        </div>
                                        <input type="number" name="cost" min="0" step="0.01"
                                            class="form-control shadow-none" id="form-apdate-cost"
                                            placeholder="Costo real" required>
                                    </div>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">$</div>
                                        </div>
                                        <input type="number" name="price" min="0" step="0.01"
                                            class="form-control shadow-none" id="form-apdate-price"
                                            placeholder="Precio venta" required>
                                    </div>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">-%</div>
                                        </div>
                                        <input type="number" name="discount" value="0" min="0" step="1"
                                            class="form-control shadow-none" id="form-apdate-discount"
                                            placeholder="Descuento" required>
                                    </div>
                                </div>
                                <div class="grid-two-cols">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">C</div>
                                        </div>
                                        <input type="number" name="quantity" min="0" step="1"
                                            class="form-control shadow-none" id="form-apdate-quantity"
                                            placeholder="Cantidad actual" required>
                                    </div>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">C</div>
                                        </div>
                                        <input type="number" name="min-stock" min="0" step="1"
                                            class="form-control shadow-none" id="form-apdate-min-stock"
                                            placeholder="Stock mínimo" required>
                                    </div>
                                </div>
                                <div class="grid-two-cols-90px-1fr">
                                    <div><img id="form-apdate-current-img" src=""
                                            alt="Imagen actual del elemento a editar" class="img-sm"></div>
                                    <div>
                                        <p>Selecciona un archivo para cambiar imagen</p>
                                        <div class="form-group mb-2">
                                            <input type="file" name="image" accept="image/*" class="form-control-file"
                                                id="form-apdate-img">
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="send" class="btn btn-primary shadow-none">Actualizar
                                        producto</button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary shadow-none" data-dismiss="modal"
                                onclick="cleanDataFromUpdateForm()">Cerrar</button>
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
                            <th scope="col">Imagen</th>
                            <th scope="col">Categoría</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Costo</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Descuento</th>
                            <th scope="col">Stock</th>
                            <th class="text-center" scope="col" colspan="3">Opciones</th>
                        </tr>
                    </thead>
                    <tbody id="table-products"></tbody>
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

$files_js_routes[] = (ROUTE_DYNAMIC_JS."products.js");
$global_js_scripts[] = ("<script>var ROUTE_DYNAMIC_IMAGES = '".ROUTE_DYNAMIC_IMAGES."'</script>");
include_once 'views/components/footer.php';
include_once 'layouts/html-closing.php';
?>