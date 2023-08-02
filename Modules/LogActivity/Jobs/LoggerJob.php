<?php

namespace Modules\LogActivity\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\File;

class LoggerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected mixed $logInfo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($logInfo)
    {
        $this->logInfo = $logInfo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        if(env('LOG_ACTIVITY_FILE')) {
            $logString = json_encode($this->logInfo, JSON_UNESCAPED_SLASHES) . PHP_EOL;
            File::append(storage_path('/logs/activity.log'), $logString);
        }

        if(env('LOG_ACTIVITY_DATABASE')) {
            // TODO: save logs in database
        }
    }
}
