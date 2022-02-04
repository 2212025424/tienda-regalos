alertify.set('notifier', 'position', 'top-right');

var currentPage = 0;
var limitElements = $('#limitElemnts').val();
var areMoreElemnts = true;
var formWithErrors = false;

getProducts(currentPage, limitElements)

$('#previous-page').click(function () {
    if (currentPage > 0) {
        currentPage = currentPage - 1;
        getProducts(currentPage, limitElements);
        $("#current-page").text('Página: ' + (currentPage + 1))
    } else {
        alertify.warning("No hay productos en la anterior página.")
    }
})

$('#next-page').click(function () {
    if (areMoreElemnts) {
        currentPage = currentPage + 1;
        getProducts(currentPage, limitElements);
        $("#current-page").text('Página: ' + (currentPage + 1))
    } else {
        alertify.warning("No hay productos en la siguiente página.")
    }
})

$('#limitElemnts').change(function () {
    limitElements = $('#limitElemnts').val();
    currentPage = 0;
    getProducts(currentPage, limitElements)
    $("#current-page").text('Página: ' + (currentPage + 1))
})

function getProducts(page, limit) {
    $.ajax({
        url: '/app/controllers/products.php',
        type: 'POST',
        data: { 'request': 'getProducts', 'page': page, 'limit': limit },
        success: function (response) {
            response = JSON.parse(response)
            if (!response.error) {
                data = response.data
                areMoreElemnts = (data.length < limitElements) ? false : true;
                Writer.printProducts(data);
            } else {
                alertify.error(response.message);
            }
        }, error: function () {
            alertify.error('Error al realizar las peticiones.');
        }
    })
}

function highlightProduct(reference) {
    $.ajax({
        url: '/app/controllers/products.php',
        type: 'POST',
        data: { 'request': 'highlightProduct', 'reference': reference },
        success: function (response) {
            response = JSON.parse(response)
            if (!response.error) {
                getProducts(currentPage, limitElements)
                alertify.success(response.message)
            } else {
                alertify.error(response.message);
            }
        }, error: function () {
            alertify.error('Error al realizar las peticiones.');
        }
    })
}

function removeProduct(reference, name) {
    alertify.confirm('Confirmación', `Seguro que deseas eliminar <b>${name}</b>`, function () {
        $.ajax({
            url: '/app/controllers/products.php',
            type: 'POST',
            data: { 'request': 'removeProduct', 'reference': reference },
            success: function (response) {
                response = JSON.parse(response)
                if (!response.error) {
                    getProducts(currentPage, limitElements)
                    alertify.success(response.message)
                } else {
                    alertify.error(response.message);
                }
            }, error: function () {
                alertify.error('Error al realizar las peticiones.');
            }
        })
    }, '');
}

$('#form-add-name').keyup(function () {
    let name = $(this).val().trim().replace(/\s+/g, ' ');
    if (name.length > 0) {
        $.ajax({
            url: '/app/controllers/products.php',
            type: 'POST',
            data: { 'request': 'issetNameProduct', 'alias': name },
            success: function (response) {
                response = JSON.parse(response)
                if (!response.error) {
                    if (response.data) {
                        formWithErrors = true;
                        alertify.error(response.message);
                    }
                } else {
                    alertify.error(response.message);
                }
            }, error: function () {
                alertify.error('Error al realizar las peticiones.');
            }
        })
    }
});

$('#form-add').submit(function (e) {
    e.preventDefault();

    let data = $(this).serializeArray();
    let formData = new FormData();
    
    data.forEach (element => formData.append(element.name, element.value))
    formData.append('image', $("#form-add-img")[0].files[0]);
    formData.append('request', 'addProduct');

    if (!formWithErrors) {
        $.ajax({
            url: '/app/controllers/products.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                response = JSON.parse(response)
                if (!response.error) {
                    $('#form-add')[0].reset();
                    $("#modal-add-product").modal("hide");
                    getProducts(currentPage, limitElements)
                    alertify.success(response.message);
                } else {
                    alertify.error(response.message);
                }
            }, error: function () {
                alertify.error('Error al realizar las peticiones.');
            }
        })
    }else {
        alertify.error('El nombre ya existe.');
    }
    
})

