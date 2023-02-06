$(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
    }
  });

  var table = $("#table").DataTable({
      dom: "ftip",
      ordering: false,
      columnDefs: [
        { "width": "5%", "targets": 0, "className": "text-center"},
        { "width": "9%", "targets": 3, "className": "text-center"},
        { "width": "10%", "targets": 4, "className": "text-center"},
      ]
  })

  $('#tahun, #skema').on('change', function() {
    var tahun = $("#tahun").val();
    var skema = $('#skema').val();
    $.ajax({
      type: "get",
      url: "/admin/usulan",
      data: {tahun: tahun, skema: skema},
      beforeSend: function () {
        $.LoadingOverlay('show');
      },
      success: function (response) {
        $.LoadingOverlay('hide');
        if (response.success) {
          populateTable(response.data)
          // display print button
          // $('.btn-print').show();
          // $(".btn-print").attr("href", window.location.pathname + "/print/" + tahun)
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

  // $('.btn-print').on('click', function () {
  //   alert($('#tahun').val());
  // });

  function populateTable(data) {
    // destroy datatable
    $('#table').DataTable().clear().destroy();
    // re-initiate filled datatable
    window.table = $('#table').DataTable({
      stateSave: true,
      dom: "ftip",
      data: data,
      responsive: true,
      ordering: false,
      "columnDefs": [
        { "className": "text-center", "targets": [0,2,3,4] },
        { "width": "5%", "targets": 0},
        { "width": "10%", "targets": 2},
        { "width": "9%", "targets": 3},
        { "width": "10%", "targets": 4},
        {
          "targets": 0,
          "data": null,
          "render": function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {
          "targets":1,
          "data": null,
          "render": function (data, type, row, meta) {
            return `<span style="word-break: normal"> ${row.judul} </span></br><strong>${row.skema}</strong>`
          }
        },
        {
          "targets" : 2,
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
          "targets":3,
          "data": null,
          "render": function (data, type, row, meta) {
            if (row.nilai) {
              return row.nilai
            } else {
              return '0'
            }
          }
        },
        {
          "targets": 4,
          "data": null,
          "render": function ( data, type, row, meta ) {
            return `
              <div class="btn-group">
                <a href="/admin/usulan/review/${row.id}" role="button" class="btn btn-icon btn-sm btn-primary" title="Show"><i class="fas fa-eye"></i></a>
                <button type="button" class="btn btn-icon btn-sm btn-warning btn-edit" title="Edit" data-id="${row.id}"><i class="fas fa-pencil-alt"></i></button>
                <button type="button" class="btn btn-icon btn-sm btn-info btn-nilai" title="Nilai" data-id="${row.id}"><i class="fas fa-file-signature"></i></button>
                <button type="button" class="btn btn-icon btn-sm btn-danger btn-delete" title="Delete" data-id="${row.id}"><i class="fas fa-trash"></i></button>
              </div>`
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
          var data = response.data;
          var proposal = data.proposal;
          var pembimbing = data.pembimbing;
          var reviewer = data.reviewer;
          $('#id-proposal').val(proposal.id);
          $("select[name='pembimbing']").val(pembimbing.id).selectric('refresh');
          if (reviewer) {
            $("select[name='reviewer']").val(reviewer.id).selectric('refresh');
          }
          $("select[name='status']").val(proposal.status).selectric('refresh');

          $('#modalEdit').modal('show');
        }
      },

    });
  });

  $('#table tbody').on('click', '.btn-nilai', function (e) {
    e.preventDefault()
    var data = $("#table").DataTable().row($(this).parents('tr') ).data();
    var nilai = data.nilai;
    $('input[name="id-proposal"]').val($(this).data('id'));
    $('input[name="nilai"]').val(nilai);
    $('#modalNilai').modal('show');
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

  $('#form-nilai').submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: "put",
      url: "/admin/usulan/nilai",
      data: $(this).serialize(),
      beforeSend: function() {
        $.LoadingOverlay('show')
      },
      success: function (response) {
        $.LoadingOverlay('hide')
        if (response.success) {
          $('#modalNilai').modal('hide');
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
              'Semua kolom wajib diisi',
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

  $('#table tbody').on('click', 'button.btn-show', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    $.ajax({
      type: "get",
      url: "usulan/show/"+id,
      data: null,
      dataType: "JSON",
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      success: function (response) {
        console.log(response.data.proposal.skema)
        $.LoadingOverlay("hide")
        if (response.success) {
          var data = response.data
          var anggota = data.anggota
          $("dd#skema").html(data.proposal.skema);
          $("#judul").html(data.proposal.judul);
          $("#pembimbing").html(data.pembimbing.nama);
          $("#ketua").html(data.ketua.nama);
          $("#reviewer").html(data.reviewer ? data.reviewer.nama : '-');
          if (anggota.length > 0) {
            $.each(anggota, function(key, value) {
              $("#anggota ul").append('<li>'+ value.nama +'</li>');
            })
          }
        }
      },
      error: function(xhr) {
        $.LoadingOverlay('hide')
        switch (xhr.status) {
          case 404:
            Swal.fire(
              'Oops!',
              'Usulan tidak ditemukan.',
              'error'
            )
            break;
          default:
            break;
        }
      }
    });
    $('#modalShow').modal('show')
  });

  $('#table tbody').on('click', 'button.btn-delete', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    Swal.fire({
      title: 'Anda yakin?',
      text: "Proposal akan dihapus.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        var tahun = $('#tahun').val();
        $.ajax({
          type: "delete",
          url: "usulan",
          data: {id: id, tahun: tahun},
          dataType: "JSON",
          beforeSend: function() {
            $.LoadingOverlay("show")
          },
          success: function (response) {
            $.LoadingOverlay("hide")
            if (response.success) {
              populateTable(response.data)
              Swal.fire(
                "Berhasil!",
                response.msg,
                'success'
              )
            }
          },
          error: function(xhr) {
            $.LoadingOverlay('hide')
            Swal.fire(
              'Error!',
              xhr.responseJSON.message,
              'error'
            )
          }
        });
      }
    })
  });

  $('#modalNilai').on('hidden.bs.modal', function (e) {
    $('input[name="nilai"]').val("");
  })

});


