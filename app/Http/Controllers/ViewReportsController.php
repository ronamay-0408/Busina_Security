<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Violation;
use Illuminate\Support\Facades\Auth;

class ViewReportsController extends Controller
{
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 2) {

            // Get the ID of the currently authenticated user
            $userId = Auth::user()->authorized_user_id;

            // Fetch violation reports created by the current user, ordered by created_at in descending order
            $violations = Violation::where('reported_by', $userId)
                                ->with('violationType') // Eager load violationType for efficiency
                                ->orderBy('created_at', 'desc') // Sort violations by creation date in descending order
                                ->paginate(10); // Paginate results, 10 per page

            // Pass the violations data and the search query to the view
            return view('view_reports', [
                'violations' => $violations,
                'search' => $request->input('search'),
            ]);

        } else {
            // Redirect to index if not authorized
            // abort(403, 'Unauthorized action.');
            return redirect()->route('head_index');
        }
    }
}
