<?php

namespace App\Http\Controllers;

use App\Models\Unauthorized;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HeadViewUnauthorizedController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            // Initialize Carbon instance for dates
            $today = Carbon::today()->format('Y-m-d');
            $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
            $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

            // Get the number of items per page from the request, default to 25 if not set
            $perPage = $request->input('per_page', 25);

            // Paginate unauthorized records
            $unauthorizedRecords = Unauthorized::orderBy('log_date', 'desc')
                ->orderBy('time_in', 'desc')
                ->paginate($perPage);

            return view('SSUHead.unauthorized_list', compact('unauthorizedRecords'));
        } else {
            // If the user is not authorized, redirect to the index view
            return redirect()->route('index');
        }
    }
}
