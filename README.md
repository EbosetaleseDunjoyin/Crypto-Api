
# Crypto API

## About Project

This is a simple crypto wallet API that maintains crypto accounts for users across multiple coins, such as Bitcoin, Ethereum, and Litecoin. It provides endpoints to get user balances, transfer funds to another user through email, get a list of transactions, and convert the balance from one coin to another using a live market rate.

This API was built with Laravel 10.






## Installation instructions.

1. Clone this repository:
   ```bash
   git clone projectUrl
   ```

2. Install Composer dependencies
     ```bash
      composer install
   ```

3. Create a copy of the .env.example file and rename it to .env
     ```bash
      cp .env.example .env
   ```

4. Generate an application key
     ```bash
      php artisan key:generate
   ```
5. Configure your .env file with your database credentials and other settings.

6. Run the database migrations
    ```bash
      php artisan migrate
   ```
7. Run the scheduler to get Crypto Coins from CoinGecko
      ```http
      GET https://api.coingecko.com/api/v3/coins/list
      ```
   Details in the command directory
    ```bash
      php artisan schedule:run
   ```
8. Start the Laravel development server
   ```bash
      php artisan serve
   ```
8. Access documentation route app_url/documentation

You are good to go.


## Design Explaination

The API uses Laravel and Sanctum for authentication. Users can log in, register, and reset their password. User data can be obtained using user routes. Coin data is fetched from the CoinGecko API and stored in the database, with the schedule set to refresh every thirty minutes for accurate data. Coins can be transferred between users on the platform, with the balance represented in USD for easier calculation. Coin swapping can only occur between users, with the market price data from CoinGecko used for calculations.

## Conclucion

This API can be further improved in terms of data fetching, service files, and validations.



