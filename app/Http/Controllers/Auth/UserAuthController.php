<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\UserResetMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;

class UserAuthController extends Controller
{
    //

    public function register(UserRegisterRequest $request): JsonResponse{
        try{
            $data = $request->validated(); 

            $user = User::create($data);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'user' => $user->load("cryptoAccounts")
            ], 200);
         } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
         }

    }


    public function login(UserLoginRequest $request): JsonResponse{
        try{
            $validatedData = $request->validated();

            // Determine type email or phone_no
            $type = filter_var($validatedData['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_no';

            // Retrieve user based on username type
            $user = User::where($type, $validatedData['username'])->first();

            if (!$user || !Hash::check($validatedData['password'], $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials.',
                ], 401);
            }


            $token = $user->currentAccessToken();

           
            if (!$token) {
                $token = $user->createToken("API TOKEN")->plainTextToken;
            }

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $token,
                'user' => $user->load("cryptoAccounts")
            ], 200);

         } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
         }

    }


    
    public function logout(): JsonResponse
    {
        try{
            auth()->user()->tokens()->delete();
            // auth()->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => true,
                "message" => "logged out"
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
         }
    }

    public function resetPassword(Request $request): JsonResponse
    {
        try{
           

            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email|exists:users,email',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            // Generate new password
            $newPassword = Str::random(20); // Generate a random password
            $user->password = Hash::make($newPassword);
            $user->save();

            $data = [
                "email" => $request->email,
                "password" => $newPassword
            ];

            Mail::to($user->email)->send(new UserResetMail($data));

            return response()->json([
                'status' => true,
                "message" => "Account password has been reset."
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
         }
    }




    public function TestAPI(): JsonResponse{
        try{
    
            return response()->json([
                'status' => true,
                'message' => 'Access Granted.',
            ], 201);
    
         } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
         }
    
    }
}
