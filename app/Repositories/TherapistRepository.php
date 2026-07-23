<?php

namespace App\Repositories;

use App\Models\TherapistProfile;
use App\Models\TherapistReview;
use App\Models\Consultation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TherapistRepository
{
    public function getApproved(?string $search = null, ?string $specialty = null, int $perPage = 12): LengthAwarePaginator
    {
        return TherapistProfile::active() // scopeActive checks for 'approved' now
            ->with('user')
            ->when($specialty, fn ($q) => $q->whereJsonContains('specialties', $specialty))
            ->when($search, fn ($q) => $q->whereHas('user', fn ($u) =>
                $u->where('name', 'like', "%{$search}%")
            ))
            ->paginate($perPage);
    }

    public function findById(int $id): ?TherapistProfile
    {
        return TherapistProfile::with('user')->find($id);
    }

    public function createReview(array $data): TherapistReview
    {
        $review = TherapistReview::create($data);
        $review->therapistProfile->recalculateRating();
        return $review;
    }

    public function getReviewsForTherapist(int $therapistProfileId): Collection
    {
        return TherapistReview::where('therapist_profile_id', $therapistProfileId)
            ->where('is_published', true)
            ->with('patient')
            ->latest()
            ->get();
    }

    public function getConsultationsForPatient(int $patientId): Collection
    {
        return Consultation::where('patient_id', $patientId)
            ->with('therapistProfile.user')
            ->latest('scheduled_at')
            ->get();
    }

    public function getUpcomingConsultationsForPatient(int $patientId): Collection
    {
        return Consultation::where('patient_id', $patientId)
            ->upcoming()
            ->with('therapistProfile.user')
            ->get();
    }
}
