<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Group;
use App\Models\Action;
use App\Models\BackUpFile;
use App\Services\FileService;
use App\Traits\ManagerFilesTrait;
use App\Traits\TransactionalTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use SebastianBergmann\Diff\Diff;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

class FileController extends Controller
{
    use ManagerFilesTrait, TransactionalTrait;


    public function __construct(protected FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    /**
     * get pending files
     */
    public function pendingFiles()
    {
        $files = $this->fileService->getFilesByStatus(0); // 0:pending files || 1:active files
        return view('files.pendingFiles', compact(['files']));
    }

    /**
     * get active files
     */
    public function activeFiles()
    {
        $files = $this->fileService->getFilesByStatus(1); // 0:pending files || 1:active files

        return view('files.activeFiles', compact(['files']));
    }

    /**
     * store file
     */
    public function store(Request $request)
    {
        try {

            $this->fileService->storeFile($request);
            Session::flash('success', 'File uploaded successfully');
            return redirect()->back();
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
            return  redirect()->back();
        }
    }
    /**
     * download file
     */
    public function downloadFile($id)
    {
        $path = $this->fileService->getFilePath($id);
        Session::flash('success', 'File downloaded successfully');
        return response()->download($path);
    }

    /**
     * multiple check-in
     */
    public function multiCheckIn(Request $request)
    {
        $fileIds = $request->input('fileIds');
        $this->executeInTransaction(function () use ($fileIds) {
            $this->fileService->checkInFiles($fileIds, auth()->user()->id);
        });

        Session::flash('success', 'Files reserved successfully');
        return redirect()->back();
    }
    /**
     * multiple approve
     */
    public function multiApprove(Request $request)
    {
        $fileIds = $request->input('fileIds');
        $files = $this->fileService->getFilesByIds($fileIds);

        $this->executeInTransaction(function () use ($fileIds) {
            $this->fileService->ApproveFiles($fileIds);
        });

        Session::flash('success', 'Files approved successfully');
        return redirect()->back();
    }
    /**
     * file's actions
     */
    public function fileActions($id)
    {
        $file = $this->fileService->getById($id);
        $fileActions = $this->fileService->getFileActions($id);
        return view('files.fileActions', compact(['fileActions', 'file']));
    }
    /**
     * checked-in Files
     */
    public function checkedInFiles()
    {
        $files = $this->fileService->getFilesByCheckStatus(auth()->user()->id, 'reserved');

        return view('files.checkedInFiles', compact(['files']));
    }
    /**
     *   check out file
     */
    public function checkOut(Request $request)
    {

        $oldFile = File::find($request->file_id);
        try {
            DB::beginTransaction();
            ///

            // Create a backup of the old file in the public directory
            if ($oldFile) {
                $backupFileName = Carbon::now(). '' . $oldFile->name;

                $backupPath = 'backups/' . $backupFileName; // Adjust the path for public storage

                // $backupPath = str_replace('/', '\\', $backupPath);
                // dd($backupPath);

                // Ensure the backups directory exists
                /*  if (!file_exists(public_path('backups'))) {
                    mkdir(public_path('backups'), 0755, true);
                } */
                // Copy the old file to the backups directory
                Storage::disk('uploads')->move($oldFile->file, $backupPath);
                // Optionally, you can also store the backup path in the database
                BackUpFile::create([
                    'file_id' => $oldFile->id,
                    'backup_file' => $backupFileName,
                    'backup_path' => $backupPath,
                ]);
            }
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileUrl = $this->uploadFile($file, 'files'); // Corrected path for uploaded files
                $fileName = $file->getClientOriginalName();
                /// dif
                //$oldContent = file_get_contents(public_path().'\\'.$backupPath); // Read the content of the old file
                // $newContent = file_get_contents(public_path().'\\'.$fileUrl); // Read the content of the new file
                //$oldLines = explode("\n", $oldContent);
                // $newLines = explode("\n", $newContent);
                // $differ = new Differ(new UnifiedDiffOutputBuilder);
                // $diffOutput = $differ->diff($oldLines, $newContent);
                //end dif
                // Update the old file information
                $oldFile->update([
                    'name' => $fileName,
                    'checkStatus' => 'free',
                    'file' => $fileUrl,
                ]);
                // Log the action
                Action::create([
                    'user_id' => auth()->user()->id,
                    'file_id' => $oldFile->id,
                    'action' => 'check-out',
                ]);
            }
            ///
            DB::commit();
            $group = Group::find($oldFile->group_id);
            $files = $group->files()->paginate(9);
            // Session::flash('diff', $diffOutput);
            return redirect()->route('admin.dashboard.files.checkedIn', compact('files', 'group'));
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('warning', $e->getMessage());
            return redirect()->back(); // Ensure to redirect back on error
        }

    }


    public function destroy(File $file)
    {
        $this->fileService->deleteFile($file);
        Session::flash('success', 'File deleted successfully');
        return redirect()->route('files.index');
    }
}
