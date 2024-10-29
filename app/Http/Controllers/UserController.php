<?php

namespace App\Http\Controllers;

use App\Models\VendorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function showVendorRequestForm()
    {
        return view('vendor.vendordashboard');
    }

    public function requestVendorRole(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $existingRequest = VendorRequest::where('user_id', $user->id)->where('status', 'pending')->first();
            if ($existingRequest) {
                return redirect()->back()->with('error', 'You already have a pending vendor request.');
            }
            $validatedData = $request->validate([
                'pan_number' => 'required|string|size:9',
                'phone_number' => 'required|string|max:10',
            ]);
            VendorRequest::create(['user_id' => $user->id, 'status' => 'pending','pan_number' => $validatedData['pan_number'],'phone_number' => $validatedData['phone_number'],]);

            return redirect()->back()->with('success', 'Vendor role requested successfully.');
        }

        return redirect()->back()->with('error', 'Failed to request vendor role.');
    }
}
