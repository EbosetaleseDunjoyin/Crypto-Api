<?php

namespace App\Http\Controllers;

use App\Models\Coin;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\CryptoAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CryptoAccountSwapCoinRequest;
use App\Http\Requests\CryptoAccountTransactRequest;
use App\Services\CryptoAccountService;

class CryptoAccountController extends Controller
{
    //

    public function get_coins(Request $request){

        $per_page = $request->query('per_page', 100);

        $coins = Coin::paginate($per_page);
        $coinsCount = Coin::count();

        return response()->json([
            'status' => true,
            'message' => 'Coins have been retrieved.',
            'meta' => [
                "items" => $coinsCount,
                "per_page" => $per_page
            ],
            'coins' => $coins
        ], 200);
    }

    public function send_crypto_coin(CryptoAccountTransactRequest $request, CryptoAccountService $cryptoAccountService)
    {
        $response = $cryptoAccountService->send_crypto_coin($request);

        return $response;
    }

    public function swap_crypto_coin(CryptoAccountSwapCoinRequest $request, CryptoAccountService $cryptoAccountService)
    {
        $response = $cryptoAccountService->swap_crypto_coin($request);

        return $response;
    }




    public function create_crypto_account(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users',
            'coin_id' => 'required|exists:coins',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email',$request->email)->first();
        $checkAccount = $user->cryptoAccounts()->where('coin_id',$request->coin_id)->first();

        if($checkAccount){
            return response()->json([
                'status' => true,
                'message' => 'Crypto account created successfully',
                'crypto_account' => $checkAccount,
            ], 200);
        }

        try{
            $cryptoAccount = CryptoAccount::create([
                'email' => $request->email,
                'coin_id' => $request->coin_id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Crypto account created successfully',
                'crypto_account' => $cryptoAccount,
            ], 200);
        }catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Transaction failed. Please try again later.',
            ], 500);
        }
        
    }



}
