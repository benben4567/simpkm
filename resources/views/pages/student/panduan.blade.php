@extends('layouts.master')
@section('content')
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Panduan</h1>
      </div>
      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-3">
                    <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="alur-tab4" data-toggle="tab" href="#alur" role="tab" aria-controls="alur" aria-selected="true">Alur Pengusulan</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="registrasi-tab4" data-toggle="tab" href="#registrasi" role="tab" aria-controls="registrasi" aria-selected="false">1. Registrasi</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="usulan-tab4" data-toggle="tab" href="#usulan" role="tab" aria-controls="usulan" aria-selected="false">2. Usulan</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="anggota-tab4" data-toggle="tab" href="#anggota" role="tab" aria-controls="anggota" aria-selected="false">3. Tambah Anggota</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="status-tab4" data-toggle="tab" href="#status" role="tab" aria-controls="status" aria-selected="false">4. Status Usulan</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="cetak-tab4" data-toggle="tab" href="#cetak" role="tab" aria-controls="cetak" aria-selected="false">5. Cetak</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="review-tab4" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false">6. Review</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="selesai-tab4" data-toggle="tab" href="#selesai" role="tab" aria-controls="selesai" aria-selected="false">7. Selesai</a>
                      </li>
                    </ul>
                  </div>
                  <div class="col-12 col-sm-12 col-md-9">
                    <div class="tab-content no-padding" id="myTab2Content">
                      <div class="tab-pane fade show active" id="alur" role="tabpanel" aria-labelledby="alur">
                        <div class="text-center">
                          <img src="{{ asset('img/Alur.png') }}" class="img-fluid rounded" alt="Alur">
                        </div>
                      </div>
                      <div class="tab-pane fade" id="registrasi" role="tabpanel" aria-labelledby="registrasi">
                        <h5>Registrasi</h5>
                        <p class="text-justify">
                          Jika belum mempunyai akun silahkan melakukan <a href="{{ route('register') }}">Registrasi</a> terlebih dahulu. Setelah registrasi dan login maka akan muncul halaman <a href="{{ route('home') }}">Dashboard</a> sebagai halaman utama. Setelah itu kalian <strong>WAJIB</strong> mengisi data diri kalian di halaman <a href="{{ route('profile.index') }}">Data Diri</a>.
                        </p>
                      </div>
                      <div class="tab-pane fade" id="usulan" role="tabpanel" aria-labelledby="usulan">
                        <h5>Usulan Proposal</h5>
                        <p class="text-justify">
                          Setelah melengkapi data diri, kalian bisa mengusulkan proposal PKM. Perlu diingat pengusulan PKM <strong>HANYA</strong> dilakukan oleh Ketua Kelompok, anggota PKM tidak perlu membuat usulan PKM. Jadi misalkan kalian tergabung di dua (atau lebih) kelompok PKM, kemudian di salah satunya kalian menjadi Ketua Kelompok maka hanya usulkan PKM tersebut.
                        </p>
                        <p class="text-justify">
                          Cara pengusulan PKM adalah dengan membuka halaman <a href="{{ route('proposal.create') }}">Usulan PKM</a> yang ada di menu sebelah kiri. Kemudian klik tombol <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="fas fa-plus"></i> Usulan PKM</button> dan isi dengan lengkap formulir yang telah disediakan. Setelah selesai kalian sebagai Ketua Kelompok bisa menambahkan anggota di PKM kalian, caranya bisa di lihat di langkah ke "3. Tambah Anggota".
                        </p>
                      </div>
                      <div class="tab-pane fade" id="anggota" role="tabpanel" aria-labelledby="anggota">
                        <h5>Anggota Kelompok</h5>
                        <p class="text-justify">
                          Sudah selesai membuat usulan PKM ? Selanjutnya adalah menambahkan anggota kedalam usulan PKM yang sudah kamu buat barusan. Oh iya, fitur ini hanya bisa diakses oleh Ketua Kelompok ya.
                        </p>
                        <p class="text-justify">
                          Pada halaman <a href="{{ route('proposal.create') }}">Usulan PKM</a> kalian akan melihat sebuah tabel dimana tabel tersebut akan menampilkan PKM yang pernah kalian kerjakan/usulkan. Kemudian lihat pada kolom <strong>aksi</strong>, didalam kolom tersebut terdapat beberapa tombol diantaranya:
                          <dl class="row">
                            <dt class="col-sm-2 text-right"><button type="button" class="btn btn-warning btn-sm btn-icon"><i class="fas fa-pencil-alt"></i></button></dt>
                            <dd class="col-sm-10">Tombol ini berfungsi untuk mengubah data usulan PKM (hanya bisa diakses oleh Ketua)</dd>

                            <dt class="col-sm-2 text-right"><button type="button" class="btn btn-primary btn-sm btn-icon"><i class="fas fa-users"></i></button></dt>
                            <dd class="col-sm-10">Tombol ini berfungsi untuk menambahkan anggota atau mahasiswa kedalam Kelompok PKM (hanya bisa diakses oleh Ketua)</dd>

                            <dt class="col-sm-2 text-right"><button type="button" class="btn btn-success btn-sm btn-icon"><i class="fas fa-download"></i></button></dt>
                            <dd class="col-sm-10">Tombol ini untuk mendownload Berita Acara dan Form Penilaian (bisa diakses oleh Ketua maupun Anggota)</dd>
                          </dl>
                        </p>
                        <p class="text-justify">
                          Dari ketiga tombol diatas klik tombol untuk menambahkan anggota, nanti akan muncul halaman baru dimana kamu sudah menjadi Ketua nya. Untuk menambah anggota, klik tombol <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="fas fa-plus"></i> Anggota</button> nanti akan muncul <i>pop-up</i> tabel yang berisikan nama mahasiswa. Kamu bisa menggunakan fitur <i>search</i> dengan mengetikkan NIM atau Nama dari anggota mu, untuk memilih nya cukup klik tombol <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="fas fa-plus-square"></i></button> Jika didalam tabel tidak ada nama yang kamu inginkan, pastikan lagi temanmu sudah mendaftar dan mengisi data diri di SIM PKM ini ya.
                        </p>
                        <p class="text-justify">
                          Jika kamu ingin menghapus temanmu dari anggota kelompok, cukup klik tombol <button type="button" class="btn btn-danger btn-sm btn-icon"><i class="fas fa-trash"></i> Hapus</button> yang ada dibawah nama nya.
                        </p>
                      </div>
                      <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status">
                        <h5>Status Usulan</h5>
                        <p class="text-justify">
                          Selain tombol di kolom aksi, juga terdapat kolom status. Tiap-tiap usulan PKM mempunyai status masing-masing, ada 3 status diantaranya:
                          <dl class="row">
                            <dt class="col-sm-2 text-center"><span class="badge badge-primary">Kompilasi</span></dt>
                            <dd class="col-sm-10">Pada tahap ini usulan PKM kalian masih dalam proses kompilasi dan belum mendapat <i>Dosen Reviewer</i>, jadi tetap cek secara berkala ya.</dd>

                            <dt class="col-sm-2 text-center"><span class="badge badge-warning">Proses</span></dt>
                            <dd class="col-sm-10">Pada tahap ini usulan PKM kalian sudah mendapat <i>Dosen Reviewer</i> dan kalian sudah bisa mendownload <strong>Berita Acara</strong> & <strong>Form Penilaian</strong> kemudian mencetaknya.</dd>

                            <dt class="col-sm-2 text-center"><span class="badge badge-success">Selesai</span></dt>
                            <dd class="col-sm-10">Jika status usulan kalian sudah berubah seperti ini, maka proposal kalian sudah selesai di review dan kalian bisa mengambilnya di Kemahasiswaan untuk kalian revisi nantinya.</dd>
                          </dl>
                        </p>
                      </div>
                      <div class="tab-pane fade" id="cetak" role="tabpanel" aria-labelledby="cetak">
                        <h5>Cetak Berita Acara dan Form Penilaian</h5>
                        <p class="text-justify">
                          Setelah status berubah menjadi <span class="badge badge-warning">Proses</span> kalian bisa mendownload kemudian mencetak Berita Acara dan Form Penilaian usulan PKM. Untuk tombol cetak sudah dijelaskan pada langkah ke 3 ya. Jangan lupa kalian juga harus mencetak proposal PKM yang akan direview sebanyak dua rangkap, masing-masing untuk Dosen Reviewer 1 dan Dosen Reviewer 2.
                          Untuk proses review dijelaskan pada langkah selanjutnya.
                        </p>
                      </div>
                      <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review">
                        <h5>Review</h5>
                        <p class="text-justify">
                          Sebelum diserahkan ke Dosen Reviewer berkas berikut harus sudah kalian persiapkan dulu, diantaranya :
                          <ul>
                            <li>Proposal PKM (2 Rangkap)</li>
                            <li>Berita Acara</li>
                            <li>Form Penilaian</li>
                          </ul>
                        </p>
                        <p class="text-justify">
                          Setelah lengkap, kalian bisa mendatangi Dosen Reviewer yang sudah tertera (di Berita Acara atau Form Penilaian) dan memberikan berkas dengan susunan seperti berikut :
                          <dl class="row">
                            <dt class="col-sm-3">Dosen Reviewer 1</dt>
                            <dd class="col-sm-9">
                              <ul>
                                <li>Proposal PKM</li>
                                <li>Form Penilaian Reviewer 1</li>
                                <li>Berita Acara</li>
                              </ul>
                            </dd>

                            <dt class="col-sm-3">Dosen Reviewer 2</dt>
                            <dd class="col-sm-9">
                              <ul>
                                <li>Proposal PKM</li>
                                <li>Form Penilaian Reviewer 2</li>
                              </ul>
                            </dd>
                          </dl>
                        </p>
                        <p class="text-justify">
                          Sebelum menyerahkan ke Dosen Reviewer cek kembali kelengkapan berkas yang akan diberikan, jangan sampai tertukar ya Form Penilaian untuk Reviewer 1 dan Reviewer 2. <br>
                          Untuk Form Penilaian tidak perlu kalian gandakan, karena dalam satu dokumen sudah terdapat dua lembar Form Penilaian (halaman 1 dan halaman 2).
                        </p>
                      </div>
                      <div class="tab-pane fade" id="selesai" role="tabpanel" aria-labelledby="selesai">
                        <h5>Selesai</h5>
                        <p class="text-justify">
                          Berkas sudah diserahkan ke Reviewer, sekarang tinggal cek secara berkala status usulan kalian di SIM PKM ini ya. Jika status sudah berubah menjadi <span class="badge badge-success">Selesai</span> maka kalian bisa mengambil Proposal PKM kalian di Kemahasiswaan (bukan ke Dosen Reviewer) untuk kalian revisi. Usahakan segera mungkin kalian ambil setelah status berubah agar bisa segera kalian revisi.
                        </p>
                        <p class="text-justify">
                          Setelah revisi kalian selesai, kalian bisa melanjutkan ke tahap Upload proposal ke SIM Belmawa milik Kemendikbud. Untuk username dan password akan diberikan melalui SIM PKM ini, kalian bisa buka menu <a href="{{ route('profile.index') }}">Data Diri</a> kemudian lihat di bagian kanan bawah.
                        </p>
                        <p class="text-justify">
                          Jika ada kendala atau permasalahan dalam penggunaan SIM PKM ini silahkan hubungi Pak Ben di Kemahasiswaan.
                        </p>
                      </div>
                    </div>
                  </div>
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
  {{-- <script src="{{ asset('js/mahasiswa/panduan.js') }}"></script> --}}
@endpush

@push('css')
@endpush
