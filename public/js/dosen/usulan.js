$(document).ready(function () {
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
      }
  });

  var table = $("#table").DataTable({
    dom: "ftip",
    ordering: false,
    columnDefs: [
      { "width": "30%", "target" : 1},
    ]
  })

  $('.btn-download').on('click', function () {
    $("input[name='id']").val($(this).data('proposal'));
    $('#modalDownload').modal('show')
  });

  $('.btn-show').on('click', function () {
    $("input[name='id']").val($(this).data('proposal'));
    $('#modalShow').modal('show')
  });

  $('.btn-berita').on('click', function (e) {
    e.preventDefault();
    $("#form-berita").submit();
  });

  $('.btn-penilaian').on('click', function (e) {
    e.preventDefault();
    $("#form-penilaian").submit();
  });
});
