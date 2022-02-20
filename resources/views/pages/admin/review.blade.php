@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Detail Proposal</h1>
      </div>
      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <dl class="row">
                  <dt class="col-sm-3">Skema</dt>
                  <dd class="col-sm-9">:</dd>

                  <dt class="col-sm-3">Judul</dt>
                  <dd class="col-sm-9">:</dd>

                  <dt class="col-sm-3">Pembimbing</dt>
                  <dd class="col-sm-9">:</dd>

                  <dt class="col-sm-3">Ketua</dt>
                  <dd class="col-sm-9">:</dd>

                  <dt class="col-sm-3">Anggota</dt>
                  <dd class="col-sm-9">:
                    <ul class="pl-3"></ul>
                  </dd>

                  <dt class="col-sm-3">Reviewer</dt>
                  <dd class="col-sm-9">:</dd>
                </dl>
                <button type="button" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Review</button>
                <button type="button" class="btn btn-success"><i class="fas fa-clipboard-check"></i> Acc Proposal</button>
              </div>
            </div>
          </div>
        </div>
        <h2 class="section-title">Riwayat Review</h2>
        <div class="row">
          <div class="col-12">
            <div class="activities">

              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="fas fa-robot"></i>
                </div>
                <div class="activity-detail w-100">
                  <div class="mb-2">
                    <span class="text-job text-primary">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $myDate2)->diffForHumans() }}</span>
                    <span class="bullet"></span>
                    <span class="text-job" style="text-transform: none; font-size: 12px;">Sistem</span>
                    <div class="float-right">
                      <span class="text-job">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $myDate2)->translatedFormat('d F Y | H:i:s') }}</span>
                    </div>
                  </div>
                  <p style="line-height: 20px">Usulan Proposal Awal</p>
                  <a class="btn btn-primary mt-3" href="#" target="_blank" role="button"><i class="fas fa-download"></i> Proposal Usulan</a>
                </div>
              </div>
              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="fas fa-user-edit"></i>
                </div>
                <div class="activity-detail">
                  <div class="mb-2">
                    <span class="text-job text-primary">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $myDate)->diffForHumans() }}</span>
                    <span class="bullet"></span>
                    <span class="text-job" style="text-transform: none; font-size: 12px;">{{ __('Nama Reviewer')}}</span>
                    <div class="float-right">
                      <span class="text-job">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $myDate)->translatedFormat('d F Y | H:i:s') }}</span>
                    </div>
                  </div>
                  <p style="line-height: 20px">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nostrum eum aperiam odio alias obcaecati fugiat animi! Magni nesciunt, corporis sed voluptatem hic earum, tempora at dignissimos velit illum nobis voluptates?</p>
                  <a class="btn btn-success mt-3" href="#" target="_blank" role="button"><i class="fas fa-download"></i> Hasil Review</a>
                </div>
              </div>
              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="fas fa-users"></i>
                </div>
                <div class="activity-detail">
                  <div class="mb-2">
                    <span class="text-job text-primary">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', "2022-02-19 14:37:13")->diffForHumans() }}</span>
                    <span class="bullet"></span>
                    <span class="text-job" style="text-transform: none; font-size: 12px;">Tim Pengusul</span>
                    <div class="float-right">
                      <span class="text-job">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', "2022-02-19 14:37:13")->translatedFormat('d F Y | H:i:s') }}</span>
                    </div>
                  </div>
                  <p style="line-height: 20px">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla, impedit. Reiciendis, expedita ex ipsam dolorem cupiditate quidem, neque itaque laudantium quisquam deleniti minus eaque quam ullam, earum provident sequi soluta.</p>
                  <a class="btn btn-primary mt-3" href="#" target="_blank" role="button"><i class="fas fa-download"></i> Hasil Revisi</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection

@push('lib-js')

@endpush

@push('page-js')
@endpush

@push('css')
@endpush
