// Laravel CSRF token for ajax request
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(document).ready( function () {
    // Date time picker plugin initialize
    if ($('.datetimepicker').get(0)) {
        $('.datetimepicker').datetimepicker({
            format         : 'Y-m-d h:i A',
            formatTime     : 'h:i A',
            validateOnBlur : false
        });
    }

    $("#page-form").validate({
        errorElement: 'i',
        errorPlacement: function (error, element) {
            element.next("span").html(error);
            $(".invalid-feedback[data-error='" + element.attr('name').replace('[]', '') + "']").html(error).show();
        },
        highlight: function (element) {
             $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        },
        submitHandler: function (form) {
            $("#page-form button[type='submit']").attr('disabled', true);

            var formUrl = $(form).prop('action');
            var enctype = (typeof $(form).attr('enctype') != 'undefined');
            var formData = enctype ? new FormData($(form).get(0)) : $(form).serialize();
            var ajaxArg = {
                type: 'POST',
                url: formUrl,
                data: formData,
                dataType: 'JSON',
                success: function (data) {
                    $('.invalid-feedback').html('');

                    if (data.status === true) {
                        $("#page-form input").addClass("is-valid").removeClass("is-invalid");
                        $("#page-form select").addClass("is-valid").removeClass("is-invalid");

                        $.notify({ message: data.message }, defaultNotifyConfig('success'));
                    } else {
                        $.each(data.errors, function (index, value) {
                            $("input[name='" + index + "']").addClass("is-invalid").removeClass("is-valid");
                            $("select[name='" + index + "']").addClass("is-invalid").removeClass("is-valid");
                            $($("input[name='" + index + "']").closest('div.row')).find('.invalid-feedback').html(value);
                            $($("select[name='" + index + "']").closest('div.row')).find('.invalid-feedback').html(value);
                            $(".invalid-feedback[data-error='" + index + "']").html(value).show();
                        });
                    }

                    $("#page-form button[type='submit']").attr('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status === 419) {
                        errorMsg = jqXHR.responseJSON.message;
                    } else {
                        errorMsg = jqXHR.status ?
                                   typeof jqXHR.responseText !== 'undefined' && jqXHR.responseText.indexOf('Error Message:') !== -1 ?
                                   jqXHR.status + ' ' + jqXHR.responseText : jqXHR.status + ' ' + errorThrown : 'Internal Server Error';
                    }

                    $.notify({ message: errorMsg }, defaultNotifyConfig('danger'));
                    $("#page-form button[type='submit']").attr('disabled', false);
                }
            };

            if (enctype) {
                ajaxArg.processData = false;
                ajaxArg.contentType = false;
            }

            $.ajax(ajaxArg);

            return false;
        },
        rules: {
            rocket: {
                required: true
            },
            time: {
                required: true
            }
        }
    });
});


/**
 * Default notify configuration.
 *
 * @param {string} type
 *
 * @return {Object}
 */
function defaultNotifyConfig (type) {
    var icon = 'fa fa-info-circle';

    // Get alter CSS class according to alert type.
    if (type === 'info') {
        icon = 'fa fa-info-circle';
    } else if (type === 'success') {
        icon = 'fa fa-check-circle';
    } else if (type === 'warning') {
        icon = 'fa fa-exclamation-triangle';
    } else if (type === 'danger') {
        icon = 'fa fa-exclamation-circle';
    }

    return {
        showProgressbar: true,
        placement: { from: 'bottom', align: 'right' },
        offset: { x: 20, y: 24 },
        delay: 3000,
        timer: 260,
        animate: { enter: 'animated fadeInRight', exit: 'animated fadeOutUp' },
        template: "<div data-notify='container' class='alert alert-" + type + " slight' role='alert'>" +
                    "<span class='" + icon + "'></span>" +
                    '{2}' +
                '</div>'
    };
}
