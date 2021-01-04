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
            <form action="" method="post" autocomplete="off" id="form-teacher-edit">
              @csrf
            <input type="text" name="id" value="{{ $user->id }}" hidden>
            <div class="card-body">
                <div class="row">
                  <div class="col-lg-6 border-right">
                    <div class="form-group">
                      <label for="nama">Nama Lengkap</label>
                      <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}">
                      <div class="invalid-feedback" name="msg_name"><ul></ul></div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="nidn">NIDN</label>
                          <input type="text" class="form-control" name="nidn" id="nidn" value="{{ $user->teacher->nidn }}" disabled>
                          <div class="invalid-feedback" name="msg_nidn"><ul></ul></div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="prodi">Prodi</label>
                          <select class="form-control form-control-sm selectric" name="major" id="prodi">
                            <option selected disabled>- pilih -</option>
                            @foreach($majors as $major)
                            <option value="{{ $major->id }}" {{ ($user->teacher->major_id == $major->id) ? 'selected' : '' }}>{{ $major->full_name }}</option>
                            @endforeach
                          </select>
                          <div class="invalid-feedback" name="msg_major"><ul></ul></div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">Tempat Lahir</label>
                          <input type="text" class="form-control" name="tempat" id="tempat" value="{{ $user->teacher->tempat_lahir }}">
                          <div class="invalid-feedback" name="msg_tempat"><ul></ul></div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="tgl_lahir">Tanggal Lahir</label>
                          <input type="text" class="form-control datepicker" name="tgl" id="tgl" value="{{ $user->teacher->tgl_lahir }}">
                          <div class="invalid-feedback" name="msg_tgl"><ul></ul></div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="jk">JK</label>
                          <select class="form-control selectric" name="jk" id="jk">
                            <option selected disabled>- pilih -</option>
                            <option value="laki" {{ $user->teacher->jk == 'laki' ? 'selected' : '' }}>Laki</option>
                            <option value="perempuan" {{ $user->teacher->jk == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                          </select>
                          <div class="invalid-feedback" name="msg_jk"><ul></ul></div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="no_hp">No. HP</label>
                          <input type="text" class="form-control" name="no_hp" id="no_hp" value="{{ $user->teacher->no_hp }}">
                          <div class="invalid-feedback" name="msg_no_hp"><ul></ul></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" name="email" id="email" value="{{ $user->email }}" disabled>
                      <div class="invalid-feedback" name="msg_email"><ul></ul></div>
                    </div>
                    <div class="form-group">
                      <label for="status">Status</label>
                      <select class="form-control selectric" name="status" id="status">
                        <option value="aktif" {{ $user->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ $user->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="password">Password (opsional)</label>
                      <input type="password" class="form-control" name="password" id="password">
                      <div class="invalid-feedback" name="msg_password"><ul></ul></div>
                    </div>
                    <div class="form-group">
                      <label for="password_confirmation">Ulangi Password (opsional)</label>
                      <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
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
