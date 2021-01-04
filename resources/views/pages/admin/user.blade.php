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
                        @foreach($admins as $admin)
                        <tr class="text-center">
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $admin->name }}</td>
                          <td>{{ $admin->email }}</td>
                          <td><div class="badge {{ $admin->status == 'aktif' ? 'badge-success' : 'badge-danger' }}">{{ strtoupper($admin->status) }}</div></td>
                          <td><button type="button" class="btn btn-sm btn-icon btn-primary admin-edit" data-id="{{ $admin->id }}"><i class="fas fa-eye"></i></button></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- Mahasiswa -->
                <div class="tab-pane fade" id="nav-mahasiswa" role="tabpanel" aria-labelledby="nav-mahasiswa-tab">
                  <div class="mb-3">
                    <a class="btn btn-primary" href="{{ route('user.create', ['role' => 'student']) }}" role="button"><i class="fas fa-plus"></i> Baru</a>
                    <button type="button" class="btn btn-success float-right"><i class="fas fa-file-excel"></i> Import</button>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-striped table-md" id="table-student">
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
                        @foreach($students as $student)
                        <tr class="text-center">
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $student->name }}</td>
                          <td>{{ $student->email }}</td>
                          <td><div class="badge {{ $student->status == 'aktif' ? 'badge-success' : 'badge-danger' }}">{{ strtoupper($student->status) }}</div></td>
                          <td>
                            <button type="button" class="btn btn-sm btn-primary student-edit" data-id="{{ $student->id }}"><i class="fas fa-eye"></i></button>
                            <button type="button" class="btn btn-sm btn-warning" data-id="{{ $student->id }}" data-toggle="modal" data-target="#mahasiswaSimModal"><i class="fas fa-key"></i></button>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- Dosen -->
                <div class="tab-pane fade" id="nav-dosen" role="tabpanel" aria-labelledby="nav-dosen-tab">
                  <div class="mb-3">
                    <a class="btn btn-primary" href="{{ route('user.create', ['role' => 'teacher']) }}" role="button"><i class="fas fa-plus"></i> Baru</a>
                  </div>
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
                        @foreach($teachers as $teacher)
                        <tr class="text-center">
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $teacher->name }}</td>
                          <td>{{ $teacher->email }}</td>
                          <td><div class="badge {{ $teacher->status == 'aktif' ? 'badge-success' : 'badge-danger' }}">{{ strtoupper($teacher->status) }}</div></td>
                          <td>
                            {{-- <button type="button" class="btn btn-sm btn-primary" data-id="{{ $teacher->id }}"><i class="fas fa-eye"></i></button> --}}
                            <a class="btn btn-sm btn-icon btn-primary" href="{{ route('user.show', ['id' => $teacher->id]) }}"><i class="fas fa-eye"></i></a>
                          </td>
                        </tr>
                        @endforeach
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
      <div class="modal-dialog" role="document">
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
          <div class="modal-body">
            <form action="" method="post" autocomplete="off">
              <div class="form-group">
                <label for="">Username</label>
                <input type="text" class="form-control" name="username_sim">
              </div>
              <div class="form-group">
                <label for="">Password</label>
                <input type="password" class="form-control" name="password_sim">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection

@push('lib-js')
  {{-- Datatables --}}
  <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
  {{-- Selectric --}}
  <script src="{{ asset('vendor/selectric/public/jquery.selectric.min.js') }}"></script>
@endpush

@push('page-js')
  <script src="{{ asset('js/admin/user.js') }}"></script>
@endpush

@push('css')
  {{-- Datatables --}}
  <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">
  {{-- Selectric --}}
  <link rel="stylesheet" href="{{ asset('vendor/selectric/public/selectric.css') }}">
@endpush
