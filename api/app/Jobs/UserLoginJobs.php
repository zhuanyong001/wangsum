<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserLoginIp;
use App\Services\IPRegionService;
use App\Services\QqwryServer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserLoginJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $ip;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $ip)
    {
        //
        $this->user = $user;
        $this->ip = $ip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $model = UserLoginIp::create([
            'user_id' => $this->user->id,
            'ip' => $this->ip,
            'region' =>  ''
        ]);

        try {
            $server = new QqwryServer();
            $model->region  = $server->q($this->ip);
        } catch (\Exception $e) {
            info($e->getMessage());
            $model->region = IPRegionService::getRegion($this->ip);
        }
        $model->save();

        //线上查询ip地址

    }
}
