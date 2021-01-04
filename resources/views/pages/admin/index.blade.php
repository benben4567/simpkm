@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Dashboard</h1>
      </div>
      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
              <i class="far fa-caret-square-up"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Usulan</h4>
              </div>
              <div class="card-body">
                {{ $proposal }}
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
              <i class="far fa-check-square"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Lolos</h4>
              </div>
              <div class="card-body">
                {{ $lolos }}
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
              <i class="far fa-flag"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Prodi</h4>
              </div>
              <div class="card-body">
                {{ $major }}
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-success">
              <i class="fas fa-user"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Mahasiswa</h4>
              </div>
              <div class="card-body">
                {{ $student }}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-8 col-md-12 col-12 col-sm-12">
          <div class="card">
            <div class="card-header">
              <h4>Statistik</h4>
            </div>
            <div class="card-body">
              <canvas id="myChart" height="182"></canvas>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-12 col-12 col-sm-12">
          <div class="card">
            <div class="card-body p-0">
              <div class="py-2 px-2">
                <div class="form-group">
                  <select class="form-control selectric" name="periode">
                    <option selected diabled>-pilih-</option>
                    <option>2020</option>
                  </select>
                </div>
              </div>
              <div class="table-responsive px-2">
                <table class="table table-striped table-md" id="table">
                  <thead>
                    <tr class="text-center">
                      <th>Skema</th>
                      <th>Jumlah</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>PKM-P</td>
                      <td>0</td>
                    </tr>
                    <tr>
                      <td>PKM-K</td>
                      <td>0</td>
                    </tr>
                    <tr>
                      <td>PKM-M</td>
                      <td>0</td>
                    </tr>
                    <tr>
                      <td>PKM-T</td>
                      <td>0</td>
                    </tr>
                    <tr>
                      <td>PKM-KC</td>
                      <td>0</td>
                    </tr>
                    <tr>
                      <td>PKM-AI</td>
                      <td>0</td>
                    </tr>
                    <tr>
                      <td>PKM-GT</td>
                      <td>0</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection

@push('lib-js')
  <script src="{{ asset('vendor/chart.js/dist/Chart.min.js') }}"></script>
  <script src="{{ asset('vendor/selectric/public/jquery.selectric.min.js') }}"></script>
@endpush

@push('page-js')
  <script src="{{ asset('js/admin/index.js') }}"></script>
@endpush

@push('css')
  <link rel="stylesheet" href="{{ asset('vendor/selectric/public/selectric.css')}}">
@endpush
