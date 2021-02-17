<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

      <!-- Icon Favicon -->
      <link rel="shorcut icon" href="favicon.ico" type="image/x-icon">

      <!-- My CSS -->
      <link rel="stylesheet" href="{{ asset('css/landing.css') }}">

      <title>SIM PKM</title>
    </head>
    <body>
      <!-- Navbar -->
      <div class="container my-5">
          <nav class="navbar navbar-expand-lg navbar-light bg-light custom-nav bg-transparent">
              <a class="navbar-brand" href="#">
                  <img src="{{ asset('img/logo.png') }}" alt="Logo Navbar">
                  <Span>SIM PKM</Span>
              </a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                  aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                  <ul class="navbar-nav">
                      <li class="nav-item align-self-center">
                          <a class="nav-link" href="{{ route('panduan') }}">Panduan</a>
                      </li>
                      @auth
                        <li class="nav-item align-self-center">
                            <a class="nav-link" href="{{ route('home') }}">Dashboard</a>
                        </li>
                      @else
                        <li class="nav-item align-self-center">
                            <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary Sign-up-button" href="{{ route('register') }}">Daftar</a>
                        </li>
                      @endauth
                  </ul>
              </div>
          </nav>
      </div>

      <!-- hero Section -->
      <div class="container">
          <div class="row custom-section">
              <div class="col-12 col-lg-4">
                  <h3>Sistem Informasi Program Kreatifitas Mahasiswa</h3>
                  <p>Di website Sistem Informasi PKM ini kamu dapat mengusulkan dan mengelola Program Kreatifitas Mahasiswa yang pernah kamu ikuti selama berkuliah di Institut Teknologi, Sains, dan Kesehatan RS dr. Soepraoen</p>
                  <a href="{{ route('login') }}" class="button-getStarted mr-2">Masuk</a>
              </div>
          </div>
      </div>

      <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    </body>
</html>
