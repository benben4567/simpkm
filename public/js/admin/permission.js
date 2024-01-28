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
        ajax: "/admin/ref-permission",
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
                "data": "name",
                "className": "text-center",
            },
            {
                "targets": 2,
                "width": "15%",
                "className": "text-center",
                "data": "id",
                "render": function (data, type, row, meta) {
                    var btnEdit = `<button type="button" class="btn btn-icon btn-sm btn-warning btn-edit" title="Edit"><i class="fas fa-pencil-alt"></i></button>`
                    return `${btnEdit}`
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
        $('#id-permission').val(data.id);
        $("input[name='name']").val(data.name);
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
});


