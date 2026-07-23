<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\TherapistProfile;
use App\Models\Consultation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MoodEntry;
use App\Models\AiConversation;
use Carbon\Carbon;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Dashboard extends Component
{
    public string $period = 'daily'; // daily, weekly, monthly
    
    // Chart Data Properties for JS access
    public array $chartDates = [];
    public array $chartRevenue = [];
    public array $chartConsultations = [];
    public array $chartMood = [];
    public array $chartAi = [];

    public function updatedPeriod()
    {
        // Re-render chart via JS event dispatch
        $this->dispatch('update-charts');
    }

    public function exportData()
    {
        $metrics = $this->getChartData();
        
        $filename = "analytics_{$this->period}_" . now()->format('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Date', 'Revenue (DZD)', 'Consultations', 'Mood Score', 'AI Chat Usage'];

        $callback = function () use ($metrics, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($metrics['dates'] as $index => $date) {
                fputcsv($file, [
                    $date,
                    $metrics['revenue'][$index] ?? 0,
                    $metrics['consultations'][$index] ?? 0,
                    $metrics['mood'][$index] ?? 0,
                    $metrics['ai'][$index] ?? 0,
                ]);
            }
            fclose($file);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    private function getChartData(): array
    {
        $startDate = match ($this->period) {
            'daily'   => now()->subDays(29)->startOfDay(),
            'weekly'  => now()->subWeeks(11)->startOfWeek(),
            'monthly' => now()->subMonths(11)->startOfMonth(),
        };

        $format = match ($this->period) {
            'daily'   => 'Y-m-d',
            'weekly'  => 'Y-\WW', // e.g. 2026-W25
            'monthly' => 'Y-m',
        };

        // Orders Revenue
        $orders = Order::where('created_at', '>=', $startDate)->get();
        // Consultations Revenue & Count
        $consultations = Consultation::where('created_at', '>=', $startDate)->get();
        // Mood Trends
        $moods = MoodEntry::where('created_at', '>=', $startDate)->get();
        // AI Usage
        $aiUsage = AiConversation::where('updated_at', '>=', $startDate)->get();

        // Build labels axis
        $labels = [];
        $current = $startDate->copy();
        while ($current <= now()) {
            $labels[] = $current->format($format);
            match ($this->period) {
                'daily'   => $current->addDay(),
                'weekly'  => $current->addWeek(),
                'monthly' => $current->addMonth(),
            };
        }

        // Aggregate Data
        $revenueData = array_fill_keys($labels, 0);
        $consultationData = array_fill_keys($labels, 0);
        $moodDataSum = array_fill_keys($labels, 0);
        $moodDataCount = array_fill_keys($labels, 0);
        $aiData = array_fill_keys($labels, 0);

        foreach ($orders as $o) {
            $key = $o->created_at->format($format);
            if (isset($revenueData[$key])) {
                $revenueData[$key] += $o->total_price;
            }
        }

        foreach ($consultations as $c) {
            $key = $c->created_at->format($format);
            if (isset($revenueData[$key])) {
                $revenueData[$key] += $c->price;
                $consultationData[$key] += 1;
            }
        }

        foreach ($moods as $m) {
            $key = $m->created_at->format($format);
            if (isset($moodDataSum[$key])) {
                $moodDataSum[$key] += $m->mood_score;
                $moodDataCount[$key] += 1;
            }
        }

        foreach ($aiUsage as $a) {
            $key = $a->updated_at->format($format);
            if (isset($aiData[$key])) {
                $aiData[$key] += 1;
            }
        }

        // Averages for mood
        $moodAverages = [];
        foreach ($labels as $label) {
            $avg = $moodDataCount[$label] > 0 ? round($moodDataSum[$label] / $moodDataCount[$label], 1) : 0;
            $moodAverages[] = $avg;
        }

        return [
            'dates'         => array_values($labels),
            'revenue'       => array_values($revenueData),
            'consultations' => array_values($consultationData),
            'mood'          => $moodAverages,
            'ai'            => array_values($aiData),
        ];
    }

    public function banUser(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => false]);
        $this->dispatch('notify', type: 'success', message: 'Utilisateur banni avec succès.');
    }

    public function resolveReport(int $reportId): void
    {
        $report = \App\Models\UserReport::findOrFail($reportId);
        $report->update(['status' => 'resolved']);
        $this->dispatch('notify', type: 'success', message: 'Signalement résolu.');
    }

    public function render()
    {
        $totalUsers = User::count();
        $totalTherapists = TherapistProfile::active()->count();
        $totalConsultations = Consultation::count();
        $totalOrders = Order::count();
        $totalProductsSold = OrderItem::sum('quantity');
        $totalRevenue = Order::sum('total_price') + Consultation::sum('price');

        $metrics = $this->getChartData();
        
        $analyticsEngine = new \App\Services\Wellness\AnalyticsEngine();
        $heatmapData = $analyticsEngine->getHeatmapsData();
        $advancedStats = $analyticsEngine->getAdvancedStats();

        // Fetch recent pending moderation reports
        $reports = \App\Models\UserReport::with(['reporter', 'reported'])
            ->where('status', '!=', 'resolved')
            ->latest()
            ->take(10)
            ->get();

        $this->chartDates = $metrics['dates'];
        $this->chartRevenue = $metrics['revenue'];
        $this->chartConsultations = $metrics['consultations'];
        $this->chartMood = $metrics['mood'];
        $this->chartAi = $metrics['ai'];

        return view('livewire.admin.dashboard', [
            'kpis' => [
                'total_users'         => $totalUsers,
                'total_therapists'    => $totalTherapists,
                'total_consultations' => $totalConsultations,
                'total_orders'        => $totalOrders,
                'total_products_sold' => $totalProductsSold,
                'total_revenue'       => $totalRevenue,
            ],
            'heatmapData'        => $heatmapData,
            'advancedStats'      => $advancedStats,
            'reports'            => $reports,
        ])->layout('layouts.app');
    }
}
