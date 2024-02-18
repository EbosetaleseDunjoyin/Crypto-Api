<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    //

    public function get_user(User $user){
        
        try{
            $data = User::where('id',$user->id)->with("cryptoAccounts")->first();

            if ($data) {
                return response()->json([
                    'status' => true,
                    'message' => 'User account details.',
                    'user' => $data
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'User account not found',
                ], 404);
            }

         } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'User account not found',
            ], 404);
        }


    }
}
