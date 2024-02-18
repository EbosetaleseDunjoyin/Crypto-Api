<?php

namespace App\Services;

use App\Models\Coin;
use App\Models\User;
use App\Models\Transaction;
use App\Models\CryptoAccount;
use Illuminate\Support\Facades\DB;


class CryptoAccountService{

    public function __construct(){

    }




    public function send_crypto_coin(object $request)
    {
        $user = User::where('email', $request->user_email)->with("cryptoAccounts")->first();
        $receiver = User::where('email', $request->receiver_email)->with("cryptoAccounts")->first();
        $amount = $request->amount;
        $coinId = $request->coin_id;

        $userCryptoAccount = $user->cryptoAccounts()->firstOrCreate(['coin_id' => $coinId]);
        $receiverCryptoAccount = $receiver->cryptoAccounts()->firstOrCreate(['coin_id' => $coinId]);

        if ($userCryptoAccount->balance < $amount) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient balance.',
            ], 400);
        }

        try {
            DB::beginTransaction();

            $userCryptoAccount->decrement('balance', $amount);
            $receiverCryptoAccount->increment('balance', $amount);

            Transaction::create([
                'user_id' => $userCryptoAccount->user_id,
                'crypto_account_id' => $userCryptoAccount->id,
                'coin_id' => $coinId,
                'amount' => $amount,
                'transaction_type' => 'debit'
            ]);

            Transaction::create([
                'user_id' => $receiverCryptoAccount->user_id,
                'crypto_account_id' => $receiverCryptoAccount->id,
                'coin_id' => $coinId,
                'amount' => $amount,
                'transaction_type' => 'credit'
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Coins transferred successfully.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Transaction failed. Please try again later.',
            ], 500);
        }
    }

    public function swap_crypto_coin(object $request)
    {
        $user = User::where('email', $request->email)->with("cryptoAccounts")->first();
        $amount = $request->amount;
        $coinId = $request->coin_id;
        $recevingCoinId = $request->receiver_coin_id;

        if ($coinId === $recevingCoinId) {
            return response()->json([
                'status' => false,
                'message' => 'Issue occured, swapping the same coin.',
            ], 400);
        }

        $userCryptoAccount = $user->cryptoAccounts()->firstOrCreate(['coin_id' => $coinId]);
        $receiverCryptoAccount = $user->cryptoAccounts()->firstOrCreate(['coin_id' => $recevingCoinId]);

        $receiver_coin_data = Coin::get_coin_data($recevingCoinId);

        $cost_price = $receiver_coin_data['market_data']['current_price']['usd'];
        $balance = $userCryptoAccount->balance;

        if(!$receiver_coin_data){
            return response()->json([
                'status' => false,
                'message' => 'Issue retriving coin data.',
            ], 400);
        }

        if ($userCryptoAccount->balance < $amount) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient balance.',
            ], 400);
        }


        if ($cost_price) {
            try {
                $balance = $amount / $cost_price;

                DB::beginTransaction();

                $userCryptoAccount->decrement('balance', $amount);
                $receiverCryptoAccount->increment('balance', $balance);

                Transaction::create([
                    'user_id' => $userCryptoAccount->user_id,
                    'crypto_account_id' => $userCryptoAccount->id,
                    'coin_id' => $request->coin_id,
                    'amount' => $amount,
                    'transaction_type' => 'debit'
                ]);

                Transaction::create([
                    'user_id' => $receiverCryptoAccount->user_id,
                    'crypto_account_id' => $receiverCryptoAccount->id,
                    'coin_id' => $request->receiver_coin_id, // Use receiver coin ID here
                    'amount' => $balance,
                    'transaction_type' => 'credit'
                ]);

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Coins swapped successfully.',
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Transaction failed. Please try again later.',
                ], 500);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'An issue occurred',
            ], 400);
        }
    }
    

}