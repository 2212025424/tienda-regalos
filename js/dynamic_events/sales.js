alertify.set('notifier', 'position', 'top-right');

var currentPage = 0;
var limitElements = $('#limitElemnts').val();
var limitProductsForSale = 5;
var areMoreElemnts = true;
var formWithErrors = false;

getSales(currentPage, limitElements)
getProductsForSale(limitProductsForSale);
getTemporalSales();

$('#previous-page').click(function () {
    if (currentPage > 0) {
        currentPage = currentPage - 1;
        getSales(currentPage, limitElements);
        $("#current-page").text('Página: ' + (currentPage + 1))
    } else {
        alertify.warning("No hay productos en la anterior página.")
    }
})

$('#next-page').click(function () {
    if (areMoreElemnts) {
        currentPage = currentPage + 1;
        getSales(currentPage, limitElements);
        $("#current-page").text('Página: ' + (currentPage + 1))
    } else {
        alertify.warning("No hay productos en la siguiente página.")
    }
})

$('#limitElemnts').change(function () {
    limitElements = $('#limitElemnts').val();
    currentPage = 0;
    getSales(currentPage, limitElements)
    $("#current-page").text('Página: ' + (currentPage + 1))
})

function getFormatHour (hour) {
    var hours = hour.split(":")[0];
    var minutes = hour.split(":")[1];
    var ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12;
    minutes = minutes < 10 ? '0'+minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
}

function getSales(page, limit) {
    $.ajax({
        url: '/app/controllers/sales.php',
        type: 'POST',
        data: { 'request': 'getSales', 'page': page, 'limit': limit },
        success: function (response) {
            response = JSON.parse(response)
            if (!response.error) {
                data = response.data
                areMoreElemnts = (data.length < limitElements) ? false : true;
                Writer.printSales(data);
            } else {
                alertify.error(response.message);
            }
        }, error: function () {
            alertify.error('Error al realizar las peticiones.');
        }
    })
}

function getProductsForSale (limit) {
    $.ajax({
        url: '/app/controllers/sales.php',
        type: 'POST',
        data: { 'request': 'getProductsForSale', 'limit': limit },
        success: function (response) {
            response = JSON.parse(response)
            if (!response.error) {
                data = response.data
                Writer.printProductsForSales(data);
            } else {
                alertify.error(response.message);
            }
        }, error: function () {
            alertify.error('Error al realizar las peticiones.');
        }
    })
}

$("#input-to-search-product").keyup (function () {
    let name = $(this).val().trim().replace(/\s+/g, ' ');
    if (name.length > 0) {
        $.ajax({
            url: '/app/controllers/sales.php',
            type: 'POST',
            data: { 'request': 'searchProductsForSale', 'alias': name , 'limit':5},
            success: function (response) {
                response = JSON.parse(response)
                if (!response.error) {
                    data = response.data
                    Writer.printProductsForSales(data);
                } else {
                    alertify.error(response.message);
                }
            }, error: function () {
                alertify.error('Error al realizar las peticiones.');
            }
        })
    }else {
        getProductsForSale(limitProductsForSale)
    }
})

function addTemporalSale (reference) {
    $.ajax({
        url: '/app/controllers/sales.php',
        type: 'POST',
        data: { 'request': 'addTemporalSale', 'reference': reference},
        success: function (response) {
            response = JSON.parse(response)
            if (!response.error) {
                getTemporalSales();
                getProductsForSale(limitProductsForSale)
            }else {
                alertify.error(response.message);
            }
        }, error: function () {
            alertify.error('Error al realizar las peticiones.');
        }
    })
}

function getTemporalSales () {
    $.ajax({
        url: '/app/controllers/sales.php',
        type: 'POST',
        data: { 'request': 'getTemporalSales'},
        success: function (response) {
            response = JSON.parse(response)
            if (!response.error) {
                data = response.data
                Writer.printTemporalSales(data);
            } else {
                alertify.error(response.message);
            }
        }, error: function () {
            alertify.error('Error al realizar las peticiones.');
        }
    })
}

