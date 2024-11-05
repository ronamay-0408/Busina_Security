<?php

namespace App\Http\Controllers;

use App\Mail\ViolationSettled;
use App\Models\Settle_violation;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Exports\ViolationsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportedViolationsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            $perPage = $request->input('per_page', 10);

            // Initialize the query and apply filters
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

            // Assign the paginated result to $data
            $data = $query->paginate($perPage)->appends($request->except('page'));

            // Return JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'tableHtml' => view('SSUHead.reported_violations', ['violations' => $data])->render(),
                    'paginationHtml' => $data->links()->render()
                ]);
            }

            // Regular view rendering for non-AJAX requests
            return view('SSUHead.reported_violations', compact('data', 'request'));
        } else {
            return redirect()->route('index');
        }
    }

    public function exportFiltered(Request $request)
    {
        // Initialize the query and apply filters based on request parameters
        $query = Violation::with(['violationType', 'reportedBy']);

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            // Filter by plate_no
            $query->where('plate_no', 'like', '%' . $searchTerm . '%')
                // Filter by violationType name
                ->orWhereHas('violationType', function ($q) use ($searchTerm) {
                    $q->where('violation_name', 'like', '%' . $searchTerm . '%');
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

        // Map numeric values to the actual remarks
        $remarksMapping = [
            '1' => 'Not been settled',
            '2' => 'Settled',
        ];

        if ($request->filled('remarks')) {
            $selectedRemarkValue = $request->input('remarks');

            // Get the actual remarks based on the selected value
            $remarksToFilter = $remarksMapping[$selectedRemarkValue] ?? null;

            if ($remarksToFilter) {
                // Filter by remarks
                $query->where('remarks', $remarksToFilter);
            }
        }

        // Fetch filtered violations
        $violations = $query->get();

        // Generate the filename with the current date
        $currentDate = now()->format('Y-m-d'); // Format the date as desired
        $filteredexport_filename = "FilteredViolationDetails_{$currentDate}.xlsx"; // Create the filename

        // Return the Excel download
        return Excel::download(new ViolationsExport($violations), $filteredexport_filename);
    }

    // Define the method to export all violation details to Excel
    public function exportAllVioDetailsToExcel()
    {
        // Fetch violations from the database
        $violations = Violation::with(['violationType', 'reportedBy'])->get();

        // Generate the filename with the current date
        $currentDate = now()->format('Y-m-d'); // Format the date as desired
        $filename = "AllViolationDetails_{$currentDate}.xlsx"; // Create the filename

        // Return the Excel download
        return Excel::download(new ViolationsExport($violations), $filename);
    }

    public function showDetails($id)
    {
        $violation = Violation::with(['vehicle', 'violationType', 'reportedBy'])->findOrFail($id);
        $proofImage = null;

        if ($violation->proof_image) {
            try {
                // Encode the binary image data to base64
                $proofImage = 'data:image/jpeg;base64,' . base64_encode($violation->proof_image);
                // Log the success of the encoding
                Log::info('Proof image encoded successfully.');
            } catch (\Exception $e) {
                Log::error('Error processing proof image: ' . $e->getMessage());
            }
        }

        return view('SSUHead.rv_details', compact('proofImage', 'violation'));
    }
}
