$(document).ready(function () {
  var table = $("#table").DataTable({
    dom: "ftip",
    ordering: false,
    pageLength: 5
  })

  $('input[type="file"]').change(function(e){
    var fileName = e.target.files[0].name;
    $('.custom-file-label').html(fileName);
  });

});
