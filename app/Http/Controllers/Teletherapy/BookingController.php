<?php

namespace App\Http\Controllers\Teletherapy;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\TherapistProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Public directory of approved therapists.
     */
    public function directory(Request $request)
    {
        $therapists = TherapistProfile::where('status', 'approved')
            ->with('user')
            ->when($request->specialty, fn ($q) => $q->whereJsonContains('specialties', $request->specialty))
            ->when($request->search, fn ($q) => $q->whereHas('user', fn ($u) =>
                $u->where('name', 'like', "%{$request->search}%")
            ))
            ->paginate(12);

        return view('teletherapy.directory', compact('therapists'));
    }

    /**
     * Public therapist profile page.
     */
    public function showProfile(TherapistProfile $therapist)
    {
        $therapist->load('user');
        return view('teletherapy.profile', compact('therapist'));
    }

    /**
     * Book a consultation.
     */
    public function book(Request $request, TherapistProfile $therapist)
    {
        $request->validate([
            'scheduled_at'     => 'required|date|after:now',
            'duration_minutes' => 'required|integer|in:30,45,60,90',
            'type'             => 'required|in:video,audio,chat',
        ]);

        $price = $therapist->hourly_rate
            ? round($therapist->hourly_rate * ($request->duration_minutes / 60), 2)
            : 0;

        $consultation = Consultation::create([
            'reference'          => Consultation::generateReference(),
            'patient_id'         => Auth::id(),
            'therapist_profile_id'=> $therapist->id,
            'status'             => Consultation::STATUS_PAYMENT_PENDING,
            'type'               => $request->type,
            'scheduled_at'       => $request->scheduled_at,
            'duration_minutes'   => $request->duration_minutes,
            'price'              => $price,
        ]);

        return redirect()->route('teletherapy.checkout', $consultation);
    }

    /**
     * Checkout page for consultation payment.
     */
    public function checkout(Consultation $consultation)
    {
        abort_unless($consultation->patient_id === Auth::id(), 403);
        $consultation->load('therapistProfile.user');
        return view('teletherapy.checkout', compact('consultation'));
    }

    /**
     * Success page after payment.
     */
    public function success(Consultation $consultation)
    {
        abort_unless($consultation->patient_id === Auth::id(), 403);
        $consultation->update(['status' => Consultation::STATUS_CONFIRMED, 'paid_at' => now()]);
        return view('teletherapy.success', compact('consultation'));
    }

    /**
     * Show feedback form.
     */
    public function feedbackForm(Consultation $consultation)
    {
        abort_unless($consultation->patient_id === Auth::id(), 403);
        return view('teletherapy.feedback', compact('consultation'));
    }

    /**
     * Submit feedback and recalculate therapist average rating.
     */
    public function submitFeedback(Request $request, Consultation $consultation)
    {
        abort_unless($consultation->patient_id === Auth::id(), 403);
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_anonymous' => 'nullable|boolean',
        ]);

        \App\Models\TherapistReview::create([
            'consultation_id' => $consultation->id,
            'therapist_profile_id' => $consultation->therapist_profile_id,
            'patient_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_anonymous' => (bool) $request->is_anonymous,
            'is_published' => true,
        ]);

        $consultation->therapistProfile->recalculateRating();

        return redirect()->route('dashboard')->with('success', 'Merci pour votre retour ! Votre avis a été enregistré.');
    }
}
