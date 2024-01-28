<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class RefPermissionController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $permissions = Permission::all();
            return ResponseFormatter::success($permissions, 'Data permission berhasil diambil');
        }

        return view('pages.admin.permission');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'unique:permissions,name']
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null, $validator->errors(), 422);
        }

        try {
            $permission = Permission::create(['name' => $request->name]);
            return ResponseFormatter::success($permission, 'Permission berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error("RefPermissionController@store: {$e->getMessage()}");
            return ResponseFormatter::error(null, 'Permission gagal ditambahkan. Kesalahan server.', 500);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:permissions,id'],
            'name' => ['required', 'unique:permissions,name,' . $request->id]
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null, $validator->errors(), 422);
        }

        try {
            $permission = Permission::find($request->id);
            $permission->update(['name' => $request->name]);
            return ResponseFormatter::success($permission, 'Permission berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error("RefPermissionController@update: {$e->getMessage()}");
            return ResponseFormatter::error(null, 'Permission gagal diperbarui. Kesalahan Server.', 500);
        }
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:permissions,id']
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null, $validator->errors(), 422);
        }

        try {
            $permission = Permission::find($request->id);
            $permission->delete();
            return ResponseFormatter::success(null, 'Permission berhasil dihapus');
        } catch (\Exception $e) {
            Log::error("RefPermissionController@destroy: {$e->getMessage()}");
            return ResponseFormatter::error(null, 'Permission gagal dihapus. Kesalahan Server.', 500);
        }
    }
}
