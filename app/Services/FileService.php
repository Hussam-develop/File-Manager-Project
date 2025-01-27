<?php

namespace App\Services;

use App\Events\NewActionEvent;
use App\Jobs\MultiApprove;
use App\Jobs\MultiCheckInJob;
use App\Jobs\SendNotifications;
use App\Repositories\FileRepository;
use App\Traits\ManagerFilesTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class FileService
{
    use ManagerFilesTrait;

    public function __construct(protected FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }
    public function getById($id)
    {
        return $this->fileRepository->getById($id);
    }

    public function createBackupFile($data)
    {
        return $this->fileRepository->createBackupFile($data);
    }
    public function checkIn($id)
    {

        try {

            $file = $this->getById($id);
            $user = Auth::user();
            if ($file && $file->checkStatus == 'free') {
                DB::beginTransaction();
                $this->fileRepository->updateStatus($file->id, ['user_id' => Auth::user()->id, 'checkStatus' => 'reserved']);
                $this->fileRepository->createAction($file->id, $user->id, 'check-in');
                DB::commit();
                //  notification processing //
                ///////// start: notifications //////////
                //SendNotifications::dispatch('check-in');
                /// start: notifications
                $user = Auth::user();

                // الحصول على المجموعات التي ينتمي إليها المستخدم
                $groups = $user->groups;

                foreach ($groups as $group) {
                    $data = [
                        'userName' => auth()->user()->name,
                        'userAction' => 'check-in',
                        'groupId' => $group->id
                    ];
                    // إرسال الإشعار إلى جميع المستخدمين في نفس المجموعة
                    event(new NewActionEvent($data));
                }
                /// end: notifications
                ////////// end: notifications /////////////

                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function checkOut($request)
    {


        try {

            ///
            $oldFile = $this->fileRepository->getById($request->file_id);
            // Create a backup of the old file in the public directory
            if ($oldFile && $request->hasFile('file')) {
                $backupFileName = uniqid() . $oldFile->name;

                $backupPath = 'backups/' . $backupFileName; // Adjust the path for public storage

                // Copy the old file to the backups directory
                Storage::disk('uploads')->move($oldFile->file, $backupPath);

                $backup_data = [
                    'file_id' => $oldFile->id,
                    'backup_file' => $backupFileName,
                    'backup_path' => $backupPath,
                ];

                DB::beginTransaction();
                $this->fileRepository->createBackupFile($backup_data);
                /*   BackUpFile::create([
                    'file_id' => $oldFile->id,
                    'backup_file' => $backupFileName,
                    'backup_path' => $backupPath,
                ]); */

                $file = $request->file('file');
                $fileUrl = $this->uploadFile($file, 'files'); // Corrected path for uploaded files
                $fileName = $file->getClientOriginalName();

                $file_data = [
                    'name' => $fileName,
                    'checkStatus' => 'free',
                    'file' => $fileUrl,
                    'user_id' => Auth::user()->id,
                ];
                // Update the old file information
                $this->fileRepository->update($oldFile->id, $file_data);
                /*  $oldFile->update([
                    'name' => $fileName,
                    'checkStatus' => 'free',
                    'file' => $fileUrl,
                    'user_id' => Auth::user()->id,
                ]); */

                // Log the action
                $this->fileRepository->createAction($oldFile->id, auth()->user()->id, 'check-out');
                /*   Action::create([
                    'user_id' => auth()->user()->id,
                    'file_id' => $oldFile->id,
                    'action' => 'check-out',
                ]); */

                ///
                DB::commit();

                /// start: notifications
              /*   $user = Auth::user();

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
                } */
                /// end: notifications
                SendNotifications::dispatch();

                return $oldFile->group_id;
            }
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('warning', $e->getMessage());
            return redirect()->back(); // Ensure to redirect back on error
        }
    }

    public function getFilePath($id)
    {
        return $this->fileRepository->filePath($id);
    }

    /**
     * get files actions
     */

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
    /**
     * multi check in files by ids
     */
    public function checkInFiles(array $fileIds, $userId)
    {
        MultiCheckInJob::dispatch($fileIds, $userId);

        /*  foreach ($fileIds as $fileId) {
            $this->fileRepository->updateStatus($fileId, ['user_id' => Auth::user()->id, 'checkStatus' => 'reserved']);
           $this->fileRepository->createAction($fileId, $userId, 'check-in');
        } */
    }

    /**
     * multi approve files by ids
     */
    public function ApproveFiles(array $fileIds)
    {
       /*  foreach ($fileIds as $fileId) {
            $this->fileRepository->updateStatus($fileId, ['status' => 1]);
        } */
       
        MultiApprove::dispatch($fileIds);
    }

    /**
     * get files by status.
     */

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

    public function getFilesByCheckStatus($status)
    {
        return $this->fileRepository->getFilesByCheckStatus($status);
    }

    public function deleteFile($id)
    {
        $file = $this->fileRepository->getById($id);
        Storage::disk('uploads')->delete($file->path);
        $this->fileRepository->delete($id);
    }
}
