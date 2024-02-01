<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Registrasi | {{ config('app.name') }}</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('vendor/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/stisla/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/stisla/css/components.css') }}">
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
                        <div class="login-brand">
                            <img src="{{ asset('vendor/stisla/img/logo-itsk.png') }}" alt="logo" width="100" class="shadow-light rounded-circle">
                        </div>

                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Registrasi</h4>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('register') }}" autocomplete="off" id="form-register">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="prodi">Angkatan <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm selectric @error('angkatan') is-invalid @enderror" name="angkatan" required>
                                                    <option selected disabled>- pilih -</option>
                                                    @for ($i = date('Y'); $i >= date('Y') - 4; $i--)
                                                        <option value="{{ $i }}" {{ old('angkatan') == $i ? 'selected' : ''}}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                                @error('angkatan')
                                                    <div class="invalid-feedback">
                                                        <ul>
                                                            <li>{{ $message }}</li>
                                                        </ul>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="nim">NIM <span class="text-danger">*</span></label>
                                                <input id="nim" type="text" class="form-control numeric @error('nim') is-invalid @enderror" name="nim" value="{{ old('nim') }}" required>
                                                @error('nim')
                                                    <div class="invalid-feedback">
                                                        <ul>
                                                            <li>{{ $message }}</li>
                                                        </ul>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label for="name">Nama Lengkap</label>
                                            <input id="name" type="text" class="form-control" name="name" readonly>
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="prodi">Prodi</label>
                                            <input id="prodi" type="text" class="form-control" name="prodi" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input id="email" type="email" class="form-control" name="email" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="email">No. HP</label>
                                                <input id="no_hp" type="text" class="form-control" name="no_hp" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary btn-lg btn-block" id="btn-cek">
                                            Cek Data
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-lg btn-block" id="btn-daftar" style="display: none;">
                                            DAFTAR
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="simple-footer">
                            Copyright &copy; {{ date('Y') }}. UPTI ITSK RS dr. Soepraoen
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{ asset('vendor/stisla/js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('vendor/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendor/loading-overlay/dist/loadingoverlay.min.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('vendor/stisla/js/scripts.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $(".numeric").on('input', function(e) {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            });

            $("#btn-cek").on("click", function(e) {
                e.preventDefault();
                
                // check if nim and angkatan is empty
                if ($("input[name=nim]").val() == "" || $("select[name=angkatan]").val() == null) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'NIM dan Angkatan tidak boleh kosong!',
                    })
                    return false;
                }
                
                $.ajax({
                    type: "POST",
                    url: "{{ route('register.check-nim') }}",
                    data: $("#form-register").serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        $.LoadingOverlay("show")
                        $("*").removeClass('is-invalid')
                        $("div.invalid-feedback").find('ul').empty();
                        
                        $("#name").val("")
                        $("#prodi").val("")
                        $("#email").val("")
                        $("#no_hp").val("")
                    },
                    success: function(response) {
                        $.LoadingOverlay("hide")
                        $("#name").val(response.data.nama)
                        $("#prodi").val(response.data.prodi)
                        $("#email").val(response.data.email)
                        $("#no_hp").val(response.data.telepon)
                        
                        $("#btn-cek").hide()
                        $("#btn-daftar").show()
                        
                        // disable input nim and angkatan
                        $("input[name=nim]").prop("readonly", true)
                        $("select[name=angkatan]").prop("readonly", true)
                        
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.meta.message,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        })
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        $.LoadingOverlay('hide')
                        switch (xhr.status) {
                            case 422:
                                let errors = xhr.responseJSON.errors
                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON.message,
                                    'error'
                                )

                                $.each(errors, function(key, value) {
                                    $("input[name=" + key + "]").addClass("is-invalid")
                                    $.each(errors[key], function(ke, val) {
                                        $('<li>' + val + "</li>").appendTo($("div[name=msg_" + key + "]").find('ul'));
                                    })
                                })
                                break;

                            default:
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON.meta.message,
                                })
                                break;
                        }
                    },
                });
            });
        });
    </script>
</body>

</html>
