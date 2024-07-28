<?php

// app/Http/Controllers/ViolationController.php
namespace App\Http\Controllers;

use App\Models\Violation;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function show($id)
    {
        $violation = Violation::findOrFail($id);
        return view('view_per_report', compact('violation'));
    }
}
