<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Violation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Add this line

class ViewReportsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 2) {
            // Get the ID of the currently authenticated user
            $userId = Auth::user()->authorized_user_id;

            // Fetch the search query
            $search = $request->input('search');

            // Log the search query to ensure it's coming through correctly
            Log::info("Search query: " . $search);

            // Query violations based on search, user ID, and paginate results
            $violations = Violation::where('reported_by', $userId)
                ->when($search, function ($query) use ($search) {
                    return $query->where('plate_no', 'like', '%' . $search . '%');
                })
                ->with('violationType') // Eager load violationType for efficiency
                ->orderBy('created_at', 'desc') // Sort violations by creation date
                ->paginate(10) // Paginate results, 10 per page
                ->appends(['search' => $search]); // Ensure search parameter is included in pagination links

            // If the request is an AJAX request, return the violations HTML, pagination, and results info
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('MainPartials.myviolation', ['violations' => $violations])->render(),
                ]);
            }

            // If it's not an AJAX request, return the full view
            return view('view_reports', [
                'violations' => $violations,
                'search' => $search,
            ]);
        } else {
            // Redirect to index if not authorized
            return redirect()->route('head_index');
        }
    }
}
