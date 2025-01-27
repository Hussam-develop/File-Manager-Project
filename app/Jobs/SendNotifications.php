<?php

namespace App\Jobs;

use App\Events\NewActionEvent;
use App\Services\GroupService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class SendNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $action;
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        /// start: notifications
        $user = Auth::user();

        // الحصول على المجموعات التي ينتمي إليها المستخدم
        $groups = $user->groups;

        foreach ($groups as $group) {
            $data = [
                'userName' => auth()->user()->name,
                'userAction' =>'check-out',
                'groupId' => $group->id
            ];
            // إرسال الإشعار إلى جميع المستخدمين في نفس المجموعة
            event(new NewActionEvent($data));
        }
        /// end: notifications
    }
}
