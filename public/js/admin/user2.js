$(document).ready(function () {
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
      }
  });

  $(".datepicker").datepicker({
    format: "yyyy-mm-dd"
  })

  $('input[type="file"]').change(function(e){
    var fileName = e.target.files[0].name;
    $('.custom-file-label').html(fileName);
  });

  var table1 = $("#table-admin").DataTable({
    ajax: "/admin/user?role=admin",
    dom: "tip",
    responsive: true,
    ordering: false,
    "columnDefs": [
      {
        "targets": 0,
        "data": null,
        "className": "text-center text-nowrap",
        "render": function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      {
        "targets": 1,
        "data": 'name',
        "className": "text-center text-nowrap",
        "render": function (data, type, row, meta) {
          return data;
        }
      },
      {
        "targets":2,
        "data": 'email',
        "className": "text-center text-nowrap",
        "render": function (data, type, row, meta) {
          return row.email
        }
      },
      {
        "targets" : 3,
        "data" : 'status',
        "className": "text-center text-nowrap",
        "render": function (data, type, row, meta) {
          if (data == "aktif") {
            return `<span class="badge badge-success">AKTIF</span>`;
          } else {
            return `<span class="badge badge-danger">NONAKTIF</span>`;
          }
        }
      },
      {
        "targets": 4,
        "data": 'id',
        "className": "text-center text-nowrap",
        "render": function ( data, type, row, meta ) {
          return `<button type="button" class="btn btn-sm btn-icon btn-primary admin-edit" data-id="${data}"><i class="fas fa-eye"></i></button>`
        }
      },
    ]
  })

  var table2 = $("#table-student").DataTable({
    ajax: "/admin/user?role=student",
    dom: "Bfrtip",
    ordering: false,
    responsive: true,
    autoWidth: false,
    columnDefs: [
      {
        "targets": 0,
        "data": null,
        "className": "text-center text-nowrap",
        "render": function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      {
        "targets": 1,
        "data": 'student.nama',
        "className": "text-center text-nowrap",
        "render": function (data, type, row, meta) {
          return data;
        }
      },
      {
        "targets": 2,
        "data": 'student.nim',
        "className": "text-center text-nowrap",
        "render": function (data, type, row, meta) {
          return data;
        }
      },
      {
        "targets":3,
        "data": 'email',
        "className": "text-center text-nowrap",
        "render": function (data, type, row, meta) {
          return data
        }
      },
      {
        "targets" : 4,
        "data" : 'status',
        "className": "text-center text-nowrap",
        "render": function (data, type, row, meta) {
          if (data == "aktif") {
            return `<span class="badge badge-success">AKTIF</span>`;
          } else {
            return `<span class="badge badge-danger">NONAKTIF</span>`;
          }
        }
      },
      {
        "targets": 5,
        "data": 'id',
        "className": "text-center text-nowrap",
        "render": function ( data, type, row, meta ) {
          return `<button type="button" class="btn btn-sm btn-icon btn-primary student-edit" data-id="${data}"><i class="fas fa-eye"></i></button><button type="button" class="btn btn-sm btn-warning ml-1 student-sim" data-id="${data}"><i class="fas fa-key"></i></button>`
        }
      },
    ],
      buttons: [
        {
            text: '<i class="fas fa-plus"></i> Baru',
            className: 'btn btn-sm btn-primary btn-baru',
            action: function ( e, dt, node, config ) {
              location.href = 'user/create/student'
            }
        },
        {
          text: '<i class="fas fa-file-excel"></i> Import',
          className: 'btn btn-sm btn-success btn-import',
          action: function ( e, dt, node, config ) {
            $('#modalImportStudent').modal('show')
          }
        }
      ],
  })

  var table3 = $("#table-teacher").DataTable({
    ajax: "/admin/user?role=teacher",
    dom: "Bfrtip",
    ordering: false,
    responsive: true,
    autoWidth: false,
    columnDefs: [
      { "className": "text-center text-nowrap", "targets": "_all" },
      {
        "targets": 0,
        "width": "10%",
        "className": "text-center text-nowrap",
        "data": null,
        "render": function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      {
        "targets": 1,
        "className": "text-center text-nowrap",
        "data": 'name',
        "render": function (data, type, row, meta) {
          return data;
        }
      },
      {
        "targets":2,
        "className": "text-center text-nowrap",
        "width": "40%",
        "data": 'email',
        "render": function (data, type, row, meta) {
          return data;
        }
      },
      {
        "targets" : 3,
        "className": "text-center text-nowrap",
        "data" : 'status',
        "render": function (data, type, row, meta) {
          if (data == "aktif") {
            return `<span class="badge badge-success">AKTIF</span>`;
          } else {
            return `<span class="badge badge-danger">NONAKTIF</span>`;
          }
        }
      },
      {
        "targets": 4,
        "className": "text-center text-nowrap",
        "data": 'id',
        "render": function ( data, type, row, meta ) {
          return `<button type="button" class="btn btn-sm btn-icon btn-primary teacher-edit" data-id="${data}"><i class="fas fa-eye"></i></button>`
        }
      },
    ],
    buttons: [
      {
          text: '<i class="fas fa-plus"></i> Baru',
          className: 'btn btn-sm btn-primary btn-baru',
          action: function ( e, dt, node, config ) {
            location.href = 'user/create/teacher'
          }
      },
      {
        text: '<i class="fas fa-file-excel"></i> Import',
        className: 'btn btn-sm btn-success btn-import',
        action: function ( e, dt, node, config ) {
          $('#modalImportTeacher').modal('show')
        }
      }
    ]
  })

  // biar button ngga gandeng
  $('.btn-import').parent('.dt-buttons').removeClass('btn-group');

  $('#adminModalEdit').on('hidden.bs.modal', function (e) {
    $("#form-admin-edit").trigger("reset");
  })

  $('#studentModalEdit').on('hidden.bs.modal', function (e) {
    $("#form-student-edit").trigger("reset");
  })

  $('#mahasiswaSimModal').on('hidden.bs.modal', function (e) {
    $("#form-update-sim").trigger("reset");
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
        $('#modalImport*').modal("hide")
        $('.custom-file-label').html("Choose file");
        $.LoadingOverlay("hide")
        if (response.success) {
          table3.ajax.reload();
          table2.ajax.reload();
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
          table1.ajax.reload();
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
          table1.ajax.reload()
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
          table2.ajax.reload()
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

  $("#form-teacher-edit").submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: "PUT",
      url: "user/update/teacher",
      data: $(this).serialize(),
      beforeSend: function() {
        $.LoadingOverlay("show")
        $("*").removeClass('is-invalid')
        $("div.invalid-feedback").find('ul').empty();
      },
      success: function (response) {
        console.log(response)
        $('#teacherModalEdit').modal("hide")
        $.LoadingOverlay("hide")
        if (response.success) {
          table3.ajax.reload()
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

  $("#form-update-sim").submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: "put",
      url: "user/sim",
      data: $(this).serialize(),
      dataType: "JSON",
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      success: function (response) {
        $.LoadingOverlay("hide")
        $('#mahasiswaSimModal').modal("hide")
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: response.msg,
          showConfirmButton: false,
          timer: 1500
        })
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
            break;

          default:
            break;
        }
      }
    });
  });

  $('.table tbody').on( 'click', 'button.student-sim', function () {
    var id = $(this).data('id');
    $.ajax({
      type: "get",
      url: `user/sim/${id}`,
      data: null,
      dataType: "JSON",
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      success: function (response) {
        $.LoadingOverlay("hide")
        if (response.success) {
          $('input[name="id"]').val(id);
          $('input[name="username_sim"]').val(response.data.username_sim);
          $('input[name="password_sim"]').val(response.data.password_sim);
          $('#mahasiswaSimModal').modal("show")
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
  });

  $('.table tbody').on( 'click', 'button.admin-edit', function () {
    var id = $(this).data('id');
    $.ajax({
      type: "get",
      url: "/admin/user/show/admin/"+id,
      data: '',
      beforeSend: function() {
        $.LoadingOverlay('show')
      },
      success: function (response) {
        $.LoadingOverlay('hide')
        if (response.data) {
          var data = response.data;
          $("input[name='id']").val(data.id);
          $("input[name='name']").val(data.name);
          $("input[name='email']").val(data.email);
          $("select[name='status']").val(data.status).change().selectric('refresh');
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
      url: "/admin/user/show/student/"+id,
      data: '',
      beforeSend: function() {
        $.LoadingOverlay('show')
      },
      success: function (response) {
        $.LoadingOverlay('hide')
        if (response.data) {
          var data = response.data;
          $("input[name='id']").val(data.id);
          $("input[name='name']").val(data.student.nama);
          $("input[name='nim']").val(data.student.nim);
          $("input[name='tempat']").val(data.student.tempat_lahir);
          $("input[name='tgl']").val(data.student.tgl_lahir);
          $("input[name='email']").val(data.email);
          $("input[name='no_hp']").val(data.student.no_hp);
          $("select[name='jk']").val(data.student.jk).change().selectric('refresh')
          $("select[name='status']").val(data.status).change().selectric('refresh')
          $("select[name='major']").val(data.student.major_id).change().selectric('refresh')
          $("#studentModalEdit").modal("show");
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        $.LoadingOverlay('hide')
        console.log(xhr.responseText)
      }
    });
  });

  $('.table tbody').on( 'click', 'button.teacher-edit', function () {
    var id = $(this).data('id');
    $.ajax({
      type: "get",
      url: "/admin/user/show/teacher/"+id,
      data: '',
      beforeSend: function() {
        $.LoadingOverlay('show')
      },
      success: function (response) {
        $.LoadingOverlay('hide')
        if (response.data) {
          var data = response.data;
          $("input[name='id']").val(data.id);
          $("input[name='name']").val(data.teacher.nama);
          $("input[name='email']").val(data.email);
          $("select[name='status']").val(data.status).change().selectric('refresh')
          $("input[name='nidn']").val(data.teacher.nidn);
          $("input[name='tempat']").val(data.teacher.tempat_lahir);
          $("input[name='tgl']").val(data.teacher.tgl_lahir);
          $("input[name='no_hp']").val(data.teacher.no_hp);
          $("select[name='jk']").val(data.teacher.jk).change().selectric('refresh')
          $("select[name='major']").val(data.teacher.major_id).change().selectric('refresh')
          $("#teacherModalEdit").modal("show");
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        $.LoadingOverlay('hide')
        console.log(xhr.responseText)
      }
    });
  });

});
