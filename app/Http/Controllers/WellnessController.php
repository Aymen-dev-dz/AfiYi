<?php

namespace App\Http\Controllers;

use App\Coordinators\WellnessCoordinator;
use App\Repositories\ActivityRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WellnessController extends Controller
{
    public function __construct(
        private readonly WellnessCoordinator $coordinator,
        private readonly ActivityRepository $activityRepository
    ) {}

    /**
     * Public endpoint to calculate recommendations for guests.
     */
    public function guestCheck(Request $request)
    {
        $request->validate([
            'stress_level' => 'required|integer|min:1|max:10',
            'sleep_quality' => 'required|integer|min:1|max:10',
            'energy_level' => 'required|integer|min:1|max:10',
            'social_level' => 'required|integer|min:1|max:10',
        ]);

        $results = $this->coordinator->buildMockForGuest($request->only([
            'stress_level', 'sleep_quality', 'energy_level', 'social_level'
        ]));

        return response()->json($results);
    }

    /**
     * Mark an activity as completed for the authenticated user.
     */
    public function completeActivity(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|string',
        ]);

        if (Auth::check()) {
            $this->activityRepository->logCompletion(Auth::id(), $request->activity_id);
            return response()->json([
                'success' => true,
                'message' => 'Activité enregistrée avec succès.'
            ]);
        }

        return response()->json(['error' => 'Non authentifié.'], 401);
    }

    /**
     * Render the Activities Center page.
     */
    public function activitiesIndex()
    {
        $activities = $this->activityRepository->getAll();
        $completed = Auth::check() ? $this->activityRepository->getCompletedToday(Auth::id()) : [];

        return view('teletherapy.activities', compact('activities', 'completed'));
    }
}
