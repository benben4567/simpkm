@extends('layouts.master')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Mahasiswa</h1>
            </div>
        </section>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form action="{{ route('student.store') }}" method="post" autocomplete="off" id="form-student">
                            <div class="card-body">
                                <div class="alert alert-info" role="alert">
                                    <strong>Perhatian!</strong> Akun mahasiswa akan dibuat secara otomatis dengan username dan password yang sama dengan SIAKAD.
                                </div>
                                <input type="hidden" name="password">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="prodi">Angkatan <span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm selectric" name="angakatan" required>
                                                        <option selected disabled>- pilih -</option>
                                                        @for($i = date('Y'); $i >= date('Y')-4; $i--)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                    <div class="invalid-feedback" name="msg_major">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-8">
                                                        <div class="form-group">
                                                            <label for="nidn">NIM <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control numeric" name="nim" required>
                                                            <div class="invalid-feedback" name="msg_nim">
                                                                <ul></ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-primary form-control" id="btn-check-nim">Cari</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="nama">Nama Lengkap</label>
                                                    <input type="text" class="form-control" name="nama">
                                                    <div class="invalid-feedback" name="msg_nama">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="prodi">Prodi</label>
                                                    <select class="form-control form-control-sm selectric" name="major">
                                                        <option selected disabled>- pilih -</option>
                                                        @foreach ($majors as $major)
                                                            <option value="{{ $major->id }}">{{ $major->full_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" name="msg_major">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="">Tempat Lahir <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="tempat" required>
                                                    <div class="invalid-feedback" name="msg_tempat">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="tgl_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control datepicker" name="tgl" required>
                                                    <div class="invalid-feedback" name="msg_tgl">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="jk">JK <span class="text-danger">*</span></label>
                                                    <select class="form-control selectric" name="jk" required>
                                                        <option selected disabled>- pilih -</option>
                                                        <option value="laki">Laki</option>
                                                        <option value="perempuan">Perempuan</option>
                                                    </select>
                                                    <div class="invalid-feedback" name="msg_jk">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="email">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="email" required>
                                                    <div class="invalid-feedback" name="msg_email">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                            </div><div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="no_hp">No. HP <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control numeric" name="no_hp" required>
                                                    <div class="invalid-feedback" name="msg_no_hp">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a class="btn btn-secondary" href="{{ route('user.index') }}" role="button">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('lib-js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"
        integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
    {{-- Selectric --}}
    <script src="{{ asset('vendor/selectric/public/jquery.selectric.min.js') }}"></script>
@endpush

@push('page-js')
    <script src="{{ asset('js/admin/user_student.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    {{-- Selectric --}}
    <link rel="stylesheet" href="{{ asset('vendor/selectric/public/selectric.css') }}">
@endpush
