"use strict";

var statistics_chart = document.getElementById("myChart").getContext('2d');

$(document).ready(function () {
  $("select[name='periode']").change(function (e) {
    e.preventDefault();
    var tahun = $(this).val();
    $.ajax({
      type: "get",
      url: "/admin/home/recap/"+ tahun,
      data: '',
      success: function (response) {
        if (response.success) {
          $('#table tbody').empty()
          $.each( response.data, function( key, value ) {
            $('#table tbody').append('<tr><td>'+ key +'</td><td class="text-center">'+ value +'</td></tr>');
          });
        }
      }
    });
  });


  chart();
});

function chart() {
  $.ajax({
    type: "get",
    url: "/admin/chart",
    dataType: "json",
    success: function (response) {
      if (response.data) {
        var data = response.data
        var label = []
        var value = []
        $.each(data, function (key, val) {
          label.push(key)
          value.push(val)
        });
      }
      var myChart = new Chart(statistics_chart, {
        type: 'line',
        data: {
          labels: label,
          datasets: [{
            label: 'Jumlah Proposal',
            data: value,
            borderWidth: 5,
            borderColor: '#6777ef',
            backgroundColor: 'transparent',
            pointBackgroundColor: '#fff',
            pointBorderColor: '#6777ef',
            pointRadius: 4
          }]
        },
        options: {
          legend: {
            display: false
          },
          scales: {
            yAxes: [{
              gridLines: {
                display: false,
                drawBorder: false,
              },
              ticks: {
                stepSize: 10
              }
            }],
            xAxes: [{
              gridLines: {
                color: '#fbfbfb',
                lineWidth: 2
              }
            }]
          },
        }
      });
    }
  });
}

