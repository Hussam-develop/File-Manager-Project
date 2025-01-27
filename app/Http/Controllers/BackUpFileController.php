<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\BackUpFile;
use App\Models\File;
use App\Services\FileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackUpFileController extends Controller
{
    public function __construct(protected FileService $fileService)
    {
        $this->fileService = $fileService;
    }

/**
 *
 * previous versions file
 *
 */

    public function previousVersions($id)
    {
        $mainfile = $this->fileService->getById($id);
        $previousFiles = $mainfile->backupFiles()->paginate(8);
        return view('files.previous', compact('previousFiles', 'mainfile'));
    }

    /**
     *
     * restore file
     *
     */

    public function restoreFile($id)
    {
        $backupFile = BackUpFile::find($id); // Assuming you pass the backup ID
        try {
            DB::beginTransaction();

            if ($backupFile) {
                $backupFileName = uniqid() . $backupFile->backup_file;

                // Restore the backup file to its original location
                $originalFilePath = 'files/' . $backupFileName; // Adjust the path as necessary

                $originalFile = File::find($backupFile->file_id);

                // Move the backup file to the original location
                Storage::disk('uploads')->move($backupFile->backup_path, $originalFilePath);

                // Update the original file record in the database
                $originalFile->update([
                    'name' => $backupFile->backup_file,
                    'checkStatus' => 'free', // or any other status you want to set
                    'file' => $originalFilePath,
                ]);


                $backupFile->delete();

                DB::commit();

                return redirect()->route('admin.dashboard.files.checkedIn')->with('success', 'File restored successfully.');
            } else {
                return redirect()->back()->with('warning', 'Backup file not found.');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('warning', $e->getMessage());
        }
    }

    public function downloadOldVersion($id)
    {
        $old_file = BackUpFile::find($id);
        return response()->download($old_file->backup_path, uniqid() . '.txt');
    }


    /*  public function restoreFilee($id)
    {
        $file = BackUpFile::find($id); // ID of the original file
        $backupFileName = $file->backup_path; // Name of the backup file

        // Define the paths
        $backupPath = public_path($backupFileName);
        $originalFilePath = public_path($backupFileName); // Adjust according to your original files location
        dd($backupPath);
        try {
            DB::beginTransaction();

            // Check if the backup file exists
            if (file_exists($backupPath)) {
                // Restore the backup file to the original location
                Storage::move($backupPath, $originalFilePath);
                // move from backup folder to files folder
                // Update the original file record in the database
                $file->update([
                    // 'checkStatus' => 'checked-in', // Set the status accordingly
                    'file' => $originalFilePath
                ]);

                DB::commit();

                return redirect()->route('admin.dashboard.files.checkedIn')->with('success', 'File restored successfully.');
            } else {
                return redirect()->back()->with('warning', 'Backup file not found.');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('warning', $e->getMessage());
        }
    }
 */
}
