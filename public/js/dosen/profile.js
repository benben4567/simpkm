$(document).ready(function () {
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
      }
  });

  $('.datepicker').datepicker()

  // update password
  $('#update-password').submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: "put",
      url: "password-update",
      data: $(this).serialize(),
      dataType: "JSON",
      beforeSend: function() {
        $.LoadingOverlay("show")
        $("*").removeClass('is-invalid')
        $("div.invalid-feedback").find('ul').empty();
      },
      success: function (response) {
        $.LoadingOverlay("hide")
        if (response.success) {
          $('#update-password').trigger('reset')
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
              'Terjadi kesalahan',
              'error'
            )
            break;
          default:
            break;
        }
      }
    });
  });
});
