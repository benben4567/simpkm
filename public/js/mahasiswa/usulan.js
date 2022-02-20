$(document).ready(function () {
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
      }
  });

  var table = $("#table").DataTable({
    dom: "tip",
    ordering: false,
    columnDefs: [
      { "width": "11%", "targets" : [1, 2, 3, 4]},
      { "width": "18%", "targets" : 5},
    ]
  })

  $('#periode').on('change', function () {
    $("#form-periode").submit();
  });

  $('.btn-delete').on('click', function () {
    Swal.fire({
      title: 'Anda yakin?',
      text: "Proposal akan dihapus. Hanya ketua kelompok yang dapat menghapus usulan PKM",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $($(this).closest('form')).submit()
      }
    })
  });

  $('.btn-download').on('click', function () {
    $("input[name='id']").val($(this).data('proposal'));
    $('#modalDownload').modal('show')
  });

  $('.btn-berita').on('click', function (e) {
    e.preventDefault();
    $("#form-berita").submit();
  });

  $('.btn-penilaian').on('click', function (e) {
    e.preventDefault();
    $("#form-penilaian").submit();
  });

  function populateTable(data) {

  }
});
