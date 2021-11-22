@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Program Studi</h1>
      </div>
    </section>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-md" id="table">
                  <thead>
                    <tr class=text-center>
                      <th>#</th>
                      <th>Program Studi</th>
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

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ubah Prodi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="" method="post" id="form-update">
          @csrf
          @method('put')
          <input type="text" name="id" id="id-prodi" class="d-none">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="">Jenjang</label>
                  <select class="form-control selectric" name="jenjang" required>
                    <option selected disabled>- pilih -</option>
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="S1">S1</option>
                    <option value="Profesi">Profesi</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="">Nama Prodi</label>
                  <input type="text" class="form-control" name="nama" required>
                  <div class="invalid-feedback" name="msg_nama"><ul></ul></div>
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

    <!-- Modal Create -->
    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Prodi Baru</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="" method="post" id="form-create">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="">Jenjang</label>
                  <select class="form-control selectric is-invalid" name="jenjang" required>
                    <option selected disabled>- pilih -</option>
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="S1">S1</option>
                    <option value="Profesi">Profesi</option>
                  </select>
                  <div class="invalid-feedback" name="msg_jenjang"><ul></ul></div>
                </div>
                <div class="form-group">
                  <label for="">Nama Prodi</label>
                  <input type="text" class="form-control" name="nama" required>
                  <div class="invalid-feedback" name="msg_nama"><ul></ul></div>
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

  </div>
@endsection

@push('lib-js')
  {{-- Datatables --}}
  {{-- <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script> --}}
  <script src="{{ asset('vendor/datatables/DataTables-1.10.23/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/DataTables-1.10.23/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/buttons-1.6.5/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/buttons-1.6.5/js/buttons.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/buttons-1.6.5/js/buttons.print.min.js') }}"></script>
  {{-- Selectric --}}
  <script src="{{ asset('vendor/selectric/public/jquery.selectric.min.js') }}"></script>
@endpush

@push('page-js')
  <script src="{{ asset('js/admin/prodi.js') }}"></script>
@endpush

@push('css')
  {{-- Datatables --}}
  <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/datatables/DataTables-1.10.23/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/datatables/buttons-1.6.5/css/buttons.bootstrap4.min.css') }}">
  {{-- Selectric --}}
  <link rel="stylesheet" href="{{ asset('vendor/selectric/public/selectric.css') }}">
@endpush
