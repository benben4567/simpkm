<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
  public function showAll($role)
  {
    if ($role) {
      if ($role == 'student') {
        $users = User::whereRole('student')->with('student')->get();
      } else {
        $users = User::whereRole($role)->get();
      }
    } else {
      $users = User::all();
    }

    return $users;
  }

  public function show($role, $id)
  {
    switch ($role) {
      case 'admin':
        $user = User::whereId($id)->first();
        break;
      case 'student':
        $user = User::whereId($id)->with('student')->first();
        break;
      case 'teacher':
        $user = User::whereId($id)->with('teacher')->first();
        break;
      default:
        break;
    }

    return $user;
  }

  public function store($data)
  {
    $role = $data['role'];
    try {
      DB::transaction(function () use ($data, $role) {
        $user = User::create([
          'name' => $data['name'],
          'email' => $data['email'],
          'email_verified_at' => now(),
          'password' => $data['password'],
          'role' => $data['role'],
        ]);

        if ($role == 'student') {
          $user->student()->create([
            'nim' => $data['nim'],
            'major_id' => $data['major'],
            'nama' => $data['name'],
            'tempat_lahir' => $data['tempat'],
            'tgl_lahir' => $data['tgl'],
            'no_hp' => $data['no_hp'],
            'jk' => $data['jk']
          ]);
        } elseif ($role == 'teacher') {
          $user->teacher()->create([
            'nidn' => $data['nidn'],
            'major_id' => $data['major'],
            'nama' => $data['name'],
            'tempat_lahir' => $data['tempat'],
            'tgl_lahir' => $data['tgl'],
            'no_hp' => $data['no_hp'],
            'jk' => $data['jk']
          ]);
        }
      });
    } catch (\Throwable $th) {
      $res = [
        'success' => false,
        'data' => null,
        'msg' => $th,
        'code' => 500
      ];

      return $res;
    }

    switch ($role) {
      case 'admin':
        $data = User::whereRole('admin')->get();
        break;
      case 'student':
        $data = User::whereRole('student')->get();
        break;
      case 'teacher':
        $data = User::whereRole('teacher')->get();
        break;
      default:
        $data = null;
        break;
    }

    $res = [
      'success' => true,
      'data' => $data,
      'msg' => 'Data berhasil disimipan',
      'code' => 201
    ];

    return $res;
  }

  public function update($data, $role)
  {
    $user = User::whereId($data['id'])->first();

      if ($data['password']) {
        $data['password'] = Hash::make($data['password']);
        $user->update($data);
      } else {
        if ($user->role == 'admin') {
          $admin = User::where('role', 'admin')->where('status', 'aktif')->count();
          if ($admin < 2 && $data['status'] == "nonaktif") {
            $res = [
              'success' => false,
              'data' => null,
              'msg' => 'Minimal 1 admin harus aktif.',
              'code' => 422
            ];
            return $res;
          } else {
            unset($data['password']);
            $user->update($data);
          }
        } else {
          unset($data['password']);
          $user->update($data);
        }
      }

      // update table teacher
      switch ($role) {
        case 'teacher':
          $data = [
            'major_id' => $data['major'],
            'nama' => $data['name'],
            'tempat_lahir' => $data['tempat'],
            'tgl_lahir' => $data['tgl'],
            'no_hp' => $data['no_hp'],
            'jk' => $data['jk']
          ];
          $user->teacher()->update($data);
          break;
        case 'student':
          $data = [
            'major_id' => $data['major'],
            'nim' => $data['nim'],
            'nama' => $data['name'],
            'tempat_lahir' => $data['tempat'],
            'tgl_lahir' => $data['tgl'],
            'no_hp' => $data['no_hp'],
            'jk' => $data['jk']
          ];
          $user->student()->update($data);
          break;
        default:
          break;
      }

      // Send back response
      if ($user) {
        if ($role == 'student') {
          $user = DB::table('users')
                    ->join('students', 'users.id', '=', 'students.user_id')
                    ->select('users.*', 'students.nama', 'students.nim')
                    ->get();
        } elseif ($role == 'teacher') {
          $user = DB::table('users')
                  ->join('teachers', 'users.id', '=', 'teachers.user_id')
                  ->join('majors', 'majors.id', '=', 'teachers.major_id')
                  ->select('users.*', 'teachers.nama', 'teachers.nidn', 'majors.*')
                  ->get();
        } else {
          $user = User::whereRole($role)->get();
        }

        $res = [
          'success' => true,
          'data' => $user,
          'msg' => 'Data berhasil disimpan',
          'code' => 201
        ];

        return $res;

      } else {

        $res = [
          'success' => false,
          'data' => null,
          'msg' => 'Data gagal disimpan',
          'code' => 500
        ];
        return $res;

      }
  }

  public function showSim($id)
  {
    $user = User::whereId($id)->first();
    $student = DB::table('students')->select('username_sim', 'password_sim')->where('user_id', $user->id)->first();

    if ($user) {
      if ($student->username_sim) {
        return [
          'success' => true,
          'data' => $student,
          'msg' => 'Data ditemukan',
          'code' => 200
        ];
      } else {
        return [
          'success' => true,
          'data' => [
            'username_sim' => '072026'.$user->student->nim
          ],
          'msg' => 'Data belum dibuat',
          'code' => 200
        ];
      }
    } else {
      return [
        'success' => false,
        'data' => null,
        'msg' => 'Data tidak ditemukan',
        'code' => 404
      ];
    }
  }

  public function updateSim($data)
  {
    $user = User::findOrFail($data['id']);
    $user->student->username_sim = $data['username_sim'];
    $user->student->password_sim = $data['password_sim'];
    $user->student->save();

    return [
      'success' => true,
      'data' => null,
      'msg' => 'Data berhasil diupdate',
      'code' => 201
    ];
  }
}
