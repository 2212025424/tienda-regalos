$(document).ready (function () {

    alertify.set('notifier','position', 'top-right');

    getContentData();

    function getContentData () {
        $.ajax ({
            url: '/app/controllers/settings.php',
            type: 'POST',
            data: {'request': 'getContentData'},
            success: function (response) {
                response = JSON.parse(response);
                if (!response.error) {
                    data = response.data
                    $('#form-data-content_mission').val(data.mission)
                    $('#form-data-content_vision').val(data.vision)
                    $('#form-data-links_fb').val(data.fb_link)
                    $('#form-data-links_wp').val(data.wp_number)
                }else{ 
                    alertify.error(response.message);
                }
            }, error: function() {
                alertify.error('Error al realizar las peticiones.');
            }
        })
    }

    $('#form-data-links').submit(function(e) {
        e.preventDefault();
        
        let data = $(this).serializeArray();
        data.push({name: 'request', value: 'updateDataLinks'});
        
        $.ajax ({
            url: '/app/controllers/settings.php',
            type: 'POST',
            data: data,
            success: function (response) {
                response = JSON.parse(response)
                if (!response.error) {
                    alertify.success(response.message);
                }else {
                    alertify.error(response.message);
                }
            }, error: function() {
                alertify.error('Error al realizar las peticiones.');
            }
        })
    })

    $('#form-data-content').submit(function(e) {
        e.preventDefault();
        
        var data = $(this).serializeArray();
        data.push({name: 'request', value: 'updateDataContent'});
        
        $.ajax ({
            url: '/app/controllers/settings.php',
            type: 'POST',
            data: data,
            success: function (response) {
                response = JSON.parse(response)
                if (!response.error) {
                    alertify.success(response.message);
                }else {
                    alertify.error(response.message);
                }
            }, error: function() {
                alertify.error('Error al realizar las peticiones.');
            }
        })
    })

    $('#form-data-credentials').submit(function(e) {
        e.preventDefault();
        
        var data = $(this).serializeArray();
        data.push({name: 'request', value: 'updateDataCredentials'});
        
        $.ajax ({
            url: '/app/controllers/settings.php',
            type: 'POST',
            data: data,
            success: function (response) {
                response = JSON.parse(response)
                if (!response.error) {
                    alertify.success(response.message);
                    $('#form-data-credentials')[0].reset();
                }else {
                    alertify.error(response.message);
                }
            }, error: function() {
                alertify.error('Error al realizar las peticiones.');
            }
        })
    })




})