<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Berita Acara</title>
  <link rel="stylesheet" href="{{ asset('css/flexboxgrid.min.css') }}">
  <style>
    .justify {
      text-align: justify;
      text-justify: inter-word;
    }
  </style>
</head>
<body>
  <div class="row center-xs">
    <div class="col-lg-12">
      <img src="{{ asset('img/kop_itsk.png') }}" style="width:85%; height:auto;" >
    </div>
  </div>
  <div class="row center">
    <div class="col-lg-12" >
      <h4>
        BERITA ACARA DAN REKAPITULASI NILAI</br>
        REVIEW INTERNAL PROPOSAL PKM SKEMA {skema}</br>
        TAHUN {tahun} PENDANAAN TAHUN {tahun}
      </h4>
    </div>
  <div class="row">
    <div class="col-lg-12">
      <p class="">
      Pada hari ini ........... tanggal ..... bulan .............. tahun ............. telah dilaksanakan <i> review internal </i> PKM Skema {skema}
      </p>
    </div>
  </div>
  </div>
</body>
</html>
