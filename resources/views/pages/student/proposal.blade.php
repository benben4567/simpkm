@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Usulan Proposal</h1>
      </div>
      <div class="section-body">
        @if (session('success'))
        <div class="row">
          <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
              </button>
              <strong>Berhasil!</strong> Usulan PKM telah disimpan, cek status secara berkala.
            </div>
          </div>
        </div>
        @endif
        <div class="row">
          <div class="col-lg-12">
            <div class="alert alert-warning alert-has-icon">
              <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
              <div class="alert-body">
                <div class="alert-title">Perhatian</div>
                Pengusulan PKM <strong>hanya</strong> dilakukan oleh Ketua Kelompok masing-masing PKM. Anggota kelompok cukup sampai melengkapi <strong>Data Diri</strong> saja. Silahkan baca <a class="btn btn-primary btn-sm" href="{{ route('panduan.index') }}" role="button">Panduan</a> pada menu disamping untuk informasi lebih lanjut.
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body p-2">
                <div class="mb-3">
                  <a class="btn btn-primary" href="{{ route('proposal.create') }}" role="button"><i class="fas fa-plus"></i> Usulan Baru</a>
                </div>
                <div class="table-responsive">
                  <table class="table table-striped table-md" id="table" style="width: 100%;">
                    <thead>
                      <tr class=text-center>
                        <th>Skema</th>
                        <th>Judul</th>
                        <th>Jabatan</th>
                        <th>Tanggal Diusulkan</th>
                        <th>Status Usulan</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($student->proposals as $proposal)
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
                        <td>
                          @if($proposal->pivot->jabatan == "Ketua")
                            <a class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="bottom" title="Edit" href="{{ route('proposal.edit', ["id" => $proposal->id]) }}" role="button"><i class="fas fa-pencil-alt"></i></a>
                            <a class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" title="Anggota" href="{{ route('proposal.member', ["id" => $proposal->id]) }}" role="button"><i class="fas fa-users"></i></a>
                          @endif
                          <button type="button" data-toggle="tooltip" data-placement="bottom" title="Download" class="btn btn-sm btn-success btn-download" data-proposal="{{ $proposal->id }}" {{ $proposal->status == 'kompilasi' ? 'disabled' : ''}} ><i class="fas fa-download"></i></button>
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
                  <form action="{{ route('proposal.download.berita') }}" id="form-berita" method="get" class="d-none">
                    @csrf
                    <input type="text" name="id">
                  </form>
                  <button type="button" class="btn btn-sq btn-success btn-berita"><span><i class="fa fa-file-alt fa-3x"></i><br> Berita Acara</span></button>
                </div>
              </div>
              <div class="col-6">
                <div class="float-left">
                  <form action="{{ route('proposal.download.form') }}" id="form-penilaian" method="get" class="d-none">
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

  </div>
@endsection

@push('lib-js')
  {{-- Datatables --}}
  <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
@endpush

@push('page-js')
  <script src="{{ asset('js/mahasiswa/usulan.js') }}"></script>
@endpush

@push('css')
  <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">
@endpush
