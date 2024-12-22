<?php

namespace App\Repositories;

use App\Models\Action;
use App\Models\File;
use Illuminate\Support\Facades\Auth;

class FileRepository implements FileRepositoryInterface
{
    public function create(array $data)
    {
        return File::create($data);
    }

    public function getById($id)
    {
        return File::findOrFail($id);
    }

    public function filePath($id)
    {
        $file =$this->getById($id);
        return $file->file;
    }

    public function fileActions($id)
    {
        $file =$this->getById($id);
        return $file->actions()->paginate(8);
    }

    public function update($id, array $data)
    {
        $file =$this->getById($id);
        $file->update($data);
    }

    public function getByIds(array $ids)
    {
        return File::whereIn('id', $ids)->get();
    }

    public function updateStatus($fileId, array $data)
    {
        $file =$this->getById($fileId);
        return $file->update($data);
    }

    public function delete($id)
    {
        $file =$this->getById($id);
        $file->delete();
    }

    public function getFilesByStatus($status)
    {
        return File::where('status', $status)->paginate(8);
    }

    public function getFilesByCheckStatus($userId, $checkStatus)
    {
        return File::where('checkStatus', $checkStatus)
            ->where('user_id', $userId)
            ->where('status', 1)
            ->paginate(8);
    }
    public function createAction($fileId,$userId,$action){
        Action::create([
            'user_id' => $userId,
            'file_id' => $fileId,
            'action' => $action,
        ]);
    }

   /*  public function getCheckedInFiles($userId)
    {
        return File::where('checkStatus', 'reserved')
            ->where('user_id', $userId)
            ->where('status', 1)
            ->paginate(8);
    } */

}
