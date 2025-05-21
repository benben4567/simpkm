$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.fn.dataTable.ext.buttons.add = {
        text: '<i class="fas fa-plus"></i> Baru',
        className: 'btn-primary',
        action: function (e, dt, node, config) {
            $('#form-create').trigger('reset');
            $('#modalCreate').modal('show');
        }
    };

    var table = $("#table").DataTable({
        ajax: "/admin/major",
        ordering: false,
        lengthChange: false,
        responsive: true,
        columnDefs: [
            {
                "targets": 0,
                "width": "10%",
                "className": "text-center",
                "data": null,
                "render": function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {
                "targets": 1,
                "data": "jenjang",
                "width": "15%",
                "className": "text-center",
            },
            {
                "targets": 2,
                "data": "nama",
                "className": "text-center",
            },
            {
                "targets": 3,
                "width": "15%",
                "data": "is_aktif",
                "className": "text-center",
                "render": function (data, type, row, meta) {
                    if (data == 1) {
                        return `<span class="badge badge-success">Aktif</span>`
                    } else {
                        return `<span class="badge badge-danger">Tidak Aktif</span>`
                    }
                }
            },
            {
                "targets": 4,
                "width": "15%",
                "className": "text-center",
                "data": "id",
                "render": function (data, type, row, meta) {
                    var btnEdit = `<button type="button" class="btn btn-icon btn-sm btn-warning btn-edit" title="Edit"><i class="fas fa-pencil-alt"></i></button>`
                    var btnToggle = `<button type="button" class="btn btn-icon btn-sm btn-${row.is_aktif == 1 ? 'danger' : 'success'} btn-toggle" data-id="${data}" title="${row.is_aktif == 1 ? 'Nonaktifkan' : 'Aktifkan'}"><i class="fas fa-power-off"></i></button>`
                    return `${btnEdit} ${btnToggle}`
                }
            },
        ],
        buttons: ['add'],
        initComplete: function () {
            table.buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');
        }
    });

    // Button Edit
    $('#table tbody').on('click', 'button.btn-edit', function (e) {
        e.preventDefault()
        var data = table.row($(this).parents('tr')).data();
        $('#id-prodi').val(data.id);
        $("select[name='jenjang']").val(data.jenjang).selectric('refresh');
        $("input[name='nama']").val(data.nama);
        $("input[name='kode_prodi']").val(data.kode_prodi);

        $('#modalEdit').modal('show');
    });

    // Submit Form Edit
    $('#form-update').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "put",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            beforeSend: function () {
                $.LoadingOverlay('show')
            },
            success: function (response) {
                $.LoadingOverlay('hide')
                $('#modalEdit').modal('hide');
                table.ajax.reload();
                Swal.fire(
                    'Berhasil!',
                    response.meta.message,
                    'success'
                )
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.LoadingOverlay('hide')
                switch (xhr.status) {
                    case 422:
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.meta.message,
                            'error'
                        )

                        $.each(xhr.responseJSON.data, function (key, value) {
                            $("select[name=" + key + "]").addClass("is-invalid")
                            $("input[name=" + key + "]").addClass("is-invalid")
                            $.each(xhr.responseJSON.data[key], function (ke, val) {
                                $('<li>' + val + "</li>").appendTo($("div[name=msg_" + key + "]").find('ul'));
                            })
                        })
                        break;
                    default:
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.meta.message,
                            'error'
                        )
                        break;
                }
            }
        });
    });

    // Submit Form Toggle
    $('#form-create').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            beforeSend: function () {
                $.LoadingOverlay('show')
            },
            success: function (response) {
                $.LoadingOverlay('hide')
                $('#modalCreate').modal('hide');
                table.ajax.reload();
                Swal.fire(
                    'Berhasil!',
                    response.meta.message,
                    'success'
                )
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.LoadingOverlay('hide')
                switch (xhr.status) {
                    case 422:
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.meta.message,
                            'error'
                        )

                        $.each(xhr.responseJSON.data, function (key, value) {
                            $("select[name=" + key + "]").addClass("is-invalid")
                            $("input[name=" + key + "]").addClass("is-invalid")
                            $.each(xhr.responseJSON.data[key], function (ke, val) {
                                $('<li>' + val + "</li>").appendTo($("div[name=msg_" + key + "]").find('ul'));
                            })
                        })
                        break;
                    default:
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.meta.message,
                            'error'
                        )
                        break;
                }
            }
        });
    });
    
    // Button Toggle
    $('#table tbody').on('click', 'button.btn-toggle', function (e) {
        e.preventDefault()
        var data = table.row($(this).parents('tr')).data();
        var id = $(this).data('id');
        var status = data.is_aktif == 1 ? 0 : 1;
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: `Prodi ${data.nama} akan di ${status == 1 ? 'aktifkan' : 'nonaktifkan'}!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: `Ya, ${status == 1 ? 'aktifkan' : 'nonaktifkan'}!`,
            cancelButtonText: 'Tidak, batalkan!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "put",
                    url: `/admin/major/toggle`,
                    data: {
                        id: id,
                    },
                    beforeSend: function () {
                        $.LoadingOverlay('show')
                    },
                    success: function (response) {
                        $.LoadingOverlay('hide')
                        table.ajax.reload(null, false);
                        Swal.fire(
                            'Berhasil!',
                            response.meta.message,
                            'success'
                        )
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $.LoadingOverlay('hide')
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.meta.message,
                            'error'
                        )
                    }
                });
            }
        })
    });
});


