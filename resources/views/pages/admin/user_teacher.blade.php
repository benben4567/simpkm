@extends('layouts.master')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Dosen</h1>
            </div>
        </section>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form action="{{ route('teacher.store') }}" method="post" autocomplete="off" id="form-teacher">
                            <div class="card-body">
                                <div class="alert alert-info" role="alert">
                                    <strong>Perhatian!</strong> Akun dosen akan dibuat secara otomatis dengan username dan password berupa NIDN/NIDK.
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" required>
                                            <div class="invalid-feedback" name="msg_name">
                                                <ul></ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="nidn">NIDN/NIDK <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control numeric" name="nidn" required>
                                                    <div class="invalid-feedback" name="msg_nidn">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="prodi">Prodi <span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm selectric" name="major" required>
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
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="no_hp">No. HP <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control numeric" name="no_hp" required>
                                                    <div class="invalid-feedback" name="msg_no_hp">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="email">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="email" required>
                                                    <div class="invalid-feedback" name="msg_email">
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
    <script src="{{ asset('js/admin/user_teacher.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    {{-- Selectric --}}
    <link rel="stylesheet" href="{{ asset('vendor/selectric/public/selectric.css') }}">
@endpush
