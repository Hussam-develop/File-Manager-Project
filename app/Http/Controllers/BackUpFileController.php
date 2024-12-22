<?php

namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\Request;

class BackUpFileController extends Controller
{
    public function __construct(protected FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function previousVersions($id)
    {
        $mainfile = $this->fileService->getById($id);
        $previousFiles = $mainfile->backupFiles()->paginate(8);
        return view('files.previous', compact('previousFiles', 'mainfile'));
    }
    public function recoverFile($id)
    {

    }
    public function downloadOldVersion($id)
    {
        
    }
}
