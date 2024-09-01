<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class HeadViewViolationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            $perPage = $request->input('per_page', 10);

            $query = Violation::with('violationType', 'vehicle', 'reportedBy')
                            ->orderBy('created_at', 'desc');

            // Apply filters and search terms to the query
            if ($request->filled('search')) {
                $query->where('plate_no', 'like', '%' . $request->input('search') . '%');
            }

            if ($request->filled('year')) {
                $query->whereYear('created_at', $request->input('year'));
            }

            if ($request->filled('month')) {
                $query->whereMonth('created_at', $request->input('month'));
            }

            if ($request->filled('day')) {
                $query->whereDay('created_at', $request->input('day'));
            }

             // Paginate and append query parameters
            $violations = $query->paginate($perPage)->appends($request->except('page'));

            // Return JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'tableHtml' => view('SSUHead.partials.violation_table', ['violations' => $violations])->render(),
                    'paginationHtml' => $violations->links()->render()
                ]);
            }

            // Regular view rendering for non-AJAX requests
            return view('SSUHead.violation_list', compact('violations', 'request'));

        } else {
            return redirect()->route('index');
        }
    }

    public function exportViolationCsv(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            $query = Violation::orderBy('created_at', 'desc');

            // Apply filters
            if ($request->filled('search')) {
                $query->where('plate_no', 'like', '%' . $request->input('search') . '%');
            }
            if ($request->filled('year')) {
                $query->whereYear('created_at', $request->input('year'));
            }
            if ($request->filled('month')) {
                $query->whereMonth('created_at', $request->input('month'));
            }
            if ($request->filled('day')) {
                $query->whereDay('created_at', $request->input('day'));
            }

            // Handle pagination
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);
            $violations = $query->forPage($page, $perPage)->get();

            return $this->exportToCsv($violations, 'Violation_Report_FilteredRecord');
        }

        return redirect()->route('index');
    }

    public function exportAllViolationCsv(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            $query = Violation::orderBy('created_at', 'desc');

            // Apply filters
            if ($request->filled('search')) {
                $query->where('plate_no', 'like', '%' . $request->input('search') . '%');
            }
            if ($request->filled('year')) {
                $query->whereYear('created_at', $request->input('year'));
            }
            if ($request->filled('month')) {
                $query->whereMonth('created_at', $request->input('month'));
            }
            if ($request->filled('day')) {
                $query->whereDay('created_at', $request->input('day'));
            }

            // Get all records
            $violations = $query->get();

            return $this->exportToCsv($violations, 'Violation_Report_AllRecord');
        }

        return redirect()->route('index');
    }

    private function exportToCsv($violations, $fileName)
    {
        $date = Carbon::now()->format('Ymd_His');
        $fileName = "{$fileName}_{$date}.csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        // Define the CSV columns
        $columns = ['Date & Time', 'Plate No', 'Violation Type', 'Location', 'Reported By', 'Remarks', 'Proof Image'];

        $callback = function () use ($violations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($violations as $record) {
                $row = [
                    $record->created_at->format('F j, Y, g:i a'),
                    $record->plate_no,
                    $record->violationType ? $record->violationType->violation_name : 'N/A',
                    $record->location ?? 'N/A',
                    $record->reportedBy ? $record->reportedBy->getFullNameAttribute() : 'N/A',
                    $record->remarks ?? 'N/A',
                    $record->proof_image ?? 'N/A' // Adjust as needed
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
