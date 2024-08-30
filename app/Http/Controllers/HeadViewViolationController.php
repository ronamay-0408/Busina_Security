<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

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
}
