@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Usulan Proposal</h1>
      </div>
      <div class="section-body">
        <div class="row">
          <div class="col-12">
          @if($periode->pendaftaran == 'buka')
            <div class="card">
              <form action="{{ route('proposal.store') }}" method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="card-body">
                  @csrf
                  <div class="row">
                    <div class="col-md-12 col-lg-6">
                      <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="text" class="form-control @error('tahun') is-invalid  @enderror" name="tahun" id="tahun" value="{{ $periode->tahun }}" readonly>
                        @error('tahun')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                      <div class="form-group">
                        <label for="">Skema PKM</label>
                        <select class="form-control selectric @error('skema') is-invalid @enderror" name="skema" id="skema">
                          <option selected disabled>- pilih -</option>
                          <option value="PKM-R" {{ old('skema') == 'PKM-R' ? 'selected' : ''}}>PKM-R</option>
                          <option value="PKM-K" {{ old('skema') == 'PKM-K' ? 'selected' : ''}}>PKM-K</option>
                          <option value="PKM-PM" {{ old('skema') == 'PKM-PM' ? 'selected' : ''}}>PKM-PM</option>
                          <option value="PKM-PI" {{ old('skema') == 'PKM-PI' ? 'selected' : ''}}>PKM-PI</option>
                          <option value="PKM-KC" {{ old('skema') == 'PKM-KC' ? 'selected' : ''}}>PKM-KC</option>
                          <option value="PKM-GFK" {{ old('skema') == 'PKM-GFK' ? 'selected' : ''}}>PKM-GFK</option>
                          <option value="PKM-AI" {{ old('skema') == 'PKM-AI' ? 'selected' : ''}}>PKM-AI</option>
                          <option value="PKM-GT" {{ old('skema') == 'PKM-GT' ? 'selected' : ''}}>PKM-GT</option>
                        </select>
                        @error('skema')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 col-lg-6">
                      <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" class="form-control @error('jabatan') is-invalid @enderror" name="jabatan" value="Ketua" id="jabatan" disabled>
                        @error('jabatan')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                      <div class="form-group">
                        <label for="dosen">Dosen Pendamping</label>
                        <select class="form-control selectric" name="dosen" id="dosen">
                          <option selected disabled>- pilih -</option>
                          @foreach($teachers as $teacher)
                          <option value="{{ $teacher->id }}" {{ old('dosen') == $teacher->id ? 'selected' : ''}}>{{ $teacher->nama }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="judul">Judul</label>
                    <textarea class="form-control @error('judul') is-invalid @enderror" name="judul" id="judul" rows="3" style="height:auto;">{{ old('judul') }}</textarea>
                    @error('judul')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="">Upload File Proposal</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input @error('file') is-invalid @enderror" accept="application/pdf" name="file" id="customFile">
                      <label class="custom-file-label" for="customFile"></label>
                      <small id="fileHelpId" class="form-text text-muted">Format file : PDF | Maks. 2MB</small>
                      @error('file')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a class="btn btn-secondary ml-2" href="{{ route('proposal.index') }}" role="button">Kembali</a>
              </div>
              </form>
            </div>
          @else
            @if($periode->tahun <= date("Y"))
              <div class="alert alert-warning alert-has-icon">
                <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                <div class="alert-body">
                  <div class="alert-title">Perhatian</div>
                  <h5> Usulan proposal PKM {{ $periode->tahun }} sudah ditutup. </h5>
                </div>
              </div>
            @else
              <div class="alert alert-info alert-has-icon">
                <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                <div class="alert-body">
                  <div class="alert-title">Informasi</div>
                  <h5> Usulan proposal PKM Tahun {{ $periode->tahun }}  belum dibuka. </h5>
                </div>
              </div>
            @endif
          @endif
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
  <script src="{{ asset('js/mahasiswa/usulan-create.js') }}"></script>
@endpush

@push('css')
  <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">
  {{-- Selectric --}}
  <link rel="stylesheet" href="{{ asset('vendor/selectric/public/selectric.css') }}">
@endpush
