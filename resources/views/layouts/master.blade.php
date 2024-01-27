<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIM PKM | ITSK RS dr. Soepraoen</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}">

    <!-- CSS Libraries -->
    @stack('css')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/stisla/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/stisla/css/components.css') }}">
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                    </ul>
                </form>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="{{ asset('vendor/stisla/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">Hai, {{ first_name(Auth::user()->name) }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-title">Menu</div>
                            @if (Auth::user()->role != 'admin')
                                <a href="{{ Auth::user()->role . '/profile' }}" class="dropdown-item has-icon">
                                    <i class="far fa-user"></i> Profil
                                </a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item has-icon" data-toggle="modal" data-target="#modal-password">
                                <i class="fas fa-lock"></i> Ubah Password
                            </a>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="#">SIMPKM</a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="#">SIMPKM</a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="{{ set_active('home') }}"><a href="{{ route('home') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a></li>
                        {{-- Admin --}}
                        @if (Auth::user()->role == 'admin')
                            @include('includes.admin_sidebar')
                        @endif
                        {{-- Mahasiswa --}}
                        @if (Auth::user()->role == 'student')
                            @include('includes.student_sidebar')
                        @endif
                        {{-- Dosen --}}
                        @if (Auth::user()->role == 'teacher')
                            @include('includes.teacher_sidebar')
                        @endif
                    </ul>
                </aside>
            </div>

            <!-- Main Content -->
            @yield('content')
            
            {{-- Modal Change Password --}}
            <div class="modal fade" id="modal-password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ubah Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('change-password') }}" method="post" id="form-password">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="">Password Lama <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="old_password" required>
                                            <div class="invalid-feedback" name="msg_old_password">
                                                <ul></ul>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Password Baru <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password" required>
                                            <small class="text-muted">Min. 8 Karakter</small>
                                            <div class="invalid-feedback" name="msg_password">
                                                <ul></ul>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Ulangi Password Baru <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password_confirmation" required>
                                            <small class="text-muted">Min. 8 Karakter</small>
                                            <div class="invalid-feedback" name="msg_password_confirmation">
                                                <ul></ul>
                                            </div>
                                        </div>
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" onclick="togglePasswordVisibilty()" id="exampleCheck1">
                                            <label class="form-check-label" for="exampleCheck1" >Tampilkan Sandi</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; {{ date("Y") }} <div class="bullet"></div> UPTI ITSK RS dr. Soepraoen
                </div>
                <div class="footer-right">
                    2.0.0
                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('vendor/jquery/jquery-3.5.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendor/loading-overlay/dist/loadingoverlay.min.js') }}"></script>
    <script src="{{ asset('vendor/stisla/js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    @stack('lib-js')

    <!-- Template JS File -->
    <script src="{{ asset('vendor/stisla/js/scripts.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        $(document).ready(function () {
            // clear input field when modal is closed
            $("#modal-password").on('hidden.bs.modal', function () {
                $("input").val('');
                $("input").removeClass("is-invalid");
                $("div[name^=msg_]").find('ul').empty();
            });
            
            // submit form change password
            $("#form-password").submit(function (e) { 
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function () {
                        $.LoadingOverlay("show");
                        // remove all error message
                        $("input").removeClass("is-invalid");
                        $("div[name^=msg_]").find('ul').empty();
                    },
                    success: function (response) {
                        $.LoadingOverlay("hide");
                        $("#modal-password").modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.meta.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $.LoadingOverlay("hide");
                        if (xhr.status == 422) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Silahkan periksa kembali form anda',
                            })
                            
                            $.each(xhr.responseJSON.data, function (key, value) {
                                $("input[name=" + key + "]").addClass("is-invalid")
                                $.each(xhr.responseJSON.data[key], function (ke, val) {
                                    $('<li>' + val + "</li>").appendTo($("div[name=msg_" + key + "]").find('ul'));
                                })
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.meta.message,
                            })
                        }
                    }
                });
            });
        });
        function togglePasswordVisibilty() {
            // get checkbox status using jquery
            var isChecked = $("#exampleCheck1").prop('checked');
            // get all input field of password using jquery
            var old_password = $("input[name='old_password']");
            var password = $("input[name='password']");
            var password_confirmation = $("input[name='password_confirmation']");
            
            // check if checkbox is checked
            if (isChecked) {
                // change input type to text
                old_password.attr('type', 'text');
                password.attr('type', 'text');
                password_confirmation.attr('type', 'text');
            } else {
                // change input type to password
                old_password.attr('type', 'password');
                password.attr('type', 'password');
                password_confirmation.attr('type', 'password');
            }
        }
    </script>
    @stack('page-js')
</body>

</html>
