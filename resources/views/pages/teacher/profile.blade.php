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
        <div class="col-lg-6 col-md-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">Data Diri</h4>
            </div>
            <form action="" method="post" autocomplete="off" id="form-teacher">
            <div class="card-body">
                @csrf
                <input type="hidden" name="id" value="{{ $teacher->id }}">
                <div class="form-group">
                  <label for="nama">Nama Lengkap</label>
                  <input type="text" class="form-control" name="name" value="{{ old('name', $teacher->nama) }}" id="name">
                  <div class="invalid-feedback" name="msg_name"><ul></ul></div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="nidn">NIDN/NIDK</label>
                      <input type="text" class="form-control" name="nidn" value="{{ $teacher->nidn }}" id="nidn" disabled>
                      <div class="invalid-feedback" name="msg_nidn"><ul></ul></div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="prodi">Prodi</label>
                      <select class="form-control form-control-sm selectric" name="major" id="prodi">
                        <option selected disabled>- pilih -</option>
                        @foreach($majors as $major)
                        <option value="{{ $major->id }}" {{ old('major', $teacher->major_id) == $major->id ? 'selected' : ''}}>{{ $major->full_name }}</option>
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
                      <input type="text" class="form-control" name="tempat" value="{{ old('tempat', $teacher->tempat_lahir) }}" id="tempat">
                      <div class="invalid-feedback" name="msg_tempat"><ul></ul></div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="tgl_lahir">Tanggal Lahir</label>
                      <input type="text" class="form-control datepicker" name="tgl" value="{{ old('tgl', $teacher->tgl_lahir) }}" id="tgl">
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
                        <option value="laki" {{ old('jk', $teacher->jk) == 'laki' ? 'selected' : '' }}>Laki</option>
                        <option value="perempuan" {{ old('jk', $teacher->jk) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                      </select>
                      <div class="invalid-feedback" name="msg_jk"><ul></ul></div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="no_hp">No. HP</label>
                      <input type="text" class="form-control" name="no_hp" value="{{ old('no_hp', $teacher->no_hp) }}" id="no_hp">
                      <div class="invalid-feedback" name="msg_no_hp"><ul></ul></div>
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
        <div class="col-lg-6 col-md-12">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Akun SIM Belmawa</h4>
                </div>
                <div class="card-body">
                  <p class="text-center"><a href="https://simbelmawa.kemdikbud.go.id/Login.aspx" target="_blank">https://simbelmawa.kemdikbud.go.id/Login.aspx</a></p>
                  <dl class="row">
                    <dt class="col-sm-3">Username</dt>
                    <dd class="col-sm-9">{{ $student->username_sim ?? "-" }}</dd>

                    <dt class="col-sm-3">Password</dt>
                    <dd class="col-sm-9">{{ $student->password_sim ?? "-" }}</dd>
                  </dl>
                </div>
              </div>
            </div>
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
  <script src="{{ asset('js/dosen/profile.js') }}"></script>
@endpush

@push('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  {{-- Selectric --}}
  <link rel="stylesheet" href="{{ asset('vendor/selectric/public/selectric.css') }}">
@endpush
