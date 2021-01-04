<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Download</title>
</head>
<body>
  <table style="width:100%">
    <thead>
      <tr>
        <th>#</th>
        <th>Skema</th>
        <th>Judul</th>
        <th>Ketua</th>
        <th>Pembimbing</th>
      </tr>
    </thead>
    <tbody>
      @foreach($proposals as $prop)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $prop->skema }}</td>
          <td>{{ $prop->judul }}</td>
          <td>
            @foreach($prop->students as $student)
              {{ $student->nama }}
            @endforeach
          <td>
            @foreach($prop->teachers as $teacher)
              {{ $teacher->nama }}
            @endforeach
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
