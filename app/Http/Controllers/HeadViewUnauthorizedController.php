<?php

namespace App\Http\Controllers;

use App\Models\Unauthorized;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class HeadViewUnauthorizedController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            // Ensure you are correctly initializing date values if used
            // Initialize Carbon instance for dates
            $today = Carbon::today()->format('Y-m-d');
            $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
            $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

            // Order unauthorized records by log_date and time_in in descending order
            $unauthorizedRecords = Unauthorized::orderBy('log_date', 'desc')
                ->orderBy('time_in', 'desc')
                ->get();
            
            return view('SSUHead.unauthorized_list', compact('unauthorizedRecords'));
        } else {
            // If the user is not authorized, redirect to the index view
            return redirect()->route('index');
        }
    }
}
