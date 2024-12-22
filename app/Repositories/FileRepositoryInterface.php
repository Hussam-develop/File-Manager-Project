<?php

namespace App\Repositories;

interface FileRepositoryInterface
{
    public function create(array $data);
    public function getById($id);
    public function filePath($id);
    public function fileActions($id);
    public function update($id, array $data);
    //
    public function getByIds(array $ids);
    public function updateStatus($fileId, array $data);
    public function createAction($fileId,$userId,$action);

    //
    public function delete($id);
    public function getFilesByStatus($status);
    public function getFilesByCheckStatus($userId,$checkStatus);
   // public function getCheckedInFiles($userId);
}
