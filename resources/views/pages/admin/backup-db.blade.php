@extends('layouts.master')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Pencadangan Database</h1>
            </div>
        </section>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if (Session::has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Sukses!</strong> {{ Session::get('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            
                            @if (Session::has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Gagal!</strong> {{ Session::get('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span> 
                                    </button>
                                </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <a href="{{ route('monitoring.database.backup') }}" class="btn btn-primary" role="button" title="Backup Database"><i class="fas fa-database"></i> Cadangkan Sekaran</a>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-md" id="table">
                                    <thead>
                                        <tr class=text-center>
                                            <th>#</th>
                                            <th>Nama File</th>
                                            <th>Ukuran</th>
                                            <th>Waktu Backup</th>
                                            <th>Unduh</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($backupFiles as $key => $value)
                                            <tr>
                                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                <td class="align-middle">{{ $value->getFilename() }}</td>
                                                <td class="text-center align-middle">{{ formatSize($value->getSize()) }}</td>
                                                <td class="text-center align-middle">{{ date('d-m-Y H:i:s', $value->getMTime()) }}</td>
                                                <td class="text-center align-middle">
                                                    <a class="btn btn-info" href="{{ route('monitoring.database.download', $value->getFilename()) }}" role="button"
                                                        title="Download file {{ $value->getFilename() }}"><i class="fas fa-file-download"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Tidak ada data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
    {{-- <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script> --}}
    <script src="{{ asset('vendor/datatables/DataTables-1.10.23/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/DataTables-1.10.23/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons-1.6.5/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons-1.6.5/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons-1.6.5/js/buttons.print.min.js') }}"></script>
    {{-- Selectric --}}
    <script src="{{ asset('vendor/selectric/public/jquery.selectric.min.js') }}"></script>
@endpush

@push('page-js')
@endpush

@push('css')
    {{-- Datatables --}}
    <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables/DataTables-1.10.23/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables/buttons-1.6.5/css/buttons.bootstrap4.min.css') }}">
    {{-- Selectric --}}
    <link rel="stylesheet" href="{{ asset('vendor/selectric/public/selectric.css') }}">
@endpush
