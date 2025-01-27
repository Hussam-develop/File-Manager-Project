<?php

namespace App\Http\Controllers;

use App\Events\NewActionEvent;
use App\Models\File;
use App\Models\Group;
use App\Models\Action;
use App\Models\BackUpFile;
use App\Repositories\FileRepository;
use App\Services\FileService;
use App\Traits\ManagerFilesTrait;
use App\Traits\TransactionalTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use SebastianBergmann\Diff\Diff;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

class FileController extends Controller
{
    use ManagerFilesTrait, TransactionalTrait;


    public function __construct(protected FileService $fileService, protected FileRepository $fileRepository)
    {
        $this->fileService = $fileService;
    }
    /**
     * get pending files
     */
    public function pendingFiles()
    {
        $files = $this->fileService->getFilesByStatus(0); // 0:pending files || 1:active files
        return view('files.pendingFiles', compact('files'));
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
        return redirect()->route('admin.dashboard.groups.index');
    }
    /**
     * multiple approve
     */
    public function multiApprove(Request $request)
    {
        try {
            $fileIds = $request->input('fileIds');
            $files = $this->fileService->getFilesByIds($fileIds);

            $this->executeInTransaction(function () use ($fileIds) {
                $this->fileService->ApproveFiles($fileIds);
            });

            Session::flash('success', 'Files approved successfully');
            return redirect()->back();
          }
         catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
    /**
     * file's actions
     */
    public function fileActions($id)
    {
        try {
            $file = $this->fileService->getById($id);
            $fileActions = $this->fileService->getFileActions($id);
            return view('files.fileActions', compact(['fileActions', 'file']));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    //***************************************** *_* ***********************************************//


    public function checkIn(Request $request, $id)
    {

        try {
            if (!$this->fileService->checkIn($id)) {
                return response()->json(['message' => 'File is already reserved.'], 409);
            }
            $file_path = $this->fileService->getFilePath($id);
            Session::flash('success','File reserved successfully' );
            return response()->download($file_path);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * checked-in Files
     */
    public function checkedInFiles()
    {
        try {
            $reseved_files = $this->fileService->getFilesByCheckStatus('reserved');
            return view('files.resevedFiles', compact(['reseved_files']));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function checkedOutFiles()
    {
        try {
            $free_files = $this->fileService->getFilesByCheckStatus('free');
            return view('files.freeFiles', compact(['free_files']));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }






    /**
     *   check out file
     */

    public function checkOut(Request $request)
    {

        try {
            $group_id = $this->fileService->checkOut($request);
            $group = Group::find($group_id);
            $files = $group->files()->paginate(9);
            return redirect()->route('admin.dashboard.files.checkedIn', compact('files', 'group'));
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('warning', $e->getMessage());
            return redirect()->back(); // Ensure to redirect back on error
        }
    }

    /**
     * restore original file
     */

    public function destroy(File $file)
    {
        $this->fileService->deleteFile($file);
        Session::flash('success', 'File deleted successfully');
        return redirect()->route('files.index');
    }


    /*   public function checkOut(Request $request)
    {

       // $oldFile = File::find($request->file_id);
        $oldFile=$this->fileService->getById($request->file_id);
        try {
            DB::beginTransaction();
            ///

            // Create a backup of the old file in the public directory
            if ($oldFile && $request->hasFile('file')) {
                $backupFileName = uniqid() . $oldFile->name;

                $backupPath = 'backups/' . $backupFileName; // Adjust the path for public storage

                // Copy the old file to the backups directory
                Storage::disk('uploads')->move($oldFile->file, $backupPath);
                BackUpFile::create([
                    'file_id' => $oldFile->id,
                    'backup_file' => $backupFileName,
                    'backup_path' => $backupPath,
                ]);

                $file = $request->file('file');
                $fileUrl = $this->uploadFile($file, 'files'); // Corrected path for uploaded files
                $fileName = $file->getClientOriginalName();

                // Update the old file information
                $oldFile->update([
                    'name' => $fileName,
                    'checkStatus' => 'free',
                    'file' => $fileUrl,
                    'user_id' => Auth::user()->id,
                ]);
                // Log the action
                Action::create([
                    'user_id' => auth()->user()->id,
                    'file_id' => $oldFile->id,
                    'action' => 'check-out',
                ]);

                ///
                DB::commit();
                $group = Group::find($oldFile->group_id);
                $files = $group->files()->paginate(9);
                /// start: notifications
                $user = Auth::user();

                // الحصول على المجموعات التي ينتمي إليها المستخدم
                $groups = $user->groups;

                foreach ($groups as $group) {
                    $data = [
                        'userName' => auth()->user()->name,
                        'userAction' => 'check-out',
                        'groupId' => $group->id
                    ];
                    // إرسال الإشعار إلى جميع المستخدمين في نفس المجموعة
                    event(new NewActionEvent($data));
                }
                /// end: notifications
                $group = Group::find($oldFile->group_id);
                $files = $group->files()->paginate(9);
                return redirect()->route('admin.dashboard.files.checkedIn', compact('files', 'group'));
            }
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('warning', $e->getMessage());
            return redirect()->back(); // Ensure to redirect back on error
        }
    } */
}
