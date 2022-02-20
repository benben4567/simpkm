@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Usulan Proposal</h1>
      </div>
      <div class="section-body">
        <div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="card h-100">
              <div class="card-header">
                <h4 class="card-title">Detail Proposal</h4>
              </div>
              <div class="card-body">
                <form action="{{ route('proposal.update') }}" method="post" autocomplete="off" enctype="multipart/form-data" id="edit-proposal">
                  @csrf
                  @method('put')
                  <input type="text" name="id" value="{{ $proposal->id }}" hidden>
                  <div class="row">
                    <div class="col-md-12 col-lg-6">
                      <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="text" class="form-control" name="tahun" id="tahun" value="{{ $proposal->period->tahun }}" disabled>
                      </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                      <div class="form-group">
                        <label for="">Skema PKM</label>
                        <input type="text" class="form-control" value="{{ $proposal->skema }}" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 col-lg-6">
                      <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" class="form-control" value="Ketua Kelompok" id="jabatan" disabled>
                      </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                      <div class="form-group">
                        <label for="dosen">Dosen Pendamping</label>
                          <input type="text" class="form-control" value="{{ $pembimbing->nama }}" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="judul">Judul</label>
                    <textarea class="form-control @error('judul') is-invalid @enderror" name="judul" id="judul" rows="3" style="height:auto;">{{ $proposal->judul }}</textarea>
                    @error('judul')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>

                  @if ($proposal->status == 'kompilasi')
                    <div class="form-group">
                      <label for="">Upload File Proposal</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input @error('file') is-invalid @enderror" accept="application/pdf" name="file" id="customFile">
                        <label class="custom-file-label" for="customFile"></label>
                        <small id="fileHelpId" class="form-text text-muted">Format file : PDF</small>
                        @error('file')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                    </div>
                  @endif

                  <div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a class="btn btn-secondary ml-2" href="{{ route('proposal.index') }}" role="button">Kembali</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>
@endsection

@push('lib-js')
  {{-- Datatables --}}
  <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
  {{-- Selectric --}}
  <script src="{{ asset('vendor/selectric/public/jquery.selectric.min.js') }}"></script>
@endpush

@push('page-js')
  <script src="{{ asset('js/mahasiswa/usulan-edit.js') }}"></script>
@endpush

@push('css')
  <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">
  {{-- Selectric --}}
  <link rel="stylesheet" href="{{ asset('vendor/selectric/public/selectric.css') }}">
@endpush
