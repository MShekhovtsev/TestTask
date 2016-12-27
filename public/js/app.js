$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.datetimepicker').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});

    $("body").tooltip({
        container: 'body',
        selector: '[data-toggle="tooltip"]'
    });

    $('form').each(function(i, form) {
        $(form).validate({
            ignore: "",
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'help-block',
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (e, validator) {
                form = validator.currentForm;
                $(form).find('.tab-pane').each(function () {
                    if ($(this).find('.has-error').length) {
                        $('a[href="#' + $(this).attr('id') + '"]').click();
                        return false;
                    }
                });
            }
        });
    });

    $('input.switch').bootstrapSwitch();
});