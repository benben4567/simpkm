$(document).ready(function () {

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
      }
  });

  $('.datepicker').datepicker({
    format: "yyyy-mm-dd"
  })

  // update profile
  $("#form-student").submit(function (e) {
    e.preventDefault();
    var values = $(this).serializeArray();
    values.find(function(input) { 
        return input.name == 'no_hp';
    }).value = $('input[name="no_hp"]').cleanVal();;
    
    $.ajax({
      type: "put",
      url: "profile",
      data: values,
      dataType: "JSON",
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
            text: response.msg,
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok'
          }).then((result) => {
            if (result.isConfirmed) {
              location.reload()
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
