<?php

namespace App\Jobs;

use App\Repositories\FileRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MultiApprove implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $fileIds;
    /**
     * Create a new job instance.
     */
    public function __construct(array $fileIds, int $userId)
    {
        $this->fileIds = $fileIds;
    }
    /**
     * Execute the job.
     */
    public function handle(FileRepository $fileRepository): void
    {
        foreach ($this->fileIds as $fileId) {
            $fileRepository->updateStatus($fileId, ['status' => 1]);
        }

    }
}
