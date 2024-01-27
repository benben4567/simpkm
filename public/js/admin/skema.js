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
    ajax: "/admin/ref-skema",
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
        "data": "nama",
        "className": "text-center",
      },
      {
        "targets": 2,
        "data": "kepanjangan",
        "className": "text-center",
      },
      {
        "targets": 3,
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
          var btnToggle = `<button type="button" class="btn btn-icon btn-sm btn-${row.is_aktif == 1 ? 'danger' : 'success'} btn-toggle" title="${row.is_aktif == 1 ? 'Nonaktifkan' : 'Aktifkan'}"><i class="fas fa-power-off"></i></button>`

          return `${btnEdit} ${btnToggle}`
        }
      },
    ],
    buttons: ['add'],
    initComplete: function () {
      table.buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');
    }
  });


  $('#table tbody').on('click', 'button.btn-edit', function (e) {
    e.preventDefault()
    var data = table.row($(this).parents('tr')).data();
    $('#id-skema').val(data.id);
    $("input[name='nama']").val(data.nama);
    $("input[name='kepanjangan']").val(data.kepanjangan);
    $('#modalEdit').modal('show');
  });

  // Submit form update
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
        table.ajax.reload(null,false);
        Swal.fire(
          'Berhasil!',
          response.meta.message,
          'success'
        )
      },
      error: function (xhr) {
        $.LoadingOverlay('hide')
        switch (xhr.status) {
          case 422:
            Swal.fire(
              'Error!',
              'Data yang diinput tidak valid',
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

  // Submit form create
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
      error: function (xhr) {
        $.LoadingOverlay('hide')
        switch (xhr.status) {
          case 422:
            Swal.fire(
              'Error!',
              'Data yang dikirim tidak valid',
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

  // Button toggle
  $('#table tbody').on('click', 'button.btn-toggle', function (e) {
    e.preventDefault()
    var data = table.row($(this).parents('tr')).data();
    var url = "/admin/ref-skema/toggle/"
    var status = data.is_aktif == 1 ? 'Nonaktifkan' : 'Aktifkan';
    Swal.fire({
      title: 'Anda yakin?',
      text: `Anda akan ${status} skema ini!`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, lanjutkan!',
      cancelButtonText: 'Tidak, batalkan!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {
        $.ajax({
          type: "PUT",
          url: url,
          data: {
            id: data.id
          },
          beforeSend: function () {
            $.LoadingOverlay('show');
          },
          success: function (response) {
            $.LoadingOverlay('hide')
            table.ajax.reload();
            Swal.fire(
              'Berhasil!',
              response.meta.message,
              'success'
            )
          },
          error: function (jqXHR, textStatus, errorThrown) {
            $.LoadingOverlay('hide')
            Swal.fire(
              'Error!',
              jqXHR.responseJSON.meta.message,
              'error'
            )
          }
        });
      }
    })
  });

});


