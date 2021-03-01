@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Usulan Proposal</h1>
      </div>
    </section>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body p-2">
              <div class="mb-2">
                <div class="form-group">
                  <select class="form-control selectric" name="tahun" id="tahun">
                    <option selected disabled>Pilih Tahun</option>
                    @foreach($periods as $period)
                      <option value="{{ $period->tahun }}" {{ $loop->first ? 'selected' : '' }}>{{ $period->tahun }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="mb-3">
                <a name="" id="" target="_blank" class="btn btn-primary btn-print" style="display: none;" href="#" role="button"><i class="fas fa-print"></i> Cetak</a>
              </div>
              <div class="table-responsive">
                <table class="table table-striped table-md" id="table" style="width: 100%;">
                  <thead>
                    <tr class=text-center>
                      <th>#</th>
                      <th>Judul</th>
                      <th>Status Review</th>
                      <th>Edit</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($proposals as $proposal)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                          <span style="word-break: normal">{{ $proposal->judul }}</span></br>
                          <strong>
                            {{$proposal->skema}}
                          </strong>
                        </td>
                        <td class="text-center">
                          @if($proposal->status == "kompilasi")
                            <span class="badge badge-primary">Kompilasi</span>
                          @elseif($proposal->status == "proses")
                            <span class="badge badge-warning">Proses</span>
                          @else
                            <span class="badge badge-success">Selesai</span>
                          @endif
                        </td>
                        <td class="text-center">
                          <div class="btn-group">
                            {{-- <button type="button" class="btn btn-icon btn-sm btn-primary btn-show" title="Lihat" data-id="{{ $proposal->id }}"><i class="fas fa-eye"></i></button> --}}
                            <button type="button" class="btn btn-icon btn-sm btn-warning btn-edit" title="Edit" data-id="{{ $proposal->id }}"><i class="fas fa-pencil-alt"></i></button>
                          </div>
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

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Update Usulan PKM</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="" method="post" id="form-update">
          @csrf
          @method('put')
          <input type="text" name="id" id="id-proposal" class="d-none">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="">Pembimbing</label>
                  <select class="form-control selectric" name="pembimbing">
                    <option selected disabled>- pilih -</option>
                    @foreach($teachers as $teacher)
                      <option value="{{ $teacher->id }}">{{ $teacher->nama }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="">Reviewer 1</label>
                  <select class="form-control selectric" name="reviewer_1">
                    <option selected disabled>- pilih -</option>
                    @foreach($teachers as $teacher)
                      <option value="{{ $teacher->id }}">{{ $teacher->nama }}</option>
                    @endforeach
                  </select>
                  <div class="invalid-feedback" name="msg_reviewer_1"><ul></ul></div>
                </div>
                <div class="form-group">
                  <label for="">Reviewer 2</label>
                  <select class="form-control selectric" name="reviewer_2">
                    <option selected disabled>- pilih -</option>
                    @foreach($teachers as $teacher)
                      <option value="{{ $teacher->id }}">{{ $teacher->nama }}</option>
                    @endforeach
                  </select>
                  <div class="invalid-feedback" name="msg_reviewer_2"><ul></ul></div>
                </div>
                <div class="form-group">
                  <label for="">Status</label>
                  <select class="form-control selectric" name="status">
                    <option value="kompilasi">Kompilasi</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                  </select>
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

    <!-- Modal Show -->
    <div class="modal fade" id="modalShow" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Usulan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-12">
                <dl class="row">
                  <dt class="col-sm-3">Skema</dt>
                  <dd class="col-sm-9" id="skema"></dd>

                  <dt class="col-sm-3">Judul</dt>
                  <dd class="col-sm-9"><p id="judul"></p></dd>

                  <dt class="col-sm-3">Pembimbing</dt>
                  <dd class="col-sm-9" id="pembimbing"></dd>

                  <dt class="col-sm-3">Ketua</dt>
                  <dd class="col-sm-9" id="ketua"></dd>

                  <dt class="col-sm-3">Anggota</dt>
                  <dd class="col-sm-9" id="anggota">
                    <ul class="pl-3"></ul>
                  </dd>

                  <dt class="col-sm-3">Reviewer 1</dt>
                  <dd class="col-sm-9" id="reviewer1"></dd>

                  <dt class="col-sm-3">Reviewer 2</dt>
                  <dd class="col-sm-9" id="reviewer2"></dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('lib-js')
  {{-- Datatables --}}
  <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/buttons-1.6.5/js/buttons.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/buttons-1.6.5/js/buttons.print.min.js') }}"></script>
  {{-- Selectric --}}
  <script src="{{ asset('vendor/selectric/public/jquery.selectric.min.js') }}"></script>
@endpush

@push('page-js')
  <script src="{{ asset('js/admin/usulan.js') }}"></script>
@endpush

@push('css')
  {{-- Datatables --}}
  <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/datatables/buttons-1.6.5/css/buttons.bootstrap4.min.css') }}">
  {{-- Selectric --}}
  <link rel="stylesheet" href="{{ asset('vendor/selectric/public/selectric.css') }}">
@endpush
