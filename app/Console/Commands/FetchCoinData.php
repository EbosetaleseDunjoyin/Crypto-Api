<?php

namespace App\Console\Commands;


use App\Models\Coin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class FetchCoinData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-coins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all the coins';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $page = 1;


            // try{
                // $response = Http::get('https://api.coingecko.com/api/v3/coins/markets', [
                //     'vs_currency' => 'usd',
                //     'order' => 'market_cap_desc',
                //     'per_page' => 300,
                //     'page' => $page,
                //     'sparkline' => false,
                //     'locale' => 'en'
                // ]);
                $response = Http::get('https://api.coingecko.com/api/v3/coins/list', [
                    'include_platform' => true
                ]);

                $coins = $response->json();

                // if (count($coins) == 0) {
                //     $this->error('No data found');
                // }
                // Log::error($coins);
                
                $chunkedCoins = array_chunk($coins, 100);

                foreach ($coins as $coin) {
                // foreach ($coins as $coin) {
                    Coin::updateOrCreate(
                        ['coin_id' => $coin['id']],
                        [
                            'symbol' => $coin['symbol'],
                            'name' => $coin['name'],
                            'platforms' => $coin['platforms'],
                        ]
                    );
                }

                

                // Pause execution for 45 seconds
                sleep(45);
            // } catch (RequestException $e) {
            //     // Handle request exception, log the error, or retry the request
            //     $this->error('Request exception: ' . $e->getMessage());
            //     // Optionally, you can break the loop to stop further attempts
            //     // break;
            // } catch (\Exception $e) {
            //     // Handle other exceptions
            //     $this->error('Error: ' . $e->getMessage());
            //     // Optionally, you can break the loop to stop further attempts
            //     // break;
            // }
        

        $this->info('Coin data fetched successfully.');
    }
}
