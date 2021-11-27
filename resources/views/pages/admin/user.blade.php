@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Data User</h1>
      </div>
    </section>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <a class="nav-item nav-link active" id="nav-admin-tab" data-toggle="tab" href="#nav-admin" role="tab" aria-controls="nav-admin" aria-selected="true">Admin</a>
                  <a class="nav-item nav-link" id="nav-mahasiswa-tab" data-toggle="tab" href="#nav-mahasiswa" role="tab" aria-controls="nav-mahasiswa" aria-selected="false">Mahasiswa</a>
                  <a class="nav-item nav-link" id="nav-dosen-tab" data-toggle="tab" href="#nav-dosen" role="tab" aria-controls="nav-dosen" aria-selected="false">Dosen</a>
                </div>
              </nav>
              <div class="row">
                <div class="col-lg-12">
                  <div class="tab-content" id="nav-tabContent">
                    <!-- Admin -->
                    <div class="tab-pane fade show active" id="nav-admin" role="tabpanel" aria-labelledby="nav-admin-tab">
                      <div class="mb-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#adminModal"><i class="fas fa-plus"></i> Baru</button>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-striped table-md" id="table-admin">
                          <thead>
                            <tr class=text-center>
                              <th>No</th>
                              <th>Nama</th>
                              <th>Email</th>
                              <th>Status</th>
                              <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <!-- Mahasiswa -->
                    <div class="tab-pane fade" id="nav-mahasiswa" role="tabpanel" aria-labelledby="nav-mahasiswa-tab">
                      <div class="table-responsive">
                        <table class="table table-striped table-md" id="table-student" class="width: 100%;">
                          <thead>
                            <tr class=text-center>
                              <th>No</th>
                              <th>Nama</th>
                              <th>NIM</th>
                              <th>Email</th>
                              <th>Status</th>
                              <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <!-- Dosen -->
                    <div class="tab-pane fade" id="nav-dosen" role="tabpanel" aria-labelledby="nav-dosen-tab">
                      <div class="table-responsive">
                        <table class="table table-striped table-md" id="table-teacher">
                          <thead>
                            <tr class=text-center>
                              <th>No</th>
                              <th>Nama</th>
                              <th>Email</th>
                              <th>Status</th>
                              <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Admin -->
    <div class="modal fade" id="adminModal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Admin</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{ route('user.store', ['role' => 'admin']) }}" method="post" autocomplete="off" id="form-admin">
          <div class="modal-body">
              @csrf
              <div class="form-group">
                <label for="">Nama</label>
                <input type="text" class="form-control" name="name">
                <div class="invalid-feedback" name="msg_name"><ul></ul></div>
              </div>
              <div class="form-group">
                <label for="">Email</label>
                <input type="email" class="form-control" name="email">
                <div class="invalid-feedback" name="msg_email"><ul></ul></div>
              </div>
              <div class="form-group">
                <label for="">Password</label>
                <input type="password" class="form-control" name="password">
                <div class="invalid-feedback" name="msg_password"><ul></ul></div>
              </div>
              <div class="form-group">
                <label for="">Ulangi Password</label>
                <input type="password" class="form-control" name="password_confirmation">
                <div class="invalid-feedback" name="msg_password_confirmation"><ul></ul></div>
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

    <!-- Admin Edit-->
    <div class="modal fade" id="adminModalEdit" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Admin</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="" method="post" autocomplete="off" id="form-admin-edit">
          <div class="modal-body">
              @csrf
              <input type="text" name="id" hidden>
              <div class="form-group">
                <label for="">Nama</label>
                <input type="text" class="form-control" name="name" disabled>
              </div>
              <div class="form-group">
                <label for="">Email</label>
                <input type="email" class="form-control" name="email" disabled>
              </div>
              <div class="form-group">
                <label for="">Status</label>
                <select class="form-control selectric" name="status">
                  <option value="aktif">Aktif</option>
                  <option value="nonaktif">Nonaktif</option>
                </select>
              </div>
              <div class="form-group">
                <label for="">Password Baru (opsional)</label>
                <input type="password" class="form-control" name="password" >
                <div class="invalid-feedback" name="msg_password_edit"><ul></ul></div>
              </div>
              <div class="form-group">
                <label for="">Ulangi Password (opsional)</label>
                <input type="password" class="form-control" name="password_confirmation" >
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

    <!-- Student Edit-->
    <div class="modal fade" id="studentModalEdit" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Mahasiswa</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="" method="post" autocomplete="off" id="form-student-edit">
          <div class="modal-body">
              @csrf
              <input type="hidden" name="id" value="">
              <div class="row">
                <div class="col-lg-6 border-right">
                  <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" class="form-control" name="name"  id="nama">
                    <div class="invalid-feedback" name="msg_name"><ul></ul></div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="nim">NIM</label>
                        <input type="text" class="form-control" name="nim" id="nim">
                        <div class="invalid-feedback" name="msg_nim"><ul></ul></div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="prodi">Prodi</label>
                        <select class="form-control form-control-sm selectric" name="major" id="prodi">
                          <option selected disabled>- pilih -</option>
                          @foreach($majors as $major)
                          <option value="{{ $major->id }}">{{ $major->full_name }}</option>
                          @endforeach
                        </select>
                        <div class="invalid-feedback" name="msg_major"><ul></ul></div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">Tempat</label>
                        <input type="text" class="form-control" name="tempat" id="tempat">
                        <div class="invalid-feedback" name="msg_tempat"><ul></ul></div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="tgl_lahir">Tanggal Lahir</label>
                        <input type="text" class="form-control datepicker" name="tgl" id="tgl">
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
                          <option value="laki">Laki</option>
                          <option value="perempuan">Perempuan</option>
                        </select>
                        <div class="invalid-feedback" name="msg_jk"><ul></ul></div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="no_hp">No. HP</label>
                        <input type="text" class="form-control" name="no_hp" id="no_hp">
                        <div class="invalid-feedback" name="msg_no_hp"><ul></ul></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                    <div class="invalid-feedback" name="msg_email"><ul></ul></div>
                  </div>
                  <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control selectric" name="status" id="student-status" required>
                      <option value="aktif">Aktif</option>
                      <option value="nonaktif">Nonaktif</option>
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
                    <div class="invalid-feedback" name="msg_password"><ul></ul></div>
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

    <!-- Teacher Edit-->
    <div class="modal fade" id="teacherModalEdit" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Dosen</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="" method="post" autocomplete="off" id="form-teacher-edit">
          <div class="modal-body">
              @csrf
              <input type="hidden" name="id" value="">
              <div class="row">
                <div class="col-lg-6 border-right">
                  <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                    <div class="invalid-feedback" name="msg_name"><ul></ul></div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="nidn">NIDN/NIDK</label>
                        <input type="text" class="form-control" name="nidn" id="nidn" required>
                        <div class="invalid-feedback" name="msg_nidn"><ul></ul></div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="prodi">Prodi</label>
                        <select class="form-control form-control-sm selectric" name="major" id="prodi" required>
                          <option selected disabled>- pilih -</option>
                          @foreach($majors as $major)
                          <option value="{{ $major->id }}">{{ $major->full_name }}</option>
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
                        <input type="text" class="form-control" name="tempat" id="tempat" required>
                        <div class="invalid-feedback" name="msg_tempat"><ul></ul></div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="tgl_lahir">Tanggal Lahir</label>
                        <input type="text" class="form-control datepicker" name="tgl" id="tgl" required>
                        <div class="invalid-feedback" name="msg_tgl"><ul></ul></div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="jk">JK</label>
                        <select class="form-control selectric" name="jk" id="jk" required>
                          <option selected disabled>- pilih -</option>
                          <option value="laki">Laki</option>
                          <option value="perempuan">Perempuan</option>
                        </select>
                        <div class="invalid-feedback" name="msg_jk"><ul></ul></div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="no_hp">No. HP</label>
                        <input type="text" class="form-control" name="no_hp" id="no_hp" required>
                        <div class="invalid-feedback" name="msg_no_hp"><ul></ul></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                    <div class="invalid-feedback" name="msg_email"><ul></ul></div>
                  </div>
                  <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control selectric" name="status" id="teacher-status" required>
                      <option value="aktif">Aktif</option>
                      <option value="nonaktif">Nonaktif</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                    <div class="invalid-feedback" name="msg_password"><ul></ul></div>
                  </div>
                  <div class="form-group">
                    <label for="password_confirmation">Ulangi Password</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
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

    <!-- Import Student -->
    <div class="modal fade" id="modalImportStudent" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Import User Mahasiswa</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{ route('user.import') }}" method="post" autocomplete="off" id="form-import">
            @csrf
            <div class="modal-body">
                @csrf
                <p>Download <a href="{{asset('template/template_mahasiswa.xlsx')}}" target="_blank" rel="noopener noreferrer">Template Excel</a></p>
                <input type="text" class="d-none" name="role" value="student">
                <div class="form-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input form-control" accept=".xls,.xlsx" id="customFile" name="file">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                    <div class="invalid-feedback" name="msg_file"><ul></ul></div>
                  </div>
                  <small class="text-muted">Filetype: xls, xlsx | Max: 2Mb</small>
                </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Import</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Import Teacher -->
    <div class="modal fade" id="modalImportTeacher" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Import User Dosen</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{ route('user.import') }}" method="post" autocomplete="off" id="form-import">
            @csrf
            <div class="modal-body">
                @csrf
                <p>Download <a href="{{asset('template/template_dosen.xlsx')}}" target="_blank" rel="noopener noreferrer">Template Excel</a></p>
                <input type="text" class="d-none" name="role" value="teacher">
                <div class="form-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input form-control" accept=".xls,.xlsx" id="customFile" name="file">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                    <div class="invalid-feedback" name="msg_file"><ul></ul></div>
                  </div>
                  <small class="text-muted">Filetype: xls, xlsx | Max: 2Mb</small>
                </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Import</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal Mahasiswa SIM -->
    <div class="modal fade" id="mahasiswaSimModal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Akun SIM Belmawa</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="" method="post" autocomplete="off" id="form-update-sim">
            <div class="modal-body">
              <input type="hidden" name="id">
              <div class="form-group">
                <label for="">Username</label>
                <input type="text" class="form-control" name="username_sim" required>
              </div>
              <div class="form-group">
                <label for="">Password</label>
                <input type="text" class="form-control" name="password_sim" required>
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

  </div>
@endsection

@push('lib-js')
  {{-- Datatables --}}
  <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/buttons-1.6.5/js/dataTables.buttons.min.js') }}"></script>
  {{-- Selectric --}}
  <script src="{{ asset('vendor/selectric/public/jquery.selectric.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
@endpush

@push('page-js')
  <script src="{{ asset('js/admin/user2.js') }}"></script>
@endpush

@push('css')
  {{-- Datatables --}}
  <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/datatables/buttons-1.6.5/css/buttons.dataTables.min.css') }}"></script>
  {{-- Selectric --}}
  <link rel="stylesheet" href="{{ asset('vendor/selectric/public/selectric.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endpush
