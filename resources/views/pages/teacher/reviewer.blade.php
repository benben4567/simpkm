@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Review Proposal</h1>
      </div>
      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body p-2">
                <div class="table-responsive">
                  <table class="table table-striped table-md" id="table" style="width: 100%;">
                    <thead>
                      <tr class=text-center>
                        <th>Skema</th>
                        <th>Judul</th>
                        <th>Jabatan</th>
                        <th>Tanggal Diusulkan</th>
                        <th>Status Usulan</th>
                        <th>Proposal</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($teacher->proposals as $proposal)
                      <tr class="text-center">
                        <td>{{ $proposal->skema }}</td>
                        <td>{{ $proposal->judul }}</td>
                        <td>{{ $proposal->pivot->jabatan }}</td>
                        <td>{{ $proposal->created_at->isoFormat('D MMMM Y')}}</td>
                        <td>
                          @if($proposal->status == "kompilasi")
                            <span class="badge badge-primary">Kompilasi</span>
                          @elseif($proposal->status == "proses")
                            <span class="badge badge-warning">Proses Review</span>
                          @else
                            <span class="badge badge-success">Selesai</span>
                          @endif
                        </td>
                        <td><a class="btn btn-sm btn-danger" href="{{ asset('/storage/files/'.$proposal->file) }}" role="button"><i class="fas fa-file-pdf"></i></a></td>
                        <td>
                          <button type="button" data-toggle="tooltip" data-placement="bottom" title="Lihat" class="btn btn-sm btn-primary btn-show" data-proposal="{{ $proposal->id }}"><i class="fas fa-eye"></i></button>
                          <button type="button" data-toggle="tooltip" data-placement="bottom" title="Download" class="btn btn-sm btn-success btn-download" data-proposal="{{ $proposal->id }}"><i class="fas fa-download"></i></button>
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
    </section>

    <!-- Modal Download -->
    <div class="modal fade" id="modalDownload" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Download</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-6">

                <div class="float-right">
                  <form action="{{ route('teacher.proposal.download.berita') }}" id="form-berita" method="get" class="d-none">
                    @csrf
                    <input type="text" name="id">
                  </form>
                  <button type="button" class="btn btn-sq btn-success btn-berita"><span><i class="fa fa-file-alt fa-3x"></i><br> Berita Acara</span></button>
                </div>
              </div>
              <div class="col-6">
                <div class="float-left">
                  <form action="{{ route('teacher.proposal.download.form') }}" id="form-penilaian" method="get" class="d-none">
                    @csrf
                    <input type="text" name="id">
                  </form>
                  <a class="btn btn-sq btn-warning btn-penilaian" href="" ><span><i class="fa fa-copy fa-3x"></i><br> Form Penilaian</span></a>
              </div>
              </div>
            </div>
          </div>
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
@endpush

@push('page-js')
  <script src="{{ asset('js/dosen/usulan.js') }}"></script>
@endpush

@push('css')
  <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">
@endpush
