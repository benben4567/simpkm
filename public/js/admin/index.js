"use strict";

var statistics_chart = document.getElementById("myChart").getContext('2d');

var myChart = new Chart(statistics_chart, {
  type: 'line',
  data: {
    labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    datasets: [{
      label: 'Statistics',
      data: [640, 387, 530, 302, 430, 270, 488],
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
          stepSize: 150
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
});
