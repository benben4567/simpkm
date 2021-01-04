@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Dashboard</h1>
      </div>
      <div class="section-body">
      @if(Auth::user()->student->major_id == null)
        {{-- Hero 1 --}}
        <div class="row">
          <div class="col-12 mb-4">
            <div class="hero bg-success text-white">
              <div class="hero-inner">
                <h2>Selamat Datang, {{ first_name(Auth::user()->student->nama)  }}!</h2>
                <p class="lead">Sebelum mulai silahkan lengkapi data diri dulu ya, klik tombol dibawah ini.</p>
                <div class="mt-4">
                  <a href="{{ route('profile.index') }}" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="fas fa-user"></i> Data Diri</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @else
        <!-- Hero 2-->
        <div class="row">
          <div class="col-12 mb-4">
            <div class="hero bg-primary text-white">
              <div class="hero-inner">
                <h2>Halo, {{ Auth::user()->student->nama }}!</h2>
                <p class="lead">Selamat datang di Web Sistem Informasi Program Kreativitas Mahasiswa ITSK RS dr. Soepraoen</p>
              </div>
            </div>
          </div>
        </div>
        <!-- Table -->
        <div class="row">
          <div class="col-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h4>Usulan Proposal</h4>
              </div>
              <div class="card-body p-1">
                <table class="table table-striped table-md" id="table">
                  <thead>
                    <tr class="text-center">
                      <th>#</th>
                      <th>Skema</th>
                      <th>Judul</th>
                      <th>Dosen Pembimbing</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($proposals as $proposal)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td class="text-center">{{ $proposal->skema }}</td>
                      <td><a href="{{route('proposal.show', ['id' => $proposal->id])}}">{{ $proposal->judul }}</a></td>
                      <td class="text-center">
                        @foreach($proposal->pembimbing as $pembimbing)
                          {{ $pembimbing->nama }}
                        @endforeach
                      </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      @endif
      </div>
    </section>
  </div>
@endsection

@push('lib-js')
  {{-- Datatables --}}
  <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
@endpush

@push('page-js')
  <script src="{{ asset('js/mahasiswa/index.js') }}"></script>
@endpush

@push('css')
  <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">
@endpush
