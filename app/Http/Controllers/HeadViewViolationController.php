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
            // Get the number of rows per page from the request, default to 25
            $perPage = $request->input('per_page', 25);

            // Order violations by created_at in descending order and paginate
            $violations = Violation::with('violationType', 'vehicle', 'reportedBy')
                                    ->orderBy('created_at', 'desc')
                                    ->paginate($perPage);
                                    
            return view('SSUHead.violation_list', compact('violations'));
        } else {
            return redirect()->route('index');
        }
    }
}
