<?php

// app/Http/Controllers/IndexReportController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Violation;
use App\Models\Unauthorized;
use Carbon\Carbon;

class IndexReportController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $filedReportsToday = Violation::whereDate('created_at', $today)->count();
        $unauthorizedEntriesToday = Unauthorized::whereDate('created_at', $today)->count();

        return view('index', compact('filedReportsToday', 'unauthorizedEntriesToday'));
    }
}
