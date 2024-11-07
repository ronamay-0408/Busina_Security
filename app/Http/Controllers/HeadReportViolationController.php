<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;
use App\Exports\ViolationsExport;
use Maatwebsite\Excel\Facades\Excel;

class HeadReportViolationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            $perPage = $request->input('per_page', 10);

            $query = Violation::with('violationType', 'vehicle', 'reportedBy')
                            ->orderBy('created_at', 'desc');

            // Apply filters if they are set in the request
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('plate_no', 'like', '%' . $request->input('search') . '%')
                    ->orWhereHas('violationType', function ($q) use ($request) {
                        $q->where('violation_name', 'like', '%' . $request->input('search') . '%');
                    });
                });
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

             // Paginate and append query parameters
            $violations = $query->paginate($perPage)->appends($request->except('page'));

            // Return JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'tableHtml' => view('SSUHead.partials.report_violation_table', ['violations' => $violations])->render(),
                    'paginationHtml' => $violations->links()->render()
                ]);
            }

            // Regular view rendering for non-AJAX requests
            return view('SSUHead.report_violation_list', compact('violations', 'request'));

        } else {
            return redirect()->route('index');
        }
    }

    public function showSubViolationList($id)
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            $violation = Violation::with('violationType', 'reportedBy')->findOrFail($id);
            
            return view('SSUHead.SubViolationList', compact('violation'));
        }

        return redirect()->route('index');
    }

    public function exportFiltered(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            // Start with the base query for violations
            $query = Violation::with(['violationType', 'reportedBy'])->orderBy('created_at', 'desc');

            // Apply filters if they are set in the request
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('plate_no', 'like', '%' . $request->input('search') . '%')
                    ->orWhereHas('violationType', function ($q) use ($request) {
                        $q->where('violation_name', 'like', '%' . $request->input('search') . '%');
                    });
                });
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

            // Check if 'remarks' filter is applied and map it to text values
            if ($request->filled('remarks')) {
                $remarks = $request->input('remarks');

                // Map numeric remarks to their text values
                if ($remarks == 1) {
                    $query->where('remarks', 'like', '%Not been settled%');
                } elseif ($remarks == 2) {
                    $query->where('remarks', 'like', '%Settled%');
                }
            }

            // Fetch the filtered violations without pagination
            $violations = $query->get();

            // If no records are found, redirect back with a message
            if ($violations->isEmpty()) {
                return redirect()->back()->with('error', 'No records found for the applied filters.');
            }

            // Generate the filename with the current date
            $currentDate = now()->format('Y-m-d');
            $filteredexport_filename = "Filtered_Violations_Report_{$currentDate}.xlsx";

            // Return the Excel download
            return Excel::download(new ViolationsExport($violations), $filteredexport_filename);
        }

        // If user is not authorized, redirect to the index page
        return redirect()->route('index');
    }

    // Define the method to export all violation details to Excel
    public function exportAllVioDetailsToExcel()
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            $violations = Violation::with(['violationType', 'reportedBy'])->get();

            $currentDate = now()->format('Y-m-d');
            $filename = "All_Violations_Report_{$currentDate}.xlsx";

            return Excel::download(new ViolationsExport($violations), $filename);
        }
        return redirect()->route('index');
    }
}
