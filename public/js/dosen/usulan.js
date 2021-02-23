$(document).ready(function () {
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
      }
  });

  var table = $("#table").DataTable({
    dom: "Bftip",
    ordering: false,
    columnDefs: [
      { "width": "30%", "target" : 1},
    ],
    buttons: [
      {
          text: '<i class="fas fa-print"></i> Cetak',
          className: 'btn btn-sm btn-primary',
          action: function ( e, dt, node, config ) {
            if (dt.rows().count()) {
              $("#form-cetak").submit();
            }
          }
      }
    ]
  })

  $('.btn-download').on('click', function () {
    $("input[name='id']").val($(this).data('proposal'));
    $('#modalDownload').modal('show')
  });

  $('#modalShow').on('hidden.bs.modal', function (e) {
    $("#anggota ul").empty();
  })

  $('.btn-show').on('click', function () {
    var id = $(this).data('proposal');
    $.ajax({
      type: "get",
      url: "usulan/show/"+id,
      data: null,
      dataType: "JSON",
      beforeSend: function() {
        $.LoadingOverlay("show")
      },
      success: function (response) {
        // console.log(response)
        $.LoadingOverlay("hide")
        if (response.success) {
          var data = response.data
          var anggota = data.anggota
          $("#skema").html(data.proposal.skema);
          $("#judul").html(data.proposal.judul);
          $("#pembimbing").html(data.pembimbing.nama);
          $("#ketua").html(data.ketua.nama);
          $("#reviewer1").html(data.reviewer1 ? data.reviewer1.nama : '-');
          $("#reviewer2").html(data.reviewer2 ? data.reviewer2.nama : '-');
          $.each(anggota, function(key, value) {
            $("#anggota ul").append('<li>'+ value.nama +'</li>');
          })
        }
      },
      error: function(xhr) {
        $.LoadingOverlay('hide')
        switch (xhr.status) {
          case 404:
            Swal.fire(
              'Oops!',
              'Usulan tidak ditemukan.',
              'error'
            )
            break;
          default:
            break;
        }
      }
    });
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
