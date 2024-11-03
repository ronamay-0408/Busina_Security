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
        $this->authorizeUser();

        $perPage = $request->input('per_page', 10);
        $violations = $this->getFilteredViolations($request)->paginate($perPage)->appends($request->except('page'));

        // Return JSON response for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'tableHtml' => view('SSUHead.partials.violation_table', ['violations' => $violations])->render(),
                'paginationHtml' => $violations->links()->render()
            ]);
        }

        // Regular view rendering for non-AJAX requests
        return view('SSUHead.violation_list', compact('violations', 'request'));
    }

    public function exportViolationCsv(Request $request)
    {
        $this->authorizeUser();

        $violations = $this->getFilteredViolations($request)->get();

        return $this->exportToCsv($violations, 'Violation_Report_FilteredRecord');
    }

    public function exportAllViolationCsv()
    {
        $this->authorizeUser();

        $violations = Violation::with('violationType', 'vehicle', 'reportedBy')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->exportToCsv($violations, 'Violation_Report_AllRecord');
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

        $columns = ['Date & Time', 'Plate No', 'Violation Type', 'Location', 'Reported By', 'Remarks'];

        $callback = function () use ($violations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($violations as $record) {
                $row = [
                    $record->created_at->format('F j, Y, g:i a'),
                    $record->plate_no,
                    $record->violationType->violation_name ?? 'N/A',
                    $record->location ?? 'N/A',
                    $record->reportedBy->getFullNameAttribute() ?? 'N/A',
                    $record->remarks ?? 'N/A',
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function showSubViolationList($id)
    {
        $this->authorizeUser();

        $violation = Violation::with('violationType', 'reportedBy')->findOrFail($id);
        return view('SSUHead.SubViolationList', compact('violation'));
    }

    private function getFilteredViolations(Request $request)
    {
        $query = Violation::with('violationType', 'vehicle', 'reportedBy')
            ->orderBy('created_at', 'desc');

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

        if ($request->filled('remarks')) {
            $query->where('remarks', $request->input('remarks'));
        }

        return $query;
    }

    private function authorizeUser()
    {
        $user = Auth::user();
        if (!$user || !$user->authorizedUser || $user->authorizedUser->user_type != 3) {
            return redirect()->route('index');
        }
    }
}
