<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}">

    <!-- Icon Favicon -->
    <link rel="shorcut icon" href="favicon.ico" type="image/x-icon">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/stisla/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/stisla/css/components.css') }}">
    <style>
        .main-content2 {
            padding-left: 30px;
            padding-right: 30px;
            padding-top: 80px;
            width: 100%;
            position: relative;
        }

        .main-footer2 {
            padding: 20px 30px 20px 30px;
            margin-top: 40px;
            color: #98a6ad;
            border-top: 1px solid #e3eaef;
            display: inline-block;
            width: 100%;
        }

        .main-footer .footer-left {
            float: left;
        }

        .main-footer .footer-right {
            float: right;
        }
    </style>

    <title>SIM PKM</title>
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>

            <!-- Main Content -->
            <div class="main-content2">
                <section class="section">
                    <div class="section-header">
                        <h1>Panduan</h1>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-info alert-has-icon">
                                    <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                                    <div class="alert-body">
                                        <div class="alert-title">Perhatian</div>
                                        Dibawah ini merupakan Panduan dalam menggunakan SIM PKM di ITSK RS dr. Soepraoen. Harap baca secara seksama seluruh bagian dari Panduan ini, yaitu Alur
                                        Pengusulan serta tahapan-tahapan pengusulan PKM.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-3">
                                                <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="alur-tab4" data-toggle="tab" href="#alur" role="tab" aria-controls="alur" aria-selected="true">Alur
                                                            Pengusulan</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="registrasi-tab4" data-toggle="tab" href="#registrasi" role="tab" aria-controls="registrasi" aria-selected="false">1.
                                                            Registrasi</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="usulan-tab4" data-toggle="tab" href="#usulan" role="tab" aria-controls="usulan" aria-selected="false">2. Usulan</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="anggota-tab4" data-toggle="tab" href="#anggota" role="tab" aria-controls="anggota" aria-selected="false">3. Tambah
                                                            Anggota</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="status-tab4" data-toggle="tab" href="#status" role="tab" aria-controls="status" aria-selected="false">4. Status
                                                            Usulan</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="review-tab4" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false">5. Review</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="selesai-tab4" data-toggle="tab" href="#selesai" role="tab" aria-controls="selesai" aria-selected="false">6.
                                                            Selesai</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-9">
                                                <div class="tab-content no-padding" id="myTab2Content">
                                                    <div class="tab-pane fade show active" id="alur" role="tabpanel" aria-labelledby="alur">
                                                        <p class="text-center">
                                                            Dibawah ini merupakan alur/tahapan secara umum dalam pengusulan PKM di SIM PKM ini.
                                                        </p>
                                                        <div class="text-center">
                                                            <img src="{{ asset('img/Alur.png') }}" class="img-fluid rounded" style="max-height: 800px" alt="Alur">
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="registrasi" role="tabpanel" aria-labelledby="registrasi">
                                                        <h5>Registrasi</h5>
                                                        <p class="text-justify">
                                                            Jika belum mempunyai akun silahkan melakukan <a href="{{ route('register') }}">Registrasi</a> terlebih dahulu. Saat registrasi cukup
                                                            mengisi <strong>Tahun Angkatan</strong> dan <strong>NIM</strong> saja, data akan sama dengan di SIAKAD. Setelah registrasi dan login maka
                                                            akan muncul halaman <a href="{{ route('home') }}">Dashboard</a> sebagai halaman utama. Jika ada data diri kalian yang salah, bisa
                                                            diperbaiki di halaman <a href="{{ route('profile.index') }}">Data Diri</a>.
                                                        </p>
                                                    </div>
                                                    <div class="tab-pane fade" id="usulan" role="tabpanel" aria-labelledby="usulan">
                                                        <h5>Usulan Proposal</h5>
                                                        <p class="text-justify">
                                                            Setelah melengkapi data diri, kalian bisa mengusulkan proposal PKM. Perlu diingat pengusulan PKM <strong>HANYA</strong> dilakukan oleh Ketua
                                                            Kelompok, anggota PKM tidak perlu membuat usulan PKM. Jadi misalkan kalian tergabung di dua (atau lebih) kelompok PKM, kemudian di salah
                                                            satunya kalian menjadi Ketua Kelompok maka hanya usulkan PKM tersebut.
                                                        </p>
                                                        <p class="text-justify">
                                                            Cara pengusulan PKM adalah dengan membuka halaman <a href="{{ route('proposal.create') }}">Usulan PKM</a> yang ada di menu sebelah kiri.
                                                            Kemudian klik tombol <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="fas fa-plus"></i> Usulan PKM</button> dan
                                                            isi dengan lengkap formulir yang telah disediakan. Setelah selesai kalian sebagai Ketua Kelompok bisa menambahkan anggota di PKM kalian,
                                                            caranya bisa di lihat di langkah ke "3. Tambah Anggota".
                                                        </p>
                                                    </div>
                                                    <div class="tab-pane fade" id="anggota" role="tabpanel" aria-labelledby="anggota">
                                                        <h5>Anggota Kelompok</h5>
                                                        <p class="text-justify">
                                                            Sudah selesai membuat usulan PKM ? Selanjutnya adalah menambahkan anggota kedalam usulan PKM yang sudah kamu buat barusan. Oh iya, fitur ini
                                                            hanya bisa diakses oleh Ketua Kelompok.
                                                        </p>
                                                        <p class="text-justify">
                                                            Pada halaman <a href="{{ route('proposal.create') }}">Usulan PKM</a> kalian akan melihat sebuah tabel dimana tabel tersebut akan
                                                            menampilkan PKM yang pernah kalian kerjakan/usulkan. Kemudian terdapat beberapa tombol diantaranya:
                                                        <dl class="row">
                                                            <dt class="col-sm-2 text-right"><button type="button" class="btn btn-warning btn-sm btn-icon"><i
                                                                        class="fas fa-pencil-alt"></i></button></dt>
                                                            <dd class="col-sm-10">Tombol ini berfungsi untuk mengubah data usulan PKM (hanya bisa diakses oleh Ketua)</dd>

                                                            <dt class="col-sm-2 text-right"><button type="button" class="btn btn-primary btn-sm btn-icon"><i class="fas fa-users"></i></button>
                                                            </dt>
                                                            <dd class="col-sm-10">Tombol ini berfungsi untuk menambahkan anggota kedalam Kelompok PKM (hanya bisa diakses oleh Ketua)</dd>
                                                        </dl>
                                                        </p>
                                                        <p class="text-justify">
                                                            Klik tombol tambah anggota, kemudian akan muncul halaman baru dimana kamu sudah menjadi Ketua nya. Untuk menambah anggota, klik tombol
                                                            <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="fas fa-plus"></i> Anggota</button> nanti akan muncul
                                                            <i>pop-up</i> tabel yang berisikan nama mahasiswa. Kamu bisa menggunakan fitur <i>search</i> dengan mengetikkan NIM atau Nama dari anggota
                                                            mu, untuk memilih nya cukup klik tombol <button type="button" class="btn btn-primary btn-sm btn-icon"><i
                                                                    class="fas fa-plus-square"></i></button> Jika didalam tabel tidak ada nama yang kamu inginkan, pastikan lagi temanmu sudah
                                                            mendaftar dan mengisi data diri di SIM PKM ini ya.
                                                        </p>
                                                        <p class="text-justify">
                                                            Jika kamu ingin menghapus temanmu dari daftar anggota kelompok, cukup klik tombol <button type="button"
                                                                class="btn btn-danger btn-sm btn-icon"><i class="fas fa-trash"></i> Hapus</button> yang ada dibawah nama nya.
                                                        </p>
                                                    </div>
                                                    <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status">
                                                        <h5>Status Usulan</h5>
                                                        <p class="text-justify">
                                                            Selain itu juga terdapat kolom status. Tiap-tiap usulan PKM mempunyai status masing-masing, ada 3 status diantaranya:
                                                        <dl class="row">
                                                            <dt class="col-sm-2 text-center"><span class="badge badge-primary">Kompilasi</span></dt>
                                                            <dd class="col-sm-10">Pada tahap ini usulan PKM kalian masih dalam proses kompilasi dan belum mendapat <i>Dosen Reviewer</i>, jadi tetap
                                                                cek secara berkala ya.</dd>

                                                            <dt class="col-sm-2 text-center"><span class="badge badge-warning">Proses</span></dt>
                                                            <dd class="col-sm-10">Pada tahap ini usulan PKM kalian sudah mendapat <i>Dosen Reviewer</i> dan proposal kalian sedang di review oleh
                                                                Reviewer. Untuk melihatnya kalian bisa membuka menu Review pada masing2 usulan PKM. Kemudian kalian juga bisa melakukan revisi ketika
                                                                proposal hasil review sudah diupload ulang oleh Dosen Reviewer.</dd>

                                                            <dt class="col-sm-2 text-center"><span class="badge badge-success">Selesai</span></dt>
                                                            <dd class="col-sm-10">Jika status usulan kalian sudah berubah seperti ini, maka proposal kalian sudah selesai di review dan kalian bisa
                                                                mencetaknya. Kemudian kumpulkan ke Kemahasiswaan agar kalian bisa mendapat akun SIMBELMAWA untuk upload Proposal PKM yg sudah ACC</dd>
                                                        </dl>
                                                        </p>
                                                    </div>
                                                    <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review">
                                                        <h5>Review</h5>
                                                        <p class="font-weight-bold">
                                                            Poin Penting :
                                                        </p>
                                                        <dl class="row">
                                                            <dt class="col-sm-3">Review</dt>
                                                            <dd class="col-sm-9">
                                                                Kegiatan ini dilakukan oleh Dosen Reviewer untuk memeriksa hasil pekerjaan Kelompok Pengusul PKM
                                                            </dd>

                                                            <dt class="col-sm-3">
                                                                Revisi
                                                            </dt>
                                                            <dd class="col-sm-9">
                                                                Kegiatan ini dilakukan oleh Kelompok Pengusul untuk memperbaiki proposal yang telah di review/periksa oleh dosen reviewer
                                                            </dd>
                                                        </dl>

                                                        <p class="text-justify">
                                                            Untuk mahasiswa yang akan melakukan revisi caranya sebagai berikut :
                                                        <ol>
                                                            <li>Klik tombol Review sesuai judul Usulan PKM (ada di tabel Usulan PKM)</li>
                                                            <li>Kemudian akan muncul <i>timeline</i> Riwayat Review Proposal, proposal yang pertama kali kalian upload akan otomatis muncul pada halaman
                                                                ini dan akan langsung di Review.</li>
                                                            <li><strong>Cek Secara Berkala.</strong> Selalu cek secara berkala review PKM kalian, karena Dosen Reviewer sewaktu-waktu dapat meng-upload
                                                                kembali hasil review PKM kalian.</li>
                                                            <li>Jika Dosen Reviewer sudah meng-upload kembali, kalian dapat mendownload nya dan melihat apa saja yang perlu di revisi di dalam proposal
                                                                tersebut. Kemudian lakukanlah revisi sesuai petunjuk Dosen Reviewer.</li>
                                                            <li>JIka sudah melakukan revisi, kalian dapat meng-upload kembali di halaman ini dengan klik tombol <strong>Hasil Revisi</strong>. Kemudian
                                                                muncul pop-up untuk memilih file hasil revisi dan mengisi keterangan yang perlu disampaikan (opsional). Kemudian klik SIMPAN.</li>
                                                            <li>Kemudian tunggu dan ulangi lagi langkah ke 3 sampai proposal kalian di ACC oleh dosen reviewer.</li>
                                                            <li>Jika sudah ACC, kalian bisa mendownload file tersebut dengan klik tombol <strong>Proposal ACC</strong> dan mencetaknya.</li>
                                                            <li>Selesai</li>
                                                        </ol>
                                                        </p>

                                                        <strong>Catatan :</strong>
                                                        <p class="text-justify">
                                                            Menu Revisi diatas hanya bisa diakses oleh mahasiswa yang menjadi KETUA didalam Proposal PKM tersebut.
                                                        </p>
                                                    </div>
                                                    <div class="tab-pane fade" id="selesai" role="tabpanel" aria-labelledby="selesai">
                                                        <h5>Selesai</h5>
                                                        <p class="text-justify">
                                                            Jika kalian sudah mencetak Proposal ACC, tahap selanjutnya adalah mengumpulkan proposal tersebut ke Bidang Kemahasiswan. Setelah itu kalian
                                                            akan mendapatkan Username dan Password yang nantinya digunakan untuk login ke Web SIMBELMAWA KEMDIKBUD (untuk meng-upload Proposal ACC PKM
                                                            kalian).
                                                            <br />
                                                            <br />
                                                            Untuk tata cara upload Proposal PKM di Web SIMBELMAWA akan dibuatkan panduan khusus (terpisah dari panduan ini).
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


            <!-- Footer -->
            <footer class="main-footer2">
                <div class="footer-left">
                    Copyright &copy; {{ date('Y') }} <div class="bullet"></div> UPTI ITSK RS dr. Soepraoen
                </div>
                <div class="footer-right">
                    2.0.0
                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.12.0/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendor/loading-overlay/dist/loadingoverlay.min.js') }}"></script>
    <script src="{{ asset('vendor/stisla/js/stisla.js') }}"></script>
    <!-- Template JS File -->
    <script src="{{ asset('vendor/stisla/js/scripts.js') }}"></script>
</body>

</html>
