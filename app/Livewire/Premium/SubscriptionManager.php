<?php

namespace App\Livewire\Premium;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SubscriptionManager extends Component
{
    public function subscribe(string $plan)
    {
        $user = Auth::user();

        // In a real app with real Stripe products, these would be valid Price IDs.
        $priceId = $plan === 'premium' ? 'price_premium_123' : 'price_pro_123';

        try {
            return $user->newSubscription('default', $priceId)
                        ->checkout([
                            'success_url' => route('dashboard') . '?session_id={CHECKOUT_SESSION_ID}',
                            'cancel_url' => route('premium.index'),
                        ]);
        } catch (\Exception $e) {
            // Because no Stripe keys are configured, this will throw an exception.
            // We'll catch it and redirect with an error.
            session()->flash('error', 'Stripe Keys missing or invalid. Subscription could not be initiated.');
            return redirect()->route('premium.index');
        }
    }

    public function render()
    {
        return view('livewire.premium.subscription-manager')->layout('components.layouts.app');
    }
}