function getProductForUpdate (reference) {
    $.ajax({
        url: '/app/controllers/products.php',
        type: 'POST',
        data: { 'request': 'getProductForUpdate', 'reference': reference },
        success: function (response) {
            response = JSON.parse(response)
            if (!response.error) {
                Writer.showValuesForUpdate(response.data)
                $("#modal-apdate-product").modal("show");
            } else {
                alertify.error(response.message);
            }
        }, error: function () {
            alertify.error('Error al realizar las peticiones.');
        }
    })
}

function cleanDataFromUpdateForm () {
    $("#form-apdate")[0].reset();
    $(`#form-apdate-category > option`).removeAttr('selected');
}

$('#form-apdate').submit(function (e) {
    e.preventDefault();

    let data = $(this).serializeArray();
    let formData = new FormData();
    
    data.forEach (element => formData.append(element.name, element.value))
    formData.append('image', $("#form-apdate-img")[0].files[0]);
    formData.append('request', 'apdateProduct');

    $.ajax({
        url: '/app/controllers/products.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            console.log(response)
            response = JSON.parse(response)
            if (!response.error) {
                $('#form-apdate')[0].reset();
                $("#modal-apdate-product").modal("hide");
                getProducts(currentPage, limitElements)
                alertify.success(response.message);
            } else {
                alertify.error(response.message);
            }
        }, error: function () {
            alertify.error('Error al realizar las peticiones.');
        }
    })
})

class Writer {

    static printEmpty() {
        $("#table-products").append(`<tr><td class="align-middle single-line-text text-center" colspan="9">No hay más datos.</td></tr>`);
    }

    static addProductsList(data) {
        $("#table-products").append(`<tr>
        <td><img class="img-sm" src="${ROUTE_DYNAMIC_IMAGES + data.img_url}"></td>
        <td class="align-middle single-line-text">${data.category_name}</td>
        <td class="align-middle single-line-text">${data.alias}</td>
        <td class="align-middle single-line-text">${data.brand}</td>
        <td class="align-middle single-line-text">$${data.cost}</td>
        <td class="align-middle single-line-text">$${data.price}</td>
        <td class="align-middle single-line-text text-center">${(data.offer > 0) ? data.discount_percentage+" %" : 'no aplica'}</td>
        <td class="align-middle single-line-text">${data.quantity} ejemplares</td>
        <td class="align-middle">
            <button onclick="highlightProduct('${data.reference}')" type="button" class="btn btn-sm btn-outline-warning shadow-none" ${(data.featured) ? 'disabled' : ''}>${(data.featured) ? 'Destacado' : 'Destacar'}</button>
        </td>
        <td class="align-middle">
            <button type="button" class="btn btn-sm btn-outline-success shadow-none" onclick="getProductForUpdate('${data.reference}')">Editar</button>
        </td>
        <td class="align-middle">
            <button onclick="removeProduct('${data.reference}', '${data.alias}')" type="button" class="btn btn-sm btn-outline-danger shadow-none">Eliminar</button>
        </td>
    </tr>`);
    }

    static printProducts(products) {
        $("#table-products").empty();
        if (products.length > 0) {
            products.forEach(product => {
                Writer.addProductsList(product)
            });
        } else {
            Writer.printEmpty();
        }
    }

    static showValuesForUpdate (data) {
        $("#form-apdate-current-img").attr("src", ROUTE_DYNAMIC_IMAGES + data.img_url);
        $("#form-apdate-reference").val(data.reference);
        $("#form-apdate-alias").val(data.alias);
        $("#form-apdate-brand").val(data.brand);
        $(`#form-apdate-category > option[value*="${data.category_ref}"]`).attr('selected', 'selected');
        $("#form-apdate-information").val(data.information);
        $("#form-apdate-cost").val(data.cost);
        $("#form-apdate-price").val(data.price);
        $("#form-apdate-discount").val(data.discount_percentage);
        $("#form-apdate-quantity").val(data.quantity);
        $("#form-apdate-min-stock").val(data.min_stock);
    }
}
