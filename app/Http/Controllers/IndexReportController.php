<?php

// app/Http/Controllers/IndexReportController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Violation;
use App\Models\Unauthorized;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IndexReportController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 2) {
        $today = Carbon::today();

        $filedReportsToday = Violation::whereDate('created_at', $today)->count();
        // $unauthorizedEntriesToday = Unauthorized::whereDate('created_at', $today)->count();

        // Count the number of unauthorized entries today using log_date
        $unauthorizedEntriesToday = Unauthorized::whereDate('log_date', $today)->count();


        return view('index', compact('filedReportsToday', 'unauthorizedEntriesToday'));

        } else {
            // Redirect to index if not authorized
            // abort(403, 'Unauthorized action.');
            return redirect()->route('head_index');
        }
    }
}

