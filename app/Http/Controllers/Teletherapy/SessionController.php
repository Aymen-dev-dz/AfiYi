<?php

namespace App\Http\Controllers\Teletherapy;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    /**
     * Enter the video room for a consultation.
     */
    public function room(Request $request, Consultation $consultation)
    {
        if ($request->user()->cannot('viewRoom', $consultation)) {
            abort(403, 'You are not authorized to access this room.');
        }

        abort_unless($consultation->isPaid(), 402, 'Payment required to enter the room.');

        // Update started_at when patient joins to notify therapist
        if (Auth::id() === $consultation->patient_id && is_null($consultation->started_at)) {
            $consultation->update(['started_at' => now()]);
        }

        $consultation->load('therapistProfile.user', 'patient');

        return view('teletherapy.room', compact('consultation'));
    }

    /**
     * Submit patient consent before entering room.
     */
    public function submitConsent(Request $request, Consultation $consultation)
    {
        abort_unless($consultation->patient_id === Auth::id(), 403);

        $request->validate(['consent' => 'required|accepted']);

        session(["consent_for_{$consultation->id}" => true]);

        return redirect()->route('teletherapy.room', $consultation);
    }
}
