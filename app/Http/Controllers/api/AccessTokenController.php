<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class AccessTokenController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone_number' => 'required',
        ]);
        // Generate verfication code
        $verificationCode = mt_rand(100000, 999999);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'verification_code' => $verificationCode,
            'is_verified' => false,
        ]);
            Log::info('New user registered:', [
            'name' => $user->name,
            'email' => $user->email,
            'verification_code' => $verificationCode,
        ]);
        $token = $user->createToken($request->device_name ?? $request->userAgent())->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }




    public function store(Request $request){
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
            'device_name' => 'string|max:255'
        ]);
        $user = User::where('email',$request->email)->first();
        if (!$user || !$user->is_verified) {
        return response()->json(['message' => 'Account not verified or invalid credentials.'], 401);
    }
        if($user && Hash::check($request->password,$user->password)){
            $devie_name = $request->post('device_name',$request->userAgent());
            $token = $user->createToken($devie_name);
            return response()->json([
                'token' => $token->plainTextToken,
                'user' => $user,
            ] , 201);
        }
        return response()->json([
            'Message' => 'Invalid Credntials'
        ] , 401);
    }



    public function logout($token = null){
        $user = Auth::guard('sanctum')->user();
        if(null === $token){
            $user->currentAccessToken()->delete();
            return;
        }
        $personalAccessToken = PersonalAccessToken::findToken($token);
        // check this token is specific for the user
        if($user->id == $personalAccessToken->tokenable_id &&
        get_class($user) == $personalAccessToken->tokenable_type)
        {
            $personalAccessToken->delete();
        }
        }

public function verifyCode(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'verification_code' => 'required|integer|digits:6',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    if ($user->verification_code === $request->verification_code) {
        $user->is_verified = true;
        $user->verification_code = null;
        $user->save();
        return response()->json(['message' => 'User verified successfully.'], 200);
    }

    return response()->json(['message' => 'Invalid verification code.'], 400);
}

}
