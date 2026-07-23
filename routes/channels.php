<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('consultation.{consultationId}', function ($user, $consultationId) {
    $consultation = \App\Models\Consultation::find($consultationId);
    if (!$consultation) return false;
    
    // Only the patient or the therapist assigned to the consultation can join
    return (int) $user->id === (int) $consultation->patient_id || 
           (int) $user->id === (int) $consultation->therapistProfile?->user_id;
});
