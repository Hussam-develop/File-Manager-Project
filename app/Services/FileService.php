<?php

namespace App\Services;

use App\Models\Action;
use App\Models\File;
use App\Repositories\FileRepository;
use App\Repositories\FileRepositoryInterface;
use App\Traits\ManagerFilesTrait;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class FileService
{
    use ManagerFilesTrait;

    public function __construct(protected FileRepositoryInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }
    public function getById($id)
    {
        return $this->fileRepository->getById($id);
    }

    public function getFilePath($id)
    {
        return $this->fileRepository->filePath($id);
    }

    public function getFileActions($id)
    {
        return $this->fileRepository->fileActions($id);
    }
    /**
     * get files by ids
     */
    public function getFilesByIds(array $ids)
    {
        return $this->fileRepository->getByIds($ids);
    }

    public function checkInFiles(array $fileIds, $userId)
    {
        foreach ($fileIds as $fileId) {
            $this->fileRepository->updateStatus($fileId, ['checkStatus' => 'reserved']);
            $this->fileRepository->createAction($fileId, $userId, 'check-in');
        }
    }
    public function ApproveFiles(array $fileIds)
    {
        foreach ($fileIds as $fileId) {
            $this->fileRepository->updateStatus($fileId, ['status' => 1]);
        }
    }

    public function getFilesByStatus($status)
    {
        return $this->fileRepository->getFilesByStatus($status);
    }

    public function storeFile($request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $fileUrl = $this->uploadFile($file, 'files'); // path and file uploaded
            $fileName = $file->getClientOriginalName();
            auth()->user()->isAdmin ? $status = 1 : $status = 0;
            $data = [
                'name' => $fileName,
                'group_id' => $request->input('group_id'),
                'user_id' => auth()->user()->id,
                'file' => $fileUrl,
                'status' => $status
            ];
            $this->fileRepository->create($data);
        }
    }

    public function getFilesByCheckStatus($userId, $status)
    {
        return $this->fileRepository->getFilesByCheckStatus($userId, $status);
    }

    public function deleteFile($id)
    {
        $file = $this->fileRepository->getById($id);
        Storage::disk('uploads')->delete($file->path);
        $this->fileRepository->delete($id);
    }
}
