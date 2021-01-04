@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Rekapitulasi Usulan</h1>
      </div>
    </section>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body p-4">
              <form action="{{ route('recap.download') }}" method="post">
                @csrf
                <div class="form-group">
                  <label for="">Periode Pembukaan</label>
                  <select class="form-control selectric" name="periode" required>
                    <option value="">- pilih -</option>
                    @foreach($periods as $period)
                      <option value="{{ $period->id }}">{{ $period->tahun }}</option>
                    @endforeach
                  </select>
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-file-excel"></i> Export</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('lib-js')
  {{-- Selectric --}}
  <script src="{{ asset('vendor/selectric/public/jquery.selectric.min.js') }}"></script>
@endpush

@push('page-js')
  <script src="{{ asset('js/admin/rekap.js') }}"></script>
@endpush

@push('css')
  {{-- Datatables --}}
  <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">
  {{-- Selectric --}}
  <link rel="stylesheet" href="{{ asset('vendor/selectric/public/selectric.css') }}">
@endpush
