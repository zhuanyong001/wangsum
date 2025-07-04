<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use App\Services\CoinMarketCapService;
use Illuminate\Support\Facades\DB;

class QueryCoinPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coin-price-query';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Query the price of a cryptocurrency from CoinMarketCap';

    protected $coinMarketCapService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CoinMarketCapService $coinMarketCapService)
    {
        parent::__construct();
        $this->coinMarketCapService = $coinMarketCapService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $symbols = Currency::where('status', 1)->pluck('code')->toArray();
        $prices = $this->coinMarketCapService->getCoinPrices($symbols);

        foreach ($prices as $key => $value) {
            if ($value == null) {
                continue;
            }
            DB::update('update currencies set price = ? * price_ratio ,change_24h = ?  where code = ?', [$value['price'], $value['percent_change_24h'], $key]);
        }


        // if ($price !== null) {
        //     $this->info("The price of {$symbol} is $${price}");
        // } else {
        //     $this->error("Could not retrieve the price for {$symbol}");
        // }
    }
}
