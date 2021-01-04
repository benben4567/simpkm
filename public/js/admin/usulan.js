$(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
    }
  });

  var table = $("#table").DataTable({
      dom: "tip",
      ordering: false,
      columnDefs: [
        { "width": "10%", "targets": 0},
      ]
  })

  $('#tahun').on('change', function() {
    var tahun = $(this).val();
    $.ajax({
      type: "get",
      url: "/admin/usulan",
      data: {tahun: tahun},
      beforeSend: function () {
        $.LoadingOverlay('show');
      },
      success: function (response) {
        $.LoadingOverlay('hide');
        if (response.success) {
          populateTable(response.data)
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        $.LoadingOverlay('hide')
        console.log(xhr)
        Swal.fire(
          'Terjadi Kesalahan!',
          xhr.responseText,
          'error'
        )
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
        { "width": "10%", "targets": 0},
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
            return row.skema;
          }
        },
        {
          "targets":2,
          "data": null,
          "render": function (data, type, row, meta) {
            return row.judul
          }
        },
        {
          "targets" : 3,
          "data" : null,
          "render": function (data, type, row, meta) {
            if (row.status == "kompilasi") {
              return `<span class="badge badge-primary">Kompilasi</span>`;
            } else if (row.status == 'proses') {
              return `<span class="badge badge-warning">Proses</span>`;
            } else {
              return `<span class="badge badge-success">Selesai</span>`;
            }


          }
        },
        {
          "targets": 4,
          "data": null,
          "render": function ( data, type, row, meta ) {
            return `<button type="button" class="btn btn-icon btn-sm btn-warning btn-edit" title="Edit" data-id="${row.id}"><i class="fas fa-pencil-alt"></i></button>`
          }
        },
      ]
    });
  }

  $('#table tbody').on('click', 'button.btn-edit', function (e) {
    e.preventDefault()
    var id = $(this).data('id');
    $.ajax({
      type: "get",
      url: "/admin/usulan/show/"+id,
      data: "",
      beforeSend: function() {
        $.LoadingOverlay('show');
      },
      success: function (response) {
        $.LoadingOverlay('hide')
        if (response.success) {
          $("select").val("").selectric('refresh');

          var data = response.data;
          var proposal = data.proposal;
          var pembimbing = data.pembimbing;
          var reviewer1 = data.reviewer1;
          var reviewer2 = data.reviewer2;
          $('#id-proposal').val(proposal.id);
          $("select[name='pembimbing']").val(pembimbing.id).selectric('refresh');
          if (reviewer1) {
            $("select[name='reviewer_1']").val(reviewer1.id).selectric('refresh');
          }
          if (reviewer2) {
            $("select[name='reviewer_2']").val(reviewer2.id).selectric('refresh');
          }
          $("select[name='status']").val(proposal.status).selectric('refresh');

          $('#modalEdit').modal('show');
        }
      },

    });
  });

  $('#form-update').submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: "put",
      url: "/admin/usulan/update",
      data: $(this).serialize(),
      beforeSend: function() {
        $.LoadingOverlay('show')
      },
      success: function (response) {
        $.LoadingOverlay('hide')
        if (response.success) {
          $('#modalEdit').modal('hide');
          populateTable(response.data)
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
              'Semua kolom wajib diisi. Dosen Pembimbing dan Reviewer tidak boleh sama.',
              'error'
            )

            $.each(errors, function(key, value) {
              $("select[name="+ key +"]").addClass("is-invalid")
              $.each(errors[key], function(ke, val) {
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

  function populateSelect(data) {

    // $(".teacher").append(data).selectric();
  }


});


