<?php

namespace App\Jobs;

use App\Repositories\FileRepository;
use App\Services\FileService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MultiCheckInJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $fileIds;
    protected $userId;
    /**
     * Create a new job instance.
     */
    public function __construct(array $fileIds, int $userId)
    {
        $this->fileIds = $fileIds;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(FileRepository $fileRepository): void
    {
        foreach ($this->fileIds as $fileId) {
            $fileRepository->updateStatus($fileId, [
                'user_id' => $this->userId,
                'checkStatus' => 'reserved',
            ]);
            $fileRepository->createAction($fileId, $this->userId, 'check-in');
        }

    }
}
