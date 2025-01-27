<?php

namespace App\Http\Controllers;

use App\Jobs\ImportCsvActions;
use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Svg\Tag\Rect;

class UserController extends Controller
{

    public function __construct(protected UserService $userService) {}
    //
    public function index()
    {
        $users = $this->userService->getPaginateUsers();
        return view('users.index', compact('users'));
    }

    public function userActions($id)
    {
        $user = $this->userService->getById($id);
        $userActions = $this->userService->getUserActions($id);

        return view('users.user-actions', compact('userActions', 'user'));
    }

    public function import($id, Request $request)
    {

        $file = $request->file('csv_file');

        if (!$file || $file->getClientOriginalExtension() !== 'csv') {
            return redirect()->back()->with('error', 'Please upload a valid CSV file!');
        }

        $data = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($data); // Remove the header row
        $rows = [];
        foreach ($data as $row) {
            $row = array_combine($header, $row); // Map headers to row data

            Validator::make($row, [
                'file_id' => 'required|integer|exists:files,id',
                'action' => 'required|string',
                'created_at' => 'required|date',
            ])->validate();

            $rows[] = $row;
        }

        ImportCsvActions::dispatch($rows, $id);
        return redirect()->back()->with('success', 'CSV file imported successfully!');
    }


    public function export($id, $format)
    {
        $userActions = $this->userService->getUserActions($id);

        if ($format === 'csv') {
            $csvFileName = 'user_actions.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$csvFileName\"",
            ];

            $callback = function () use ($userActions) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['ID', 'File ID', 'File Name', 'Action', 'Created At']);

                foreach ($userActions as $action) {
                    fputcsv($handle, [
                        $action->id,
                        $action->file_id,
                        $action->File->name,
                        $action->action,
                        $action->created_at->format('j/n/Y ,g:i a'),
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } elseif ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.userActions', compact('userActions'));
            return $pdf->download('user_actions.pdf');
        }

        return redirect()->back()->with('error', 'Invalid format selected!');
    }
}
