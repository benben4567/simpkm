@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Dashboard</h1>
      </div>
      <div class="section-body">
        <!-- Hero 2-->
        <div class="row">
          <div class="col-12 mb-4">
            <div class="hero bg-primary text-white">
              <div class="hero-inner">
                <h2>Halo, {{ Auth::user()->teacher->nama }}!</h2>
                <p class="lead">Selamat datang di Web Sistem Informasi Program Kreativitas Mahasiswa ITSK RS dr. Soepraoen</p>
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
  {{-- <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script> --}}
@endpush

@push('page-js')
  {{-- <script src="{{ asset('js/mahasiswa/index.js') }}"></script> --}}
@endpush

@push('css')
  <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">
@endpush
