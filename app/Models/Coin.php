<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coin extends Model
{
    use HasFactory;

    protected $fillable = [
        'coin_id',
        'symbol',
        'name',
        'platforms'
        
    ];

    public function cryptoAccounts()
    {
        return $this->hasMany(CryptoAccount::class);
    }

    public static function get_coin_data($coin){
        $response = Http::timeout(30)->get("https://api.coingecko.com/api/v3/coins/{$coin}", [
            'tickers' => 'false',
            'market_data' => 'true',
            'community_data' => 'false',
            'developer_data' => 'true',
        ]);

        $data = $response->json();

        $coin = $response->json();

        return $coin;
    }
}
