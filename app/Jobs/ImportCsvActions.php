<?php

namespace App\Jobs;

use App\Models\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportCsvActions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $rows;
    protected $userId;
    /**
     * Create a new job instance.
     */
    public function __construct($rows, int $userId)
    {
        $this->rows = $rows;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

            foreach ($this->rows as $row) {
                Action::create([
                    'file_id' => $row['file_id'],
                    'action' => $row['action'],
                    'created_at' => $row['created_at'],
                    'user_id' => $this->userId,
                ]);
            }
           
    }
}
