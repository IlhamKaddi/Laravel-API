<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    //register
      public function register(Request $request)
    {
        //validation rules------------------------------------------
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);


       // create user------------------------------------------------
       $user=User::create($validated);
   
     // return response-------------------------------------------
       return response()->json([
        'message'=>'User registered successfully',
        'user'=>$user
       ],201);

    
    }
    //login
  public function login(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (!auth()->attempt($validated)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    // $user = auth()->user();

    // return response()->json([
    //     'message' => 'Login successful',
    //     'user' => $user
    // ]);
    $user = auth()->user();

   $token = $user->createToken('auth_token')->plainTextToken;

return response()->json([
    'message' => 'Login successful',
    'user' => $user,
    'token' => $token
]);
}
//logout
public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logout successful'
    ]);
}

  
}
