<?php

namespace App\Livewire\Patient;

use App\Services\AI\SmartTherapistMatcher;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SmartMatchResults extends Component
{
    public array $concerns = [];
    public string $preferredLanguage = '';
    public float $maxPrice = 200;
    public bool $hasSearched = false;
    public array $results = [];

    public function mount()
    {
        // Auto-match on mount based on latest mood data
        $this->findMatches();
    }

    public function findMatches()
    {
        $matcher = app(SmartTherapistMatcher::class);

        $matchResults = $matcher->match(
            userId: Auth::id(),
            concerns: !empty($this->concerns) ? $this->concerns : null,
            preferredLanguage: $this->preferredLanguage ?: null,
            maxPrice: $this->maxPrice > 0 ? $this->maxPrice : null,
        );

        $this->results = $matchResults->map(function ($item) {
            return [
                'id'           => $item['profile']->id,
                'name'         => $item['profile']->user->name,
                'title'        => $item['profile']->title ?? 'Psychothérapeute',
                'bio'          => $item['profile']->bio,
                'photo'        => $item['profile']->photo,
                'rating'       => $item['profile']->rating,
                'total_reviews' => $item['profile']->total_reviews,
                'experience'   => $item['profile']->experience_years,
                'price'        => $item['profile']->session_price,
                'currency'     => $item['profile']->currency ?? 'DZD',
                'specialties'  => $item['profile']->specialties ?? [],
                'languages'    => $item['profile']->languages ?? [],
                'free_first'   => $item['profile']->offers_first_free_session,
                'matchPercent' => $item['matchPercent'],
                'reasons'      => $item['reasons'],
            ];
        })->toArray();

        $this->hasSearched = true;
    }

    public function toggleConcern(string $concern)
    {
        if (in_array($concern, $this->concerns)) {
            $this->concerns = array_values(array_diff($this->concerns, [$concern]));
        } else {
            $this->concerns[] = $concern;
        }
    }

    public function render()
    {
        return view('livewire.patient.smart-match-results');
    }
}
