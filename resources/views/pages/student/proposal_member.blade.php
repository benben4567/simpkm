@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Usulan Proposal</h1>
      </div>
      <div class="section-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h4 class="header-title">Usulan PKM</h4>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="row">
                      <div class="col-sm-12 col-md-6">
                        <dl class="row">
                          <dt class="col-sm-3">Judul</dt>
                          <dd class="col-sm-9">{{ $proposal->judul }}</dd>

                          <dt class="col-sm-3">Skema</dt>
                          <dd class="col-sm-9">{{ $proposal->skema }}</dd>

                          <dt class="col-sm-3">Tanggal Usulan</dt>
                          <dd class="col-sm-9">{{ $proposal->created_at->isoFormat('D MMMM Y')}}</dd>
                        </dl>
                      </div>
                      <div class="col-sm-12 col-md-6">
                        <dl class="row">
                          <dt class="col-sm-3">Pembimbing</dt>
                          <dd class="col-sm-9">{{ $pembimbing->nama }}</dd>

                          <dt class="col-sm-3">Reviewer 1</dt>
                          <dd class="col-sm-9">{{ $reviewer1->nama ?? '-' }}</dd>

                          <dt class="col-sm-3">Reviewer 2</dt>
                          <dd class="col-sm-9">{{ $reviewer2->nama ?? '-' }}</dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Anggota Pengusul</h4>
                <div class="card-header-action">
                  <button type="button" class="btn btn-primary btn-icon" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus"></i> Anggota</button>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  @foreach ($members as $member)
                    <div class="col-md-12 col-lg-2 mb-4">
                      <div class="user-item">
                        @if($member->pivot->jabatan == 'Ketua')
                        <img alt="image" src="{{asset('vendor/stisla/img/avatar/avatar-5.png')}}" class="img-fluid">
                        @else
                        <img alt="image" src="{{asset('vendor/stisla/img/avatar/avatar-1.png')}}" class="img-fluid">
                        @endif
                        <div class="user-details">
                          <div class="user-name">{{ $member->nama }}</div>
                          <div class="text-job text-muted">{{$member->pivot->jabatan}}</div>
                          <div class="user-cta">
                            @if($member->pivot->jabatan == 'Ketua')
                            <button type="button" class="btn btn-icon btn-secondary" disabled><i class="fa fa-trash" aria-hidden="true"></i> Hapus</button>
                            @else
                            <button type="button" class="btn btn-icon btn-danger btn-remove" data-proposal="{{ $proposal->id }}" data-student="{{ $member->id }}"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</button>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Anggota</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <table class="table table-striped table-md" id="table">
                  <thead>
                    <tr class="text-center">
                      <th>NIM</th>
                      <th>Nama</th>
                      <th>Tambahkan</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($students as $student)
                      <tr class="text-center">
                        <td>{{ $student->nim }}</td>
                        <td>{{ $student->nama }}</td>
                        <td>
                          <button type="button" data-toggle="tooltip" data-placement="bottom" title="Tambahkan" class="btn btn-sm btn-primary btn-icon btn-add" data-proposal="{{ $proposal->id }}" data-student="{{ $student->id }}"><i class="fas fa-plus-square"></i></button>
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

  </div>
@endsection

@push('lib-js')
  {{-- Datatables --}}
  <script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
@endpush

@push('page-js')
  <script src="{{ asset('js/mahasiswa/usulan-member.js') }}"></script>
@endpush

@push('css')
  <link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}">
@endpush
