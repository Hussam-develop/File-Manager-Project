<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ManagerFilesTrait
{
    public function uploadFile($file, $path)
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $fileUrl = $file->storeAs($path, $fileName, ['disk' => 'uploads']);
        return $fileUrl;
    }


    public function deleteFile($filePath)
    {
        if (Storage::disk('uploads')->exists($filePath)) {
            Storage::disk('uploads')->delete($filePath);
            return true;
        }

        return false;
    }
}