function removeFromTemporalSale (reference) {
    Writer.hideFormUpdateItemSales();
    $.ajax({
        url: '/app/controllers/sales.php',
        type: 'POST',
        data: { 'request': 'removeFromTemporalSale', 'reference' : reference},
        success: function (response) {
            response = JSON.parse(response)
            if (!response.error) {
                data = response.data
                getProductsForSale(limitProductsForSale);
                getTemporalSales();
            } else {
                alertify.error(response.message);
            }
        }, error: function () {
            alertify.error('Error al realizar las peticiones.');
        }
    })
}

$('#form-update-item-sales').submit(function (e) {
    e.preventDefault();

    let data = $(this).serializeArray();
    data.push({name: 'request', value: 'updateTemporalSale'});

    if (!formWithErrors) {
        $.ajax({
            url: '/app/controllers/sales.php',
            type: 'POST',
            data: data,
            success: function (response) {
                response = JSON.parse(response)
                if (!response.error) {
                    $('#form-update-item-sales')[0].reset();
                    getProductsForSale(limitProductsForSale)
                    getTemporalSales();
                    Writer.hideFormUpdateItemSales();
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

function closeTemporalSale () {
    $.ajax({
        url: '/app/controllers/sales.php',
        type: 'POST',
        data: {'request': 'closeTemporalSale'},
        success: function (response) {
            response = JSON.parse(response)
            if (!response.error) {
                getSales(currentPage, limitElements)
                getProductsForSale(limitProductsForSale);
                getTemporalSales();
                alertify.success(response.message);
            }
        }, error: function () {
            alertify.error('Error al realizar las peticiones.');
        }
    })
}

$("#action-close-sale").click (function () {
    $.ajax({
        url: '/app/controllers/sales.php',
        type: 'POST',
        data: {'request': 'getTotalTemporalSale'},
        success: function (response) {
            response = JSON.parse(response)
            if (!response.error && response.data > 0) {
                alertify.confirm('Confirmación', `<h3>$ ${response.data}</h3> Seguro que deseas cerrar la venta.`, function () {
                    closeTemporalSale();
                }, '');
                
            }
        }, error: function () {
            alertify.error('Error al realizar las peticiones.');
        }
    })
})

function seeSaleDetails (reference, total) {
    $.ajax({
        url: '/app/controllers/sales.php',
        type: 'POST',
        data: {'request': 'getAllSaleDetails', 'reference' : reference},
        success: function (response) {
            response = JSON.parse(response)
            if (!response.error) {
                Writer.showSaleDetails(response.data, total);
            }
        }, error: function () {
            alertify.error('Error al realizar las peticiones.');
        }
    })
}

function cancelAllSale (reference, date, hour) {
    alertify.confirm('Confirmación', `<center>Seguro que deseas <b>cancelar</b> la venta realizada el <br><h4><b>${date}</b> a las <b>${hour}</b></h4>.</center>`, function () {
        $.ajax({
            url: '/app/controllers/sales.php',
            type: 'POST',
            data: {'request': 'cancelAllSale', 'reference' : reference},
            success: function (response) {
                response = JSON.parse(response)
                if (!response.error) {
                    getSales(currentPage, limitElements)
                    alertify.success(response.message);
                }else {
                    alertify.error(response.message);
                }
            }, error: function () {
                alertify.error('Error al realizar las peticiones.');
            }
        })
    }, '');
}

class Writer {

    static printEmpty() {
        $("#table-sales").append(`<tr><td class="align-middle single-line-text text-center" colspan="9">No hay más datos.</td></tr>`);
    }

    static printEmptyProductsForSales () {
        $("#table-products").append(`<tr><td class="align-middle single-line-text text-center" colspan="5">No hay coincidencias.</td></tr>`);
    }

    static printEmptyTemporalSales () {
        $("#table-temporal-sales").append(`<tr><td class="align-middle single-line-text text-center" colspan="5">No hay productos asignados.</td></tr>`);
    }

    static showFormUpdateItemSales () {
        $("#form-update-item-sales").removeClass('d-none');
        $("#input-to-search-product").addClass('d-none');
    }

    static hideFormUpdateItemSales () {
        $("#form-update-item-sales").addClass('d-none');
        $("#input-to-search-product").removeClass('d-none');
    }

    static addSaleList (data) {
        $("#table-sales").append(`
        <tr>
            <td class="single-line-text">${(data.consumed) ? data.consumed + ' en detalle' : 'Sin detalles.'}</td>
            <td class="single-line-text">$ ${(data.subtotal) ? data.subtotal : '0'}</td>
            <td class="single-line-text">$ ${(data.total) ? data.total : '0'}</td>
            <td class="single-line-text">${data.date_day}</td>
            <td class="single-line-text">${getFormatHour(data.hour)}</td>
            <td>
                <button onClick="seeSaleDetails('${data.reference}', '${data.total}')" type="button" class="btn btn-sm btn-outline-success shadow-none">Detalles</button>
            </td>
            <td>
                <button onClick="cancelAllSale('${data.reference}', '${data.date_day}', '${getFormatHour(data.hour)}')" type="button" class="btn btn-sm btn-outline-danger shadow-none">Cancelar</button>
            </td>
        </tr>
        `);
    }

    static addProductList (data) {
        $("#table-products").append(`
        <tr>
            <td class="align-middle">${data.alias}</td>
            <td class="align-middle text-center">
                ${data.quantity} existentes
            </td>
            <td class="align-middle text-center">
                $ ${data.cost} 
            </td>
            <td class="align-middle text-center">
                $ ${data.price}
            </td>
            <td class="align-middle text-center">
                <button type="button"
                    class="btn btn-sm btn-primary shadow-none" onclick="addTemporalSale('${data.reference}')">Agregar</button>
            </td>
        </tr>
        `)
    }
    
    static addTemporalSaleList (data) {
        let limit = parseInt(data.quantity, 10) + parseInt(data.required_q, 10);
        $("#table-temporal-sales").append(`
        <tr>
            <td class="align-middle">${data.alias}</td>
            <td class="align-middle">Cant. ${data.required_q}</td>
            <td class="align-middle">$ ${data.subtotal}</td>
            <td class="align-middle">$ ${data.total}</td>
            <td class="align-middle text-center">
                <button onclick="Writer.addOnFormForEdit('${data.reference}', '${data.alias}', '${data.required_q}', ${limit})" type="button" class="btn btn-sm btn-warning shadow-none">Editar</button>
            </td>
            <td class="align-middle text-center">
                <button onclick="removeFromTemporalSale('${data.reference}')" type="button" class="btn btn-sm btn-danger shadow-none">Eliminar</button>
            </td>
        </tr>
        `);
    }

    static printSales(sales) {
        $("#table-sales").empty();
        if (sales.length > 0) {
            sales.forEach(sale => {
                Writer.addSaleList(sale)
            });
        } else {
            Writer.printEmpty();
        }
    }

    static printProductsForSales (products) {
        $("#table-products").empty();
        if (products.length > 0) {
            products.forEach(sale => {
                Writer.addProductList(sale)
            });
        } else {
            Writer.printEmptyProductsForSales();
        }
    }

    static printTemporalSales (sales) {
        $("#table-temporal-sales").empty();
        if (sales.length > 0) {
            sales.forEach(sale => {
                Writer.addTemporalSaleList(sale)
            });
        } else {
            Writer.printEmptyTemporalSales();
        }
    }

    static addOnFormForEdit (reference, name, required, limit) {
        Writer.showFormUpdateItemSales();
        $("#form-update-item-sales-reference").val(reference);
        $("#form-update-item-sales-alias").val(name);
        $("#form-update-item-sales-quantity").val(required);
        $("#form-update-item-sales-quantity").attr("max", limit);
    }
    
    static showSaleDetails (data, total) {
        $("#madal-see-sale-details").modal('show')
        $("#table-see-sale-details").empty()
        if (data.length > 0) {
            data.forEach(e => {
                $("#table-see-sale-details").append(
                    `<tr>
                    <td>${e.alias}</td>
                    <td>${e.quantity}</td>
                    <td>${e.subtotal}</td>
                    <td>${e.total}</td>
                </tr>`
                )
            });
            $("#table-see-sale-details").append(`<div><h4>Total: ${total}</h4></div>`)
        }
    }
}
Writer.hideFormUpdateItemSales();