<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RefRoleController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $role = Role::all();
            return ResponseFormatter::success($role, 'Data role berhasil diambil');
        }

        return view('pages.admin.role');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'unique:roles,name']
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null, $validator->errors(), 422);
        }

        try {
            $role = Role::create([
                'name' => strtolower($request->name)
            ]);
            return ResponseFormatter::success($role, 'Role berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error("RefRoleController@store: {$e->getMessage()}");
            return ResponseFormatter::error(null, 'Grup pengguna gagal ditambahkan. Kesalahan Server.', 500);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:roles,id'],
            'name' => ['required', 'unique:roles,name,' . $request->id]
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null, $validator->errors(), 422);
        }

        try {
            $role = Role::find($request->id);
            $role->update(['name' => strtolower($request->name)]);
            return ResponseFormatter::success($role, 'Role berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error("RefRoleController@update: {$e->getMessage()}");
            return ResponseFormatter::error(null, 'Grup pengguna gagal diperbarui. Kesalahan Server.', 500);
        }
    }
}
