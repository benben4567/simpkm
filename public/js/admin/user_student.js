$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $(".numeric").on('input', function (e) {
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    $(".datepicker").datepicker({
        format: "yyyy-mm-dd"
    })

    $("#form-student").on('submit', function (e) {
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
    
    // Button check nim
    $("#btn-check-nim").on('click', function (e) {
        e.preventDefault();
        let nim = $("input[name=nim]").val()
        let angkatan = $("select[name=angkatan]").val()
        if (nim == "" || angkatan == "") {
            Swal.fire(
                'Error!',
                'Angkatan dan NIM tidak boleh kosong.',
                'error'
            )
        } else {
            $.ajax({
                type: "post",
                url: "/admin/student/check-nim",
                data: {
                    nim: nim,
                    angkatan: angkatan
                },
                beforeSend: function () {
                    $.LoadingOverlay("show")
                },
                success: function (response) {
                    $.LoadingOverlay("hide")
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.meta.message,
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    })
                    
                    $("input[name=nama]").val(response.data.nama);
                    $("select[name=major]").val(response.data.prodi).selectric('refresh');
                    $("input[name=no_hp]").val(response.data.telepon);
                    $("input[name=email]").val(response.data.email);
                    $("input[name=tempat]").val(response.data.tempat_lahir);
                    $("input[name=tgl]").val(response.data.tanggal_lahir);
                    $("select[name=jk]").val(response.data.jk).selectric('refresh');
                    $("input[name=password]").val(response.data.password);
                    
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
        }
    });
});
