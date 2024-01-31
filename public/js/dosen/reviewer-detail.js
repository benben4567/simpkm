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
  $("#form-review").on("submit", function (e) {
    e.preventDefault();

    var formData = new FormData($("#form-review")[0]);

    $.ajax({
      url: "/teacher/review",
      type: "POST",
      data : formData,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $.LoadingOverlay('show');
      },
      success: function (response) {
        $.LoadingOverlay('hide');
        $('#modalReview').modal('hide');
        if (response.meta.status == 'success') {
          Swal.fire({
            title: 'Berhasil!',
            text: "Data berhasil disimpan.",
            icon: 'success',
            confirmButtonColor: '#3085d6',
            allowOutsideClick: false,
          }).then((result) => {
            window.location.reload(true);
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

  // Submit Form Acc
  $("#form-acc").on("submit", function (e) {
    e.preventDefault();

    var formData = new FormData($("#form-acc")[0]);

    Swal.fire({
      title: 'Anda yakin?',
      text: "Proposal yang telah Acc tidak dapat dibatalkan.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Acc!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "/teacher/review-acc",
          type: "POST",
          data : formData,
          processData: false,
          contentType: false,
          beforeSend: function() {
            $.LoadingOverlay('show');
          },
          success: function (response) {
            $.LoadingOverlay('hide');
            $('#modalAcc').modal('hide');
            if (response.meta.status == 'success') {
              Swal.fire({
                title: 'Berhasil!',
                text: "Data berhasil disimpan.",
                icon: 'success',
                confirmButtonColor: '#3085d6',
                allowOutsideClick: false,
              }).then((result) => {
                // hard reload
                window.location.reload(true);
              })
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            $.LoadingOverlay('hide');
            console.log(jqXHR);
            Swal.fire('Oops!', 'Terjadi Kesalahan Server', 'error');
          }
        });
      }
    })

  });

  $('.btn-berita').on('click', function (e) {
    e.preventDefault();
    $("#form-berita").submit();
  });

  $('.btn-form').on('click', function (e) {
    e.preventDefault();
    $("#form-penilaian").submit();
  });

});
