<?php

// app/Http/Controllers/HeadViewViolationController.php
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
            $violations = Violation::with('violationType', 'vehicle', 'reportedBy')->get();
            return view('SSUHead.violation_list', compact('violations'));
        } else {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }
    }
}
