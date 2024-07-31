<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HeadViewViolationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            // Order violations by created_at in descending order
            $violations = Violation::with('violationType', 'vehicle', 'reportedBy')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
            return view('SSUHead.violation_list', compact('violations'));
        } else {
            // abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');

            // If the user is not authorized, redirect to the index view
            return redirect()->route('index');
        }
    }
}
