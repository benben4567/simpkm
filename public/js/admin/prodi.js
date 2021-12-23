$(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
    }
  });

  $.fn.dataTable.ext.buttons.add = {
    text: '<i class="fas fa-plus"></i> Baru',
    className: 'btn-primary',
    action: function ( e, dt, node, config ) {
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
          "data": "name",
          "className": "text-center",
          "render": function (data, type, row, meta) {
            return `${row.degree} ${data}`
          }
        },
        {
          "targets": 2,
          "width": "25%",
          "className": "text-center",
          "data": "id",
          "render": function ( data, type, row, meta ) {
            return `<button type="button" class="btn btn-icon btn-sm btn-warning btn-edit" title="Edit" data-id="${row.id}"><i class="fas fa-pencil-alt"></i></button>`
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
    var id = $(this).data('id');
    $.ajax({
      type: "get",
      url: "/admin/major/show/"+id,
      data: "",
      beforeSend: function() {
        $.LoadingOverlay('show');
      },
      success: function (response) {
        $.LoadingOverlay('hide')
        if (response.meta.status == 'success') {
          var data = response.data;
          var jenjang = data.degree;
          var nama = data.name;
          console.log(data.id)
          $('#id-prodi').val(data.id);
          $("select[name='jenjang']").val(jenjang).selectric('refresh');
          $("input[name='nama']").val(nama);

          $('#modalEdit').modal('show');
        }
      },

    });
  });

  $('#form-update').submit(function (e) {
    e.preventDefault();
    console.log($(this).serialize())
    $.ajax({
      type: "put",
      url: "/admin/major/update",
      data: $(this).serialize(),
      beforeSend: function() {
        $.LoadingOverlay('show')
      },
      success: function (response) {
        $.LoadingOverlay('hide')
        if (response.meta.status == 'success') {
          $('#modalEdit').modal('hide');
          table.ajax.reload();
          Swal.fire(
            'Berhasil!',
            'Data berhasil disimpan.',
            'success'
          )
        }
      },
      error: function(xhr) {
        $.LoadingOverlay('hide')
        switch (xhr.status) {
          case 422:
            Swal.fire(
              'Error!',
              'Data yang diinput tidak valid',
              'error'
            )

            $.each(xhr.responseJSON.data, function(key, value) {
              $("select[name="+ key +"]").addClass("is-invalid")
              $("input[name="+ key +"]").addClass("is-invalid")
              $.each(xhr.responseJSON.data[key], function(ke, val) {
                $('<li>'+val+"</li>").appendTo($("div[name=msg_"+ key +"]").find('ul'));
              })
            })
            break;

          default:
            break;
        }
      }
    });
  });

  $('#form-create').submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: "post",
      url: "/admin/major/store",
      data: $(this).serialize(),
      beforeSend: function() {
        $.LoadingOverlay('show')
      },
      success: function (response) {
        $.LoadingOverlay('hide')
        if (response.meta.status == 'success') {
          $('#modalCreate').modal('hide');
          table.ajax.reload();
          Swal.fire(
            'Berhasil!',
            'Data berhasil disimpan.',
            'success'
          )
        }
      },
      error: function(xhr) {
        $.LoadingOverlay('hide')
        switch (xhr.status) {
          case 422:
            Swal.fire(
              'Error!',
              'Data yang dikirim tidak valid',
              'error'
            )

            $.each(xhr.responseJSON.data, function(key, value) {
              $("select[name="+ key +"]").addClass("is-invalid")
              $("input[name="+ key +"]").addClass("is-invalid")
              $.each(xhr.responseJSON.data[key], function(ke, val) {
                $('<li>'+val+"</li>").appendTo($("div[name=msg_"+ key +"]").find('ul'));
              })
            })
            break;

          default:
            break;
        }
      }
    });
  });

});


