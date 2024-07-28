<?php

// app/Http/Controllers/HeadViewUnauthorizedController.php
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
            $unauthorizedRecords = Unauthorized::all();
            return view('SSUHead.unauthorized_list', compact('unauthorizedRecords'));
        } else {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action.');
        }
    }
}
