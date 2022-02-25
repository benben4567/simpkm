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
                  <dd class="col-sm-9">: {{ $proposal->skema }}</dd>

                  <dt class="col-sm-3">Judul</dt>
                  <dd class="col-sm-9">: {{ $proposal->judul }}</dd>

                  <dt class="col-sm-3">Pembimbing</dt>
                  <dd class="col-sm-9">: {{ $pembimbing->nama }}</dd>

                  <dt class="col-sm-3">Ketua</dt>
                  <dd class="col-sm-9">: {{ $ketua->nama." (".$ketua->nim.")" }}</dd>

                  <dt class="col-sm-3">Anggota</dt>
                  <dd class="col-sm-9">:
                    @foreach ($anggota as $ang)
                      <span class="badge badge-primary">{{ $ang['nama']." (".$ang['nim'].")" }}</span>
                    @endforeach
                  </dd>

                  <dt class="col-sm-3">Reviewer</dt>
                  <dd class="col-sm-9">: {{ $reviewer->nama }}</dd>
                </dl>

                @if ($proposal->reviews->last()->acc == 1)
                  <a type="button" class="btn btn-danger" href="{{ route('teacher.proposal.download', ['file' => $proposal->file]) }}"><i class="fas fa-file-pdf"></i> Proposal ACC</a>
                  @if ($reviewer->user_id == auth()->user()->id)
                    <button type="button" class="btn btn-info btn-form"><i class="fas fa-file-word"></i> Form Penilaian</button>
                    <button type="button" class="btn btn-info btn-berita"><i class="fas fa-file-word"></i> Berita Acara</button>
                  @endif
                @else
                  @if ($reviewer->user_id == auth()->user()->id)
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalReview"><i class="fas fa-upload"></i> Hasil Review</button>
                    {{-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAcc"><i class="fas fa-clipboard-check"></i> Acc Proposal</button> --}}
                  @endif
                @endif
              </div>
            </div>
          </div>
        </div>
        <h2 class="section-title">Riwayat Review</h2>
        <div class="row">
          <div class="col-12">
            <div class="activities">
              @foreach ($proposal->reviews as $review )
                <div class="activity">
                  <div class="activity-icon bg-primary text-white shadow-primary">
                    @if ($loop->first)
                      <i class="fas fa-robot"></i>
                    @elseif ($review->type == 'student')
                      <i class="fas fa-users"></i>
                    @elseif ($review->type == 'teacher')
                      <i class="fas fa-user-edit"></i>
                    @endif
                  </div>
                  <div class="activity-detail w-100">
                    <div class="mb-2">
                      <span class="text-job text-primary">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                      <span class="bullet"></span>
                      @if ($loop->first)
                        <span class="text-job" style="text-transform: none; font-size: 12px;">Sistem</span>
                      @elseif ($review->type == 'student')
                        <span class="text-job" style="text-transform: none; font-size: 12px;">{{ __('Tim Pengusul') }}</span>
                      @elseif ($review->type == 'teacher')
                        <span class="text-job" style="text-transform: none; font-size: 12px;">{{ $review->user->name }}</span>
                      @endif
                      <div class="float-right">
                        <span class="text-job">{{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d F Y | H:i:s') }}</span>
                      </div>
                    </div>
                    <p style="line-height: 20px">{{ $review->description }}</p>
                    @if ($loop->first)
                      <a class="btn btn-primary mt-3" href="{{ route('teacher.proposal.download', ['file' => $review->file]) }}" target="_blank" role="button"><i class="fas fa-download"></i> Proposal Usulan</a>
                    @elseif ($review->type == 'student')
                    <a class="btn btn-info mt-3" href="{{ route('teacher.proposal.download', ['file' => $review->file]) }}" target="_blank" role="button"><i class="fas fa-download"></i> Hasil Revisi</a>
                    @elseif ($review->type == 'teacher')
                    <a class="btn btn-success mt-3" href="{{ route('teacher.proposal.download', ['file' => $review->file]) }}" target="_blank" role="button"><i class="fas fa-download"></i> Hasil Review</a>
                  @endif
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Modal Review -->
    <div class="modal fade" id="modalReview" tabindex="-1" role="dialog" aria-labelledby="modalTitleReview" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Review Proposal</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <form method="post" id="form-review">
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                  <input type="text" class="d-none" name="id-proposal" value="{{ $proposal->id }}">
                  <input type="text" class="d-none" name="id-folder" value="{{ $periode->id_folder_review }}">
                  <div class="form-group">
                    <label>Instruksi Singkat</label>
                    <textarea class="form-control h-100" name="deskripsi" rows="5" required></textarea>
                  </div>
                  <div class="form-group">
                    <label for="">File Hasil Review</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input @error('file') is-invalid @enderror" accept="application/pdf" name="file" id="customFile" required>
                      <label class="custom-file-label" for="customFile"></label>
                      <small id="fileHelpId" class="form-text text-muted">Format file : PDF | Maks. 5MB</small>
                      @error('file')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal Acc -->
    <div class="modal fade" id="modalAcc" tabindex="-1" role="dialog" aria-labelledby="modalTitleAcc" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Acc Proposal</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <form method="post" id="form-acc">
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                  <input type="text" class="d-none" name="id-proposal" value="{{ $proposal->id }}">
                  <input type="text" class="d-none" name="id-folder" value="{{ $periode->id_folder }}">
                  <div class="form-group">
                    <label>Instruksi Singkat (opsional)</label>
                    <textarea class="form-control h-100" name="deskripsi" rows="5"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="">File Proposal Acc</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input @error('file') is-invalid @enderror" accept="application/pdf" name="file" id="customFile" required>
                      <label class="custom-file-label" for="customFile"></label>
                      <small id="fileHelpId" class="form-text text-muted">Format file : PDF | Maks. 5MB</small>
                      @error('file')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <form action="{{ route('teacher.proposal.download.berita') }}" id="form-berita" method="get" class="d-none">
      @csrf
      <input type="text" name="id" value="{{ $proposal->id }}">
    </form>

    <form action="{{ route('teacher.proposal.download.form') }}" id="form-penilaian" method="get" class="d-none">
      @csrf
      <input type="text" name="id" value="{{ $proposal->id }}">
    </form>
  </div>
@endsection

@push('lib-js')

@endpush

@push('page-js')
<script src="{{ asset('js/dosen/reviewer-detail.js') }}"></script>
@endpush

@push('css')
@endpush
