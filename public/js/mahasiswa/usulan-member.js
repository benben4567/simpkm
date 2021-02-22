$(document).ready(function () {
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
      }
  });

  var table = $("#table").DataTable({
    dom: "ftip",
    ordering: false,
    pageLength: 5
  })

  $('#table tbody').on('click', '.btn-add', function () {
    var proposal = $(this).data('proposal');
    var student = $(this).data('student');
    $.ajax({
      type: "POST",
      url: "/student/usulan/member/add",
      data: {proposal: proposal, student: student},
      beforeSend: function() {
        $.LoadingOverlay('show');
      },
      success: function (response) {
        $.LoadingOverlay('hide')
        $('#modalTambah').modal("hide")
        if (response.success) {
          Swal.fire({
            title: 'Berhasil!',
            text: "Anggota telah ditambahkan.",
            icon: 'success',
            allowOutsideClick: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
          }).then((result) => {
            location.reload()
          })
        } else {
          Swal.fire({
            title: 'Gagal!',
            text: "Anggota gagal ditambahkan.",
            icon: 'error',
            allowOutsideClick: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
          })
        }
      },
    });
  });

  $('.btn-remove').on('click', function () {
    var proposal = $(this).data('proposal');
    var student = $(this).data('student');
    Swal.fire({
      title: 'Anda yakin?',
      text: "Mahasiswa akan dihapus dari anggota.",
      icon: 'warning',
      allowOutsideClick: false,
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Batal',
      confirmButtonText: 'OK'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "POST",
          url: "/student/usulan/member/remove",
          data: {proposal: proposal, student: student},
          beforeSend: function() {
            $.LoadingOverlay('show');
          },
          success: function (response) {
            $.LoadingOverlay('hide')
            $('#modalTambah').modal("hide")
            if (response.success) {
              Swal.fire({
                title: 'Berhasil!',
                text: "Anggota telah dihapus.",
                icon: 'success',
                allowOutsideClick: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
              }).then((result) => {
                location.reload()
              })
            } else {
              Swal.fire({
                title: 'Gagal!',
                text: "Anggota gagal dihapus.",
                icon: 'error',
                allowOutsideClick: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
              })
            }
          }
        });
      }
    })
  });

});
