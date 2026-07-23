<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Coordinators\WellnessCoordinator;
use App\Repositories\MoodRepository;
use App\Repositories\ActivityRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WellnessApiController extends Controller
{
    public function __construct(
        private readonly WellnessCoordinator $coordinator,
        private readonly MoodRepository $moodRepository,
        private readonly ActivityRepository $activityRepository
    ) {}

    /**
     * Get unified dashboard wellness metrics for mobile sync.
     */
    public function dashboard()
    {
        $data = $this->coordinator->buildForUser(Auth::user());
        
        // Remove Eloquent database models from response to clean up payload
        unset($data['context']);
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Log a mood check from mobile app.
     */
    public function logMood(Request $request)
    {
        $request->validate([
            'stress_level' => 'required|integer|min:1|max:10',
            'sleep_quality' => 'required|integer|min:1|max:10',
            'energy_level' => 'required|integer|min:1|max:10',
            'emotion' => 'required|string|max:50',
            'note' => 'nullable|string|max:500',
        ]);

        $mood = $this->moodRepository->create([
            'user_id' => Auth::id(),
            'stress_level' => $request->stress_level,
            'sleep_quality' => $request->sleep_quality,
            'energy_level' => $request->energy_level,
            'emotion' => $request->emotion,
            'note' => $request->note,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Humeur enregistrée avec succès via l\'API.',
            'data' => $mood
        ]);
    }

    /**
     * Get activities checklist.
     */
    public function getActivities()
    {
        $activities = $this->activityRepository->getAll();
        $completed = $this->activityRepository->getCompletedToday(Auth::id());

        return response()->json([
            'success' => true,
            'data' => [
                'activities' => $activities,
                'completed' => $completed
            ]
        ]);
    }

    /**
     * Log activity completion.
     */
    public function completeActivity(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|string',
        ]);

        $this->activityRepository->logCompletion(Auth::id(), $request->activity_id);

        return response()->json([
            'success' => true,
            'message' => 'Activité enregistrée avec succès via l\'API.'
        ]);
    }
}
