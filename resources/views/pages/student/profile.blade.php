@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Data Diri</h1>
      </div>
    </section>
    <div class="section-body">
      <div class="row">
        <div class="col-lg-6 col-md-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">Data Diri</h4>
            </div>
            <form action="" method="post" autocomplete="off" id="form-student">
            <div class="card-body">
                @csrf
                @method('put')
                <input type="hidden" name="id" value="{{ $student->id }}">
                <div class="form-group">
                  <label for="nama">Nama</label>
                  <input type="text" class="form-control" name="nama" value="{{ old('nama', $student->nama) }}" id="nama">
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="nim">NIM</label>
                      <input type="text" class="form-control" name="nim" value="{{ $student->nim }}" id="nim" disabled>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="prodi">Prodi</label>
                      <select class="form-control form-control-sm selectric" name="major" id="prodi">
                        <option selected disabled>- pilih -</option>
                        @foreach($majors as $major)
                        <option value="{{ $major->id }}" {{ old('major', $student->major_id) == $major->id ? 'selected' : ''}}>{{ $major->full_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">Tempat</label>
                      <input type="text" class="form-control" name="tempat" value="{{ old('tempat', $student->tempat_lahir) }}" id="tempat">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="tgl_lahir">Tanggal Lahir</label>
                      <input type="text" class="form-control datepicker" name="tgl" value="{{ old('tgl', $student->tgl_lahir) }}" id="tgl">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="jk">JK</label>
                      <select class="form-control selectric" name="jk" id="jk">
                        <option selected disabled>- pilih -</option>
                        <option value="laki" {{ old('jk', $student->jk) == 'laki' ? 'selected' : '' }}>Laki</option>
                        <option value="perempuan" {{ old('jk', $student->jk) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="no_hp">No. HP</label>
                      <input type="text" class="form-control" name="no_hp" value="{{ old('no_hp', $student->no_hp) }}" id="no_hp">
                    </div>
                  </div>
                </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
          </div>
        </div>
        <div class="col-lg-6 col-md-12">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Ganti Password</h4>
                </div>
                <div class="card-body">
                  <form action="" method="post" autocomplete="off" id="update-password">
                    @csrf
                    @method('put')
                    <input type="hidden" name="id" value="{{ $student->user->id }}">
                    <div class="form-group">
                      <label for="">Password Baru</label>
                      <input type="password" class="form-control" name="password">
                      <div class="invalid-feedback" name="msg_password"><ul></ul></div>
                    </div>
                    <div class="form-group">
                      <label for="">Ulangi Password Baru</label>
                      <input type="password" class="form-control" name="password_confirmation">
                      <div class="invalid-feedback" name="msg_password_confirmation"><ul></ul></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Akun SIM Belmawa</h4>
                </div>
                <div class="card-body">
                  <p class="text-center">
                    <a href="https://simbelmawa.kemdikbud.go.id/Login.aspx" target="_blank">https://simbelmawa.kemdikbud.go.id/Login.aspx</a>
                    <br>
                    <a name="" id="" class="btn btn-sm btn-danger" href="https://drive.google.com/file/d/1WpdOFAGbSuowQKzWI9joLh8XAJB4Rnwi/view" role="button" target="_blank"><i class="fas fa-file-pdf"></i> Panduan</a>
                  </p>
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
  <script src="{{ asset('vendor/selectric/public/jquery.selectric.min.js') }}"></script>
@endpush

@push('page-js')
  <script src="{{ asset('js/mahasiswa/profile2.js') }}"></script>
@endpush

@push('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="{{ asset('vendor/selectric/public/selectric.css')}}">
@endpush
