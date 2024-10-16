<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// class UserController extends Controller
// {
//     public function register(Request $request){
//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//             'phone_number' => $request->phone_number,
//         ]);
//         return response()->json([
//             'Message' => 'User Inserted'
//         ]);
//     }
// }
