<?php

namespace App\Http\Controllers;

use App\Models\Unauthorized;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HeadViewUnauthorizedController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            // Order unauthorized records by created_at in descending order
            $unauthorizedRecords = Unauthorized::orderBy('created_at', 'desc')->get();
            return view('SSUHead.unauthorized_list', compact('unauthorizedRecords'));
        } else {
            // abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');

            // If the user is not authorized, redirect to the index view
            return redirect()->route('index');
        }
    }
}
