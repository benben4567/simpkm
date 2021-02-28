@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Periode Pembukaan</h1>
      </div>
    </section>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body p-2">
              <div class="mb-3 ">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPeriode"><i class="fas fa-plus"></i> Pembukaan Baru</button>
              </div>
              <div class="table-responsive">
                <table class="table table-striped table-md" id=table>
                  <thead>
                    <tr class=text-center>
                      <th>#</th>
                      <th>Tahun</th>
                      <th>Jumlah Usulan</th>
                      <th>Status</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($periods as $period)
                      <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $period->tahun }}</td>
                        <td>{{ $period->proposals_count }}</td>
                        <td><span class="badge {{ $period->status == "buka" ? "badge-success" : "badge-danger" }}">{{ ucfirst($period->status) }}</span></td>
                        <td>
                          <button type="button" class="btn btn-icon btn-sm btn-warning" title="Edit" data-id="{{ $period->id }}"><i class="fas fa-pencil-alt"></i></button>
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

    <!-- Modal Periode -->
    <div class="modal fade" id="modalPeriode" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Buka Periode Baru</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="" method="post" id="create-periode">
              <div class="form-group">
                <label for="">Tahun Pembukaan</label>
                <select class="form-control selectric" name="tahun" id="tahun">
                  <option disabled value=""selected>- pilih -</option>
                  <option value="{{ Carbon\Carbon::now()->year }}">{{ Carbon\Carbon::now()->year }}</option>
                  <option value="{{ Carbon\Carbon::now()->addYear()->format('Y') }}">{{ Carbon\Carbon::now()->addYear()->format('Y') }}</option>
                </select>
              </div>
              <div>
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Periode Edit -->
    <div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Periode</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="" method="post" id="edit-periode">
              @csrf
              <input type="text" name="id" hidden>
              <div class="form-group">
                <label for="">Tahun</label>
                <input type="text" class="form-control" name="tahun" disabled>
              </div>
              <div class="form-group">
                <label for="">Status</label>
                <select class="form-control selectric" name="status" id="status">
                  <option value="buka">Buka</option>
                  <option value="tutup">Tutup</option>
                </select>
              </div>
              <div>
                <button type="submit" class="btn btn-primary" v-on:click="simpan">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('lib-js')
  {{-- Datatables --}}
  <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
  {{-- Selectric --}}
  <script src="{{ asset('vendor/selectric/public/jquery.selectric.min.js') }}"></script>
@endpush

@push('page-js')
  <script src="{{ asset('js/admin/period.js') }}"></script>
@endpush

@push('css')
  {{-- Datatables --}}
  <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">
  {{-- Selectric --}}
  <link rel="stylesheet" href="{{ asset('vendor/selectric/public/selectric.css') }}">
@endpush
