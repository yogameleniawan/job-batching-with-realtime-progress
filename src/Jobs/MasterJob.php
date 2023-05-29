<?php

namespace YogaMeleniawan\JobBatchingWithRealtimeProgress\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class MasterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $timeout = 10000000;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private $service,
        private $option,
        private $channel_name,
        private $broadcast_name
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $batch = Bus::batch([])
        ->name($this->batch()->id)
        ->onQueue('default')
        ->dispatch();

        foreach($this->option as $key => $value) {
            $batch->add(new BatchJob(
                channel_name: $this->channel_name,
                broadcast_name: $this->broadcast_name
                ),
            );
        }
    }
}