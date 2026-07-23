<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TherapistProfile;
use Illuminate\Http\Request;

class AdminTherapistController extends Controller
{
    public function approve(TherapistProfile $therapist)
    {
        $therapist->update([
            'status'      => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', "Thérapeute approuvé.");
    }

    public function reject(Request $request, TherapistProfile $therapist)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $therapist->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        return back()->with('success', "Thérapeute rejeté.");
    }
}
