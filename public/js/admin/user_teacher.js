$(document).ready(function () {
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
      }
  });

  $(".datepicker").datepicker({
    format: "yyyy-mm-dd"
  })

  $("#form-teacher").submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: "post",
      url: "/admin/user/store/teacher",
      data: $(this).serialize(),
      beforeSend: function() {
        $.LoadingOverlay("show")
        $("*").removeClass('is-invalid')
        $("div.invalid-feedback").find('ul').empty();
      },
      success: function (response) {
        $.LoadingOverlay("hide")
        if (response.success) {
          Swal.fire({
            title: 'Berhasil!',
            text: "User dosen sudah disimpan.",
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok'
          }).then((result) => {
            if (result.isConfirmed) {
              window.location = "http://" + window.location.hostname + "/admin/user"
            }
          })
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
              $("[name="+ key +"]").addClass("is-invalid")
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

  $("#form-teacher-edit").submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: "put",
      url: "/admin/user/update/teacher",
      data: $(this).serialize(),
      beforeSend: function() {
        $.LoadingOverlay("show")
        $("*").removeClass('is-invalid')
        $("div.invalid-feedback").find('ul').empty();
      },
      success: function (response) {
        $.LoadingOverlay("hide")
        if (response.success) {
          Swal.fire({
            title: 'Berhasil!',
            text: "User dosen sudah diperbarui.",
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok'
          }).then((result) => {
            if (result.isConfirmed) {
              window.location = "http://" + window.location.hostname + "/admin/user"
            }
          })
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
              $("[name="+ key +"]").addClass("is-invalid")
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
});
