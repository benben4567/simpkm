<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Reviewer - SIMPKM ITSK RS dr. Soepraoen</title>

  <!-- Bootstrap core CSS -->
  <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
</head>

<body>


  <!-- Page Content -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 text-center">
        <h3 class="mt-5">Usulan Proposal PKM</h3>
        <h4>Institut Teknologi Sains dan Kesehatan RS dr. Soepraoen</h4>
        <p class="lead">Tahun {{ $tahun }}</p>
        <p class="mb-5">Pendamping : {{ $teacher->nama." / ".$teacher->nidn }}</p>
        <table class="table table-striped table-bordered" style="width: 100%">
          <thead class="thead-dark">
            <tr>
              <th scope="col" class="w-10">#</th>
              <th scope="col" class="w-20">Skema</th>
              <th scope="col" class="w-50">Judul</th>
              <th scope="col" class="w-20">Ketua</th>
              <th scope="col" class="w-20">Reviewer</th>
            </tr>
          </thead>
          <tbody>
              @foreach($teacher->proposals as $proposal)
                <tr>
                  <th scope="row">{{ $loop->iteration }}</th>
                  <td>{{ $proposal->skema }}</td>
                  <td>{{ $proposal->judul }}</td>
                  <td>{{ $proposal->ketua[0]['nama'] }}</td>
                  <td>
                    <ul>
                      @foreach ($proposal->reviewer as $reviewer)
                        <li>{{ $reviewer['nama']}}</li>
                      @endforeach
                    </ul>
                  </td>
                </tr>
              @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript -->
  <script src=" {{ asset('vendor/jquery/jquery-3.5.1.min.js')}}"></script>
  <script src=" {{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script>window.print();</script>
</body>

</html>
