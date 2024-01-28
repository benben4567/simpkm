$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".datepicker").datepicker({
        format: "yyyy-mm-dd"
    })

    $(".numeric").on('input', function (e) {
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    $("#form-teacher").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            beforeSend: function () {
                $.LoadingOverlay("show")
                $("*").removeClass('is-invalid')
                $("div.invalid-feedback").find('ul').empty();
            },
            success: function (response) {
                $.LoadingOverlay("hide")
                Swal.fire({
                    title: 'Berhasil!',
                    text: response.meta.message,
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload()
                    }
                })
            },
            error: function (xhr) {
                $.LoadingOverlay('hide')
                switch (xhr.status) {
                    case 422:
                        let errors = xhr.responseJSON.data
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.meta.message,
                            'error'
                        )

                        $.each(errors, function (key, value) {
                            $("select[name=" + key + "]").addClass("is-invalid")
                            $("input[name=" + key + "]").addClass("is-invalid")
                            $.each(errors[key], function (ke, val) {
                                $('<li>' + val + "</li>").appendTo($("div[name=msg_" + key + "]").find('ul'));
                            })
                        })
                        break;
                    default:
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.meta.message,
                            icon: 'error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        })
                        break;
                }
            }
        });
    });
});
