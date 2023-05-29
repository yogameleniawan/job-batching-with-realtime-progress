<?php

namespace YogaMeleniawan\JobBatchingWithRealtimeProgress;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Jobs\MasterJob;

class RealtimeJobBatch {
    public function runJob(string $name, $service, ?array $option, string $channel_name, string $broadcast_name): object {
        $batch = Bus::batch(
            new MasterJob(
                service: $service,
                option: $option,
                channel_name: $channel_name,
                broadcast_name: $broadcast_name
            )
        )
        ->name("Generate - {$name}") // it will show in your job batch name as master job
        ->dispatch();

        return $batch;
    }
}
