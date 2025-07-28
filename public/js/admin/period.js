$(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
    }
  });

  var table = $("#table").DataTable({
      dom: "tip",
      ordering: false,
  })

  $("#create-periode").submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: "post",
      url: "/admin/periode/store",
      data: $(this).serialize(),
      beforeSend: function () {
        $.LoadingOverlay('show')
      },
      success: function (response) {
        $.LoadingOverlay('hide')
        if (response.success) {
          populateTable(response.data)
          $("#modalPeriode").modal('hide')
          Swal.fire(
            'Berhasil!',
            'Periode pembukaan sudah disimpan.',
            'success'
          )
        } else {
          $("#modalPeriode").modal('hide')
          Swal.fire(
            'Gagal!',
            response.msg,
            'error'
          )
        }
      },
      error: function(xhr) {
        $.LoadingOverlay('hide')
        if (xhr.status == 422) {
          Swal.fire(
            'Gagal!',
            'Tahun Pembukaan sudah ada.',
            'error'
          )
        } else {
          console.log(xhr)
        }
      }
    });
  });

  $("#edit-periode").submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: "put",
      url: "/admin/periode/update",
      data: $(this).serialize(),
      beforeSend: function() {
        $.LoadingOverlay('show')
      },
      success: function (response) {
        $.LoadingOverlay('hide')
        if (response.success) {
          populateTable(response.data);
          $("#modalUpdate").modal("hide");
          Swal.fire(
            'Berhasil!',
            'Periode berhasil diubah.',
            'success'
          )
        } else {
          $("#modalUpdate").modal("hide");
          Swal.fire(
            'Oops!',
            response.msg,
            'error'
          )
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        $.LoadingOverlay('hide')
        console.log(xhr.responseText)
      }
    });
  });

  function populateTable(data) {
    // destroy datatable
    $('#table').DataTable().clear().destroy();
    // re-initiate filled datatable
    window.table = $('#table').DataTable({
      stateSave: true,
      dom: "tip",
      data: data,
      responsive: true,
      ordering: false,
      "columnDefs": [
        { "className": "text-center text-nowrap", "targets": "_all" },
        {
          "targets": 0,
          "data": null,
          "render": function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {
          "targets": 1,
          "data": null,
          "render": function (data, type, row, meta) {
            return row.tahun ;
          }
        },
        {
            "targets":2,
            "data": null,
            "render": function (data, type, row, meta) {
              return row.kegiatan ? row.kegiatan : 'Tidak ada kegiatan';
            }
        },
        {
          "targets":3,
          "data": null,
          "render": function (data, type, row, meta) {
            return row.proposals_count
          }
        },
        {
          "targets" : 4,
          "data" : null,
          "render": function (data, type, row, meta) {
            if (row.status == "aktif") {
              return `<span class="badge badge-success">Aktif</span>`;
            } else {
              return `<span class="badge badge-danger">Nonaktif</span>`;
            }
          }
        },
        {
          "targets" : 5,
          "data" : null,
          "render": function (data, type, row, meta) {
            if (row.pendaftaran == "buka") {
              return `<span class="badge badge-success">Buka</span>`;
            } else {
              return `<span class="badge badge-danger">Tutup</span>`;
            }
          }
        },
        {
          "targets": 6,
          "data": null,
          "render": function ( data, type, row, meta ) {
            return `<button type="button" class="btn btn-icon btn-sm btn-warning" title="Edit" data-id="${row.id}"><i class="fas fa-pencil-alt"></i></button>`
          }
        },
      ]
    });
  }

  $('#table tbody').on( 'click', 'button', function () {
    // var data = table.row( $(this).parents('tr') ).data();
    var id = $(this).data('id');
    console.log(id);
    $.ajax({
      type: "get",
      url: "/admin/periode/show",
      data: {id: id},
      beforeSend: function() {
        $.LoadingOverlay('show')
      },
      success: function (response) {
        $.LoadingOverlay('hide')
        if (response.data) {
          var data = response.data;
          console.log(data);
          $("input[name='id']").val(data.id);
          $("input[name='tahun']").val(data.tahun);
          $("input[name='kegiatan']").val(data.kegiatan);
          $("select[name='status']").val(data.status).change().selectric('refresh');
          $("select[name='pendaftaran']").val(data.pendaftaran).change().selectric('refresh');

          if (data.status == 'aktif') {
            $('.pendaftaran').show();
          } else {
            $('.pendaftaran').hide();
          }

          $("#modalUpdate").modal("show");
        }
      }
    });
  });

});


