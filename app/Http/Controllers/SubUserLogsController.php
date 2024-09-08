<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleOwner;
use Illuminate\Support\Facades\Auth;

class SubUserLogsController extends Controller
{
    public function show($vehicleOwnerId)
    {
        $user = Auth::user();

        if ($user && $user->authorizedUser && $user->authorizedUser->user_type == 3) {
            // Fetch the vehicle owner with their vehicles and related transactions
            $vehicleOwner = VehicleOwner::with('vehicles.transactions')->findOrFail($vehicleOwnerId);

            // Pass the vehicle owner and their vehicles to the view
            return view('SSUHead.SubUserLogs', compact('vehicleOwner'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
