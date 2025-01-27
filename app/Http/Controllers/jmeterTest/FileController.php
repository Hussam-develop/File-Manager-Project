<?php

namespace App\Http\Controllers\jmeterTest;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct(protected FileService $fileService)
    {
    }
    public function activeFiles()
    {
        $files = $this->fileService->getFilesByStatus(1); // 0:pending files || 1:active files

        $data = [
            'files' => $files,

        ];
        return response()->json($data);
    }

    public function createGroup(Request $request)
    {
        // 0:pending files || 1:active files

       Group::create($request->all());
        return response()->json('success store data from csv',200);
    }
}
