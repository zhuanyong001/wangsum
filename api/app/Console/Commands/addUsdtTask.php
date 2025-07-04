<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserAsset;
use Illuminate\Console\Command;

class addUsdtTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-usdt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $users = User::get();
        // foreach ($users as $user) {
        //     UserAsset::firstOrCreate(['user_id' => $user->id, 'currency_id' => 1], ['amount' => 10000]);
        // }
        return 0;
    }
}
