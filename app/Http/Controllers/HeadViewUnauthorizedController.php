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
            // Start the query builder
            $query = Unauthorized::orderBy('log_date', 'desc')->orderBy('time_in', 'desc');

            // Apply search filters if provided
            if ($request->filled('search')) {
                $query->where('plate_no', 'like', '%' . $request->input('search') . '%');
            }
            if ($request->filled('year')) {
                $query->whereYear('log_date', $request->input('year'));
            }
            if ($request->filled('month')) {
                $query->whereMonth('log_date', $request->input('month'));
            }
            if ($request->filled('day')) {
                $query->whereDay('log_date', $request->input('day'));
            }

            // Define the number of items per page
            $perPage = $request->input('per_page', 10);

            // Paginate results and ensure pagination maintains filters
            $unauthorizedRecords = $query->paginate($perPage)->appends($request->except('page'));

            // Check if the request is AJAX and return the partial view
            if ($request->ajax()) {
                return view('SSUHead.partials.unauthorized_table', compact('unauthorizedRecords'))->render();
            }

            // Return the main view with the paginated records
            return view('SSUHead.unauthorized_list', compact('unauthorizedRecords'));
        } else {
            return redirect()->route('index');
        }
    }
}


