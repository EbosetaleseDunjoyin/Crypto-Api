<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;



class PageController extends Controller
{
    //

    public function documentation_page() : View {

        return view('documentation'); 
        
    }
    public function login_page() : JsonResponse {

        return response()->json([
            'status' => false,
            'message' => 'Unauthorised',
        ], 401);
        
    }
}
