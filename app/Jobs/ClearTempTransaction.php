<?php

namespace App\Jobs;

use App\Models\TempTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ClearTempTransaction implements ShouldQueue
{
    use Queueable;

    protected $user_id;

    /**
     * Create a new job instance.
     */
    public function __construct($userId)
    {
        $this->user_id = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        TempTransaction::where('user_id', $this->user_id)->delete();
    }
}
