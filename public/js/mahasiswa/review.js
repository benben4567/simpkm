$(function () {
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
      }
  });

  $('.custom-file-input').on('change',function(){
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);
  })

  $("#modalReview").on('hidden.bs.modal', function () {
    $("#form-review").trigger("reset");
    $('.custom-file-label').html('');
  });

  // Submit Form Review
  $("#form-review").submit(function (e) {
    e.preventDefault();

    var formData = new FormData($("#form-review")[0]);

    $.ajax({
      url: "/student/usulan/review",
      type: "POST",
      data : formData,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $.LoadingOverlay('show');
      },
      success: function (response) {
        $.LoadingOverlay('hide');
        if (response.meta.status == 'success') {
          Swal.fire({
            title: 'Berhasil!',
            text: "Data berhasil disimpan.",
            icon: 'success',
            confirmButtonColor: '#3085d6',
            allowOutsideClick: false,
          }).then((result) => {
            location.reload();
          })
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        $.LoadingOverlay('hide');
        console.log(jqXHR);
        Swal.fire('Oops!', 'Terjadi Kesalahan Server', 'error');
      }
    });
  });


});
