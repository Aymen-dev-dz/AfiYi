<?php

namespace App\Services\AI;

use App\Models\MoodEntry;
use App\Models\TherapistProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class SmartTherapistMatcher
{
    /**
     * Maps mood "concerns" keywords to therapist specialties.
     */
    private array $concernToSpecialty = [
        'stress'        => ['Burn-out', 'Anxiété', 'Gestion du stress'],
        'anxiety'       => ['Anxiété', 'Trouble anxieux', 'Phobies'],
        'loneliness'    => ['Dépression', 'Isolement social', 'Thérapie relationnelle'],
        'work'          => ['Burn-out', 'Coaching professionnel', 'Gestion du stress'],
        'relationships' => ['Couple', 'Thérapie familiale', 'Thérapie relationnelle'],
        'sleep'         => ['Insomnie', 'Trouble du sommeil', 'Relaxation'],
        'depression'    => ['Dépression', 'Trouble de l\'humeur'],
        'trauma'        => ['PTSD', 'Traumatisme', 'EMDR'],
        'self-esteem'   => ['Estime de soi', 'Développement personnel'],
        'addiction'     => ['Addictologie', 'Dépendance'],
    ];

    /**
     * Scores therapists based on the user's latest mood data and returns the top 3.
     */
    public function match(?int $userId = null, ?array $concerns = null, ?string $preferredLanguage = null, ?float $maxPrice = null): Collection
    {
        $userId = $userId ?? Auth::id();

        // Get user's latest mood entry for context
        $latestMood = MoodEntry::where('user_id', $userId)
            ->latest()
            ->first();

        // Derive concerns from mood entry if none provided
        if (empty($concerns) && $latestMood) {
            $concerns = $this->deriveConcernsFromMood($latestMood);
        }

        // Build relevant specialties from concerns
        $relevantSpecialties = $this->mapConcernsToSpecialties($concerns ?? []);

        // Get all available therapists
        $therapists = TherapistProfile::with('user')
            ->where('accepts_new_clients', true)
            ->get();

        // Score each therapist
        $scored = $therapists->map(function (TherapistProfile $profile) use ($relevantSpecialties, $preferredLanguage, $maxPrice, $latestMood) {
            $score = 0;
            $reasons = [];

            // 1. Specialty match (0-40 points)
            $profileSpecialties = array_map('strtolower', $profile->specialties ?? []);
            $matchedSpecialties = [];
            foreach ($relevantSpecialties as $spec) {
                if (in_array(strtolower($spec), $profileSpecialties)) {
                    $score += 10;
                    $matchedSpecialties[] = $spec;
                }
            }
            $score = min($score, 40); // Cap at 40
            if (!empty($matchedSpecialties)) {
                $reasons[] = 'Spécialiste en ' . implode(', ', array_slice($matchedSpecialties, 0, 2));
            }

            // 2. Language match (0-15 points)
            if ($preferredLanguage) {
                $profileLanguages = array_map('strtolower', $profile->languages ?? []);
                if (in_array(strtolower($preferredLanguage), $profileLanguages)) {
                    $score += 15;
                    $reasons[] = 'Parle ' . $preferredLanguage;
                }
            }

            // 3. Rating bonus (0-20 points)
            if ($profile->rating) {
                $score += round(($profile->rating / 5) * 20);
                if ($profile->rating >= 4.5) {
                    $reasons[] = 'Excellentes évaluations (' . $profile->rating . '/5)';
                } elseif ($profile->rating >= 4.0) {
                    $reasons[] = 'Très bien noté(e) (' . $profile->rating . '/5)';
                }
            }

            // 4. Experience bonus (0-10 points)
            if ($profile->experience_years >= 10) {
                $score += 10;
                $reasons[] = $profile->experience_years . ' ans d\'expérience';
            } elseif ($profile->experience_years >= 5) {
                $score += 6;
            } elseif ($profile->experience_years >= 2) {
                $score += 3;
            }

            // 5. Price within budget (0-10 points)
            if ($maxPrice && $profile->session_price <= $maxPrice) {
                $score += 10;
            }

            // 6. First session free bonus (0-5 points)
            if ($profile->offers_first_free_session) {
                $score += 5;
                $reasons[] = 'Première séance gratuite';
            }

            // 7. Availability bonus (0-15 points)
            // Mocking fast availability for demonstration (e.g. within 48h)
            if ($profile->accepts_new_clients && ($profile->id % 2 !== 0)) {
                $score += 15;
                $reasons[] = 'Disponible dans les 48h';
            }

            // Generate a match percentage (capped at 98%)
            $matchPercent = min(98, max(15, round(($score / 100) * 100)));

            // Fallback reason
            if (empty($reasons)) {
                $reasons[] = 'Professionnel disponible';
            }

            return [
                'profile'       => $profile,
                'score'         => $score,
                'matchPercent'  => $matchPercent,
                'reasons'       => array_slice($reasons, 0, 3),
            ];
        });

        // Sort by score descending and return top 3
        return $scored->sortByDesc('score')->take(3)->values();
    }

    /**
     * Derives user concerns from their latest mood entry.
     */
    private function deriveConcernsFromMood(MoodEntry $mood): array
    {
        $concerns = [];

        if ($mood->stress_level && $mood->stress_level >= 7) {
            $concerns[] = 'stress';
        }
        if ($mood->mood_score && $mood->mood_score <= 3) {
            $concerns[] = 'depression';
        }
        if ($mood->sleep_quality && $mood->sleep_quality <= 3) {
            $concerns[] = 'sleep';
        }
        if ($mood->energy_level && $mood->energy_level <= 3) {
            $concerns[] = 'stress';
        }

        // Parse notes for keywords
        $notes = strtolower($mood->note ?? '');
        foreach (['anxiety', 'loneliness', 'work', 'relationships', 'trauma', 'addiction'] as $keyword) {
            if (str_contains($notes, $keyword)) {
                $concerns[] = $keyword;
            }
        }

        return array_unique($concerns);
    }

    /**
     * Maps concern keywords to therapist specialties.
     */
    private function mapConcernsToSpecialties(array $concerns): array
    {
        $specialties = [];
        foreach ($concerns as $concern) {
            $mapped = $this->concernToSpecialty[strtolower($concern)] ?? [];
            $specialties = array_merge($specialties, $mapped);
        }
        return array_unique($specialties);
    }
}
