<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nidn' => 'required|numeric|unique:teachers,nidn',
                'name' => 'required|string',
                'major' => 'required|exists:majors,id',
                'email' => 'required|email',
                'no_hp' => 'required|digits_between:10,13',
            ]);
            
            if ($validator->fails()) {
                return ResponseFormatter::error($validator->errors(), "Data yang diberikan tidak valid. Silahkan coba lagi.", 422);
            }
            
            $user = User::create([
                'email' => $request->input('email'),
                'username' => $request->input('nidn'),
                'name' => trim($request->input('name')),
                'password' => Hash::make($request->input('nidn')),
                'no_hp' => $request->input('no_hp'),
                'email_verified_at' => now(),
            ]);

            $user->assignRole('teacher');

            $user->teacher()->create([
                'major_id' => $request->input('major'),
                'nidn' => $request->input('nidn'),
                'nama' => trim($request->input('name')),
                'no_hp' => $request->input('no_hp'),
            ]);
            
            return ResponseFormatter::success(null, 'Data dosen berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error("TeacherController@store: {$e->getMessage()}");
            return ResponseFormatter::error(null, 'Tidak dapat menyimpan data dosen. Kesalahan Server.', 500);
        }
    }
    
    public function update(Request $request)
    {
        
        try {
            $id = $request->input('id');
            $user = User::find($id);
            
            if ($user == null) {
                return ResponseFormatter::error(null, 'Data dosen tidak ditemukan.', 404);
            }
            
            $teacher = $user->teacher;
            
            $validator = Validator::make($request->all(), [
                'nidn' => 'required|numeric|unique:teachers,nidn,' . $teacher->id,
                'name' => 'required|string',
                'major' => 'required|exists:majors,id',
                'email' => 'required|email',
                'no_hp' => 'required|digits_between:10,13',
            ]);
            
            if ($validator->fails()) {
                return ResponseFormatter::error($validator->errors(), "Data yang diberikan tidak valid. Silahkan coba lagi.", 422);
            }
            
            $teacher->update([
                'major_id' => $request->input('major'),
                'nidn' => $request->input('nidn'),
                'nama' => trim($request->input('name')),
                'no_hp' => $request->input('no_hp'),
            ]);
            
            $user = $teacher->user()->update([
                'email' => $request->input('email'),
                'name' => trim($request->input('name')),
                'username' => $request->input('nidn'),
                'no_hp' => $request->input('no_hp'),
            ]);
            
            return ResponseFormatter::success(null, 'Data dosen berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("TeacherController@update: {$e->getMessage()}");
            return ResponseFormatter::error(null, 'Data dosen gagal diperbarui. Kesalahan Server.', 500);
        }
    }
    
    public function resetPassword(Request $request)
    {
        try {
            $user = User::find($request->id);
            if ($user == null) {
                return ResponseFormatter::error(null, 'Data dosen tidak ditemukan.', 404);
            }
            
            $teacher = $user->teacher;
            $user->update([
                'password' => Hash::make($teacher->nidn),
            ]);
            
            return ResponseFormatter::success(null, 'Password dosen berhasil direset.');
        } catch (\Exception $e) {
            Log::error("TeacherController@resetPassword: {$e->getMessage()}");
            return ResponseFormatter::error(null, 'Password dosen gagal direset. Kesalahan Server.', 500);
        }
    }
    
    public function toggle(Request $request)
    {
        try {
            $user = User::find($request->id);
            if ($user == null) {
                return ResponseFormatter::error(null, 'Data dosen tidak ditemukan.', 404);
            }
            
            $user->status = $user->status == 'aktif' ? 'nonaktif' : 'aktif';
            $user->save();
            
            return ResponseFormatter::success(null, 'Status dosen berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("TeacherController@toggle: {$e->getMessage()}");
            return ResponseFormatter::error(null, 'Status dosen gagal diperbarui. Kesalahan Server.', 500);
        }
    }
}
