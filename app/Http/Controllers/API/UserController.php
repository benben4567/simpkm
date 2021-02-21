<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index($role)
    {
      if ($role == 'teacher' || $role == 'student') {
        $users = User::with($role)->whereRole($role)->get();
        if ($users) {
          return response()->json([
            'success' => true,
            'data' => $users
          ], 200);
        } else {
          return response()->json([
            'success' => true,
            'msg' => 'User not found'
          ], 404);
        }
      } else {
        return response()->json([
          'success' => false,
          'msg' => 'Role is requried'
        ], 422);
      }
    }

    public function show($id)
    {
      $user = User::find($id);
      if ($user) {
        $role = $user->role;
        if ($role != 'admin') {
          $user = User::with($role)->whereId($id)->get();
        } else {
          $user = User::find($id)->get();
        }

        if ($user) {
          return response()->json([
            'success' => true,
            'data' => $user
          ], 200);
        } else {
          return response()->json([
            'success' => false,
            'msg' => "User not found"
          ], 404);
        }
      } else {
        return response()->json([
          'success' => false,
          'msg' => "User not found"
        ], 404);
      }
    }
}
