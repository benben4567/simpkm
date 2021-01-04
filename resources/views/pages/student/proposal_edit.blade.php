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
                <form action="" method="post" autocomplete="off">
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
                        <select class="form-control" name="skema" id="skema">
                          <option selected disabled>- pilih -</option>
                          <option value="PKM-P" {{ $proposal->skema == "PKM-P" ? 'selected' : ''}}>PKM-P</option>
                          <option value="PKM-K" {{ $proposal->skema == "PKM-K" ? 'selected' : ''}}>PKM-K</option>
                          <option value="PKM-M" {{ $proposal->skema == "PKM-M" ? 'selected' : ''}}>PKM-M</option>
                          <option value="PKM-T" {{ $proposal->skema == "PKM-T" ? 'selected' : ''}}>PKM-T</option>
                          <option value="PKM-KC" {{ $proposal->skema == "PKM-KC" ? 'selected' : ''}}>PKM-KC</option>
                          <option value="PKM-AI" {{ $proposal->skema == "PKM-AI" ? 'selected' : ''}}>PKM-AI</option>
                          <option value="PKM-GT" {{ $proposal->skema == "PKM-GT" ? 'selected' : ''}}>PKM-GT</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 col-lg-6">
                      <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" class="form-control" name="jabatan" value="Ketua Kelompok" id="jabatan" disabled>
                      </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                      <div class="form-group">
                        <label for="dosen">Dosen Pendamping</label>
                        <select class="form-control" name="dosen" id="dosen">
                          <option selected disabled>- pilih -</option>
                          @foreach($teachers as $teacher)
                          <option value="{{ $teacher->id }}" {{ $teacher->id == $pembimbing->id ? 'selected' : '' }}>{{ $teacher->nama }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="judul">Judul</label>
                    <textarea class="form-control" name="judul" id="judul" rows="3" style="height:auto;">{{ $proposal->judul }}</textarea>
                  </div>
                  <div class="form-group">
                    <label for="">Upload File Proposal</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="customFile">
                      <label class="custom-file-label" for="customFile"></label>
                    <small id="fileHelpId" class="form-text text-muted">Format file : PDF</small>
                    </div>
                  </div>
                  <div>
                    <button type="button" class="btn btn-primary">Simpan</button>
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
  {{-- Vue JS --}}
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
  {{-- Datatables --}}
  <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
@endpush

@push('page-js')
  <script src="{{ asset('js/mahasiswa/usulan-edit.js') }}"></script>
@endpush

@push('css')
  <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">
@endpush
