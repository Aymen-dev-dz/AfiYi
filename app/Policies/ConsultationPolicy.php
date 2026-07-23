<?php

namespace App\Policies;

use App\Models\Consultation;
use App\Models\User;

class ConsultationPolicy
{
    /**
     * Determine whether the user can view the consultation room.
     */
    public function viewRoom(User $user, Consultation $consultation): bool
    {
        return $user->id === $consultation->patient_id || 
               $user->id === $consultation->therapistProfile?->user_id;
    }

    /**
     * Determine whether the user can take notes for the consultation.
     */
    public function takeNotes(User $user, Consultation $consultation): bool
    {
        return $user->id === $consultation->therapistProfile?->user_id;
    }

    /**
     * Determine whether the user can send chat messages.
     */
    public function sendMessages(User $user, Consultation $consultation): bool
    {
        return $user->id === $consultation->patient_id || 
               $user->id === $consultation->therapistProfile?->user_id;
    }
}
