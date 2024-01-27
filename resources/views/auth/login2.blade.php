<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Login | {{ config('app.name') }}</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css" integrity="sha512-f8mUMCRNrJxPBDzPJx3n+Y5TC5xp6SmStstEfgsDXZJTcxBakoB5hvPLhAfJKa9rCvH+n3xpJ2vQByxLk4WP2g==" crossorigin="anonymous" />

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('vendor/stisla/css/style.css')}}">
  <link rel="stylesheet" href="{{ asset('vendor/stisla/css/components.css')}}">
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="d-flex flex-wrap align-items-stretch">
        <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
          <div class="p-4 m-3">
            <img src="{{ asset('vendor/stisla/img/logo-itsk.png')}}" alt="logo" width="100" class="mb-3 mt-2">
            <h4 class="text-dark font-weight-normal">Selamat Datang di <span class="font-weight-bold">SIM PKM</span></h4>
            <p class="text-muted">Silahkan login untuk memulai, atau daftar jika belum memiliki akun.</p>
            <form method="POST" action="{{ route('login') }}" novalidate="">
              @csrf

              <div class="form-group">
                <label for="username">Username</label>
                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" tabindex="1" required autofocus autocomplete="username">
                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>

              <div class="form-group">
                <div class="d-block">
                  <label for="password" class="control-label">Password</label>
                </div>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" tabindex="2" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>

              <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me" {{ old('remember') ? 'checked' : '' }}>
                  <label class="custom-control-label" for="remember-me">Ingat Saya</label>
                </div>
              </div>

              <div class="form-group text-right">
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="float-left mt-3">
                  Lupa Password?
                </a>
                @endif
                <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="4">
                  Login
                </button>
              </div>

              @if (Route::has('register'))
              <div class="mt-5 text-center">
                Tidak punya akun? <a href="{{ route('register') }}">Registrasi disini</a>
              </div>
              @endif
            </form>

            <div class="text-center mt-3 text-small">
              Copyright &copy; {{ date('Y')}}. UPTI ITSK RS dr. Soepraoen
            </div>
          </div>
        </div>
        <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 position-relative overlay-gradient-bottom" data-background="{{ asset('vendor/stisla/img/unsplash/login-bg2.jpg')}}">
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="{{ asset('vendor/jquery/jquery-3.5.1.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('vendor/jquery-nicescroll/jquery.nicescroll.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="{{ asset('vendor/stisla/js/stisla.js')}}"></script>

  <!-- JS Libraies -->

  <!-- Template JS File -->
  <script src="{{ asset('vendor/stisla/js/scripts.js')}}"></script>

  <!-- Page Specific JS File -->
</body>
</html>
