$(document).ready(function () {
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
      }
  });

  $('input[type="file"]').change(function(e){
    var fileName = e.target.files[0].name;
    $('.custom-file-label').html(fileName);
  });

  var table1 = $("#table-admin").DataTable({
    dom: "tip",
    ordering: false,
  })

  var table2 = $("#table-student").DataTable({
    dom: "ftip",
    ordering: false,
  })

  var table3 = $("#table-teacher").DataTable({
    dom: "tip",
    ordering: false,
  })

  $('#adminModalEdit').on('hidden.bs.modal', function (e) {
    $("#form-admin-edit").trigger("reset");
  })

  $('#adminModal').on('show.bs.modal', function (e) {
    $("#form-admin").trigger("reset");
  })

  $("#form-import").submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: "post",
      url: "user/import",
      data: new FormData(this),
      dataType: 'JSON',
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function() {
        $.LoadingOverlay("show")
        $("*").removeClass('is-invalid')
        $("div.invalid-feedback").find('ul').empty();
      },
      success: function (response) {
        $('#modalImport').modal("hide")
        $('.custom-file-label').html("Choose file");
        $.LoadingOverlay("hide")
        if (response.success) {
          populateTableTeacher(response.data)
          Swal.fire(
            "Berhasil!",
            response.msg,
            'success'
          )
        }
      },
      error: function(xhr) {
        $.LoadingOverlay('hide')
        switch (xhr.status) {
          case 422:
            let errors = xhr.responseJSON.errors
            Swal.fire(
              'Error!',
              xhr.responseJSON.message,
              'error'
            )
            $.each(errors, function(key, value) {
              $("input[name="+ key +"]").addClass("is-invalid")
              $.each(errors[key], function(ke, val) {
                $('<li>'+val+"</li>").appendTo($("div[name=msg_"+ key +"]").find('ul'));
              })
            })
            break;
          case 500:
            Swal.fire(
              'Error!',
              'Terjadi kesalahan, periksa kembali data anda',
              'error'
            )
            break;
          default:
            break;
        }
      }
    });
  });

  $("#form-admin").submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: "post",
      url: "user/store/admin",
      data: $(this).serialize(),
      beforeSend: function() {
        $.LoadingOverlay("show")
        $("*").removeClass('is-invalid')
        $("div.invalid-feedback").find('ul').empty();
      },
      success: function (response) {
        $('#adminModal').modal("hide")
        $.LoadingOverlay("hide")
        if (response.success) {
          populateTableAdmin(response.data)
          Swal.fire(
            "Berhasil!",
            'User admin sudah disimpan.',
            'success'
          )
        }
      },
      error: function(xhr) {
        $.LoadingOverlay('hide')
        switch (xhr.status) {
          case 422:
            let errors = xhr.responseJSON.errors
            Swal.fire(
              'Error!',
              xhr.responseJSON.message,
              'error'
            )

            $.each(errors, function(key, value) {
              $("input[name="+ key +"]").addClass("is-invalid")
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

  $("#form-admin-edit").submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: "PUT",
      url: "user/update/admin",
      data: $(this).serialize(),
      beforeSend: function() {
        $.LoadingOverlay("show")
        $("*").removeClass('is-invalid')
        $("div.invalid-feedback").find('ul').empty();
      },
      success: function (response) {
        $('#adminModalEdit').modal("hide")
        $.LoadingOverlay("hide")
        if (response.success) {
          populateTableAdmin(response.data)
          Swal.fire(
            "Berhasil!",
            'Perubahan user sudah disimpan.',
            'success'
          )
        }
      },
      error: function(xhr) {
        $.LoadingOverlay('hide')
        switch (xhr.status) {
          case 422:
            let errors = xhr.responseJSON.errors
            Swal.fire(
              'Error!',
              xhr.responseJSON.message,
              'error'
            )

            $.each(errors, function(key, value) {
              $("input[name="+ key +"]").addClass("is-invalid")
              $.each(errors[key], function(ke, val) {
                $('<li>'+val+"</li>").appendTo($("div[name=msg_"+ key +"_edit]").find('ul'));
              })
            })
            break;

          default:
            break;
        }
      }
    });
  });

  $("#form-student-edit").submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: "PUT",
      url: "user/update/student",
      data: $(this).serialize(),
      beforeSend: function() {
        $.LoadingOverlay("show")
        $("*").removeClass('is-invalid')
        $("div.invalid-feedback").find('ul').empty();
      },
      success: function (response) {
        console.log(response)
        $('#studentModalEdit').modal("hide")
        $.LoadingOverlay("hide")
        if (response.success) {
          populateTableStudent(response.data)
          Swal.fire(
            "Berhasil!",
            'Perubahan user sudah disimpan.',
            'success'
          )
        }
      },
      error: function(xhr) {
        $.LoadingOverlay('hide')
        switch (xhr.status) {
          case 422:
            let errors = xhr.responseJSON.errors
            Swal.fire(
              'Error!',
              xhr.responseJSON.message,
              'error'
            )

            $.each(errors, function(key, value) {
              $("input[name="+ key +"]").addClass("is-invalid")
              $.each(errors[key], function(ke, val) {
                $('<li>'+val+"</li>").appendTo($("div[name=msg_"+ key +"_edit]").find('ul'));
              })
            })
            break;

          default:
            break;
        }
      }
    });
  });

  $('.table tbody').on( 'click', 'button.admin-edit', function () {
    var id = $(this).data('id');
    $.ajax({
      type: "get",
      url: "/admin/user/show/"+id,
      data: '',
      beforeSend: function() {
        $.LoadingOverlay('show')
      },
      success: function (response) {
        $.LoadingOverlay('hide')
        if (response.data) {
          var data = response.data;
          $("input[name='id']").val(data['id']);
          $("input[name='name']").val(data['name']);
          $("input[name='email']").val(data['email']);
          $("select[name='status']").val(data['status']).change().selectric('refresh');
          $("#adminModalEdit").modal("show");
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        $.LoadingOverlay('hide')
        console.log(xhr.responseText)
      }
    });
  });

  $('.table tbody').on( 'click', 'button.student-edit', function () {
    var id = $(this).data('id');
    $.ajax({
      type: "get",
      url: "/admin/user/show/"+id,
      data: '',
      beforeSend: function() {
        $.LoadingOverlay('show')
      },
      success: function (response) {
        $.LoadingOverlay('hide')
        if (response.data) {
          var data = response.data;
          $("input[name='id']").val(data['id']);
          $("input[name='name']").val(data['name']);
          $("input[name='email']").val(data['email']);
          $("select[name='status']").val(data['status']).change().selectric('refresh')
          $("#studentModalEdit").modal("show");
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        $.LoadingOverlay('hide')
        console.log(xhr.responseText)
      }
    });
  });

  function populateTableAdmin(data) {
    // destroy datatable
    $('#table-admin').DataTable().clear().destroy();
    // re-initiate filled datatable
    window.table = $('#table-admin').DataTable({
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
            return row.name;
          }
        },
        {
          "targets":2,
          "data": null,
          "render": function (data, type, row, meta) {
            return row.email
          }
        },
        {
          "targets" : 3,
          "data" : null,
          "render": function (data, type, row, meta) {
            if (row.status == "aktif") {
              return `<span class="badge badge-success">AKTIF</span>`;
            } else {
              return `<span class="badge badge-danger">NONAKTIF</span>`;
            }
          }
        },
        {
          "targets": 4,
          "data": null,
          "render": function ( data, type, row, meta ) {
            return `<button type="button" class="btn btn-sm btn-icon btn-primary admin-edit" data-id="${row.id}"><i class="fas fa-eye"></i></button>`
          }
        },
      ]
    });
  }

  function populateTableStudent(data) {
    // destroy datatable
    $('#table-student').DataTable().clear().destroy();
    // re-initiate filled datatable
    window.table = $('#table-student').DataTable({
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
            return row.nama;
          }
        },
        {
          "targets": 2,
          "data": null,
          "render": function (data, type, row, meta) {
            return row.nim;
          }
        },
        {
          "targets":3,
          "data": null,
          "render": function (data, type, row, meta) {
            return row.email
          }
        },
        {
          "targets" : 4,
          "data" : null,
          "render": function (data, type, row, meta) {
            if (row.status == "aktif") {
              return `<span class="badge badge-success">AKTIF</span>`;
            } else {
              return `<span class="badge badge-danger">NONAKTIF</span>`;
            }
          }
        },
        {
          "targets": 5,
          "data": null,
          "render": function ( data, type, row, meta ) {
            return `<button type="button" class="btn btn-sm btn-icon btn-primary student-edit" data-id="${row.id}"><i class="fas fa-eye"></i></button><button type="button" class="btn btn-sm btn-warning ml-1" data-id="${row.id}" data-toggle="modal" data-target="#mahasiswaSimModal"><i class="fas fa-key"></i></button>`
          }
        },
      ]
    });
  }

  function populateTableTeacher(data) {
    // destroy datatable
    $('#table-teacher').DataTable().clear().destroy();
    // re-initiate filled datatable
    window.table = $('#table-teacher').DataTable({
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
            return row.name;
          }
        },
        {
          "targets":2,
          "data": null,
          "render": function (data, type, row, meta) {
            return row.email
          }
        },
        {
          "targets" : 3,
          "data" : null,
          "render": function (data, type, row, meta) {
            if (row.status == "aktif") {
              return `<span class="badge badge-success">AKTIF</span>`;
            } else {
              return `<span class="badge badge-danger">NONAKTIF</span>`;
            }
          }
        },
        {
          "targets": 4,
          "data": null,
          "render": function ( data, type, row, meta ) {
            return `<button type="button" class="btn btn-sm btn-icon btn-primary student-edit" data-id="${row.id}"><i class="fas fa-eye"></i></button>`
          }
        },
      ]
    });
  }

});
