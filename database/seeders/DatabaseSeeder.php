<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\TherapistProfile;
use App\Models\TherapistSchedule;
use App\Models\Consultation;
use App\Models\DestinyConnection;
use App\Models\DestinyMatch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Setup Roles
        $roles = ['Super Admin', 'Admin', 'Therapist', 'Seller', 'User'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // 2. Create Core Users
        $password = Hash::make('password');

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'AF IYI Admin', 'password' => $password, 'role' => 'admin', 'email_verified_at' => now()]
        );
        $admin->assignRole('Super Admin');

        $patient = User::firstOrCreate(
            ['email' => 'patient@example.com'],
            ['name' => 'Sarah (Patient)', 'password' => $password, 'role' => 'patient', 'email_verified_at' => now()]
        );
        $patient->assignRole('User');

        $seller = User::firstOrCreate(
            ['email' => 'seller@example.com'],
            ['name' => 'Wellness Store', 'password' => $password, 'role' => 'seller', 'email_verified_at' => now()]
        );
        $seller->assignRole('Seller');

        $therapistUser = User::firstOrCreate(
            ['email' => 'therapist@example.com'],
            ['name' => 'Dr. Emma Watson', 'password' => $password, 'role' => 'therapist', 'email_verified_at' => now()]
        );
        $therapistUser->assignRole('Therapist');

        // 3. Seed Marketplace Products
        $categories = ['Essential Oils', 'Candles', 'Journals', 'Meditation Accessories', 'Herbal Teas'];
        for ($i = 1; $i <= 15; $i++) {
            Product::withTrashed()->updateOrCreate(
                ['slug' => "wellness-product-{$i}"],
                [
                    'seller_id' => $seller->id,
                    'name' => "Premium Wellness Item {$i}",
                    'description' => "This is a detailed description for Premium Wellness Item {$i}. It helps with relaxation and mental clarity.",
                    'short_description' => "A great item for relaxation.",
                    'category' => $categories[array_rand($categories)],
                    'status' => 'active',
                    'price' => rand(15, 80),
                    'quantity' => rand(10, 100),
                    'is_featured' => $i <= 3,
                ]
            );
        }

        // 4. Seed Therapist Profile & Schedule
        $profile = TherapistProfile::updateOrCreate(
            ['user_id' => $therapistUser->id],
            [
                'bio' => 'Licensed Clinical Psychologist with 10 years of experience helping individuals overcome anxiety, depression, and burnout.',
                'specialties' => ['Anxiety', 'Depression', 'Burnout', 'Stress Management'],
                'languages' => ['English', 'French'],
                'hourly_rate' => 85.00,
                'is_active' => true,
                'status' => 'approved',
                'approved_at' => now(),
            ]
        );

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        foreach ($days as $day) {
            TherapistSchedule::firstOrCreate([
                'therapist_profile_id' => $profile->id,
                'day_of_week' => $day,
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'is_available' => true,
            ]);
        }

        // 5. Seed Consultations
        // One upcoming
        Consultation::firstOrCreate([
            'patient_id' => $patient->id,
            'therapist_profile_id' => $profile->id,
            'scheduled_at' => Carbon::tomorrow()->setHour(10)->setMinute(0),
        ], [
            'duration_minutes' => 60,
            'status' => 'scheduled',
            'type' => 'video',
            'reference' => strtoupper(Str::random(10)),
            'price' => $profile->hourly_rate,
            'payment_status' => 'paid',
        ]);

        // One completed
        Consultation::firstOrCreate([
            'patient_id' => $patient->id,
            'therapist_profile_id' => $profile->id,
            'scheduled_at' => Carbon::yesterday()->setHour(14)->setMinute(0),
        ], [
            'duration_minutes' => 60,
            'status' => 'completed',
            'type' => 'video',
            'reference' => strtoupper(Str::random(10)),
            'price' => $profile->hourly_rate,
            'payment_status' => 'paid',
        ]);

        // 6. Seed Destiny Matches
        // Create an anonymous user to match with the patient
        $anonUser = User::firstOrCreate(
            ['email' => 'anon@example.com'],
            ['name' => 'Anonymous User', 'password' => $password, 'email_verified_at' => now()]
        );
        $anonUser->assignRole('User');

        DestinyMatch::firstOrCreate([
            'user_a_id' => $patient->id,
            'user_b_id' => $anonUser->id,
        ], [
            'uuid' => (string) Str::uuid(),
            'status' => 'closed',
            'closed_at' => now()->subDays(2),
        ]);

        DestinyMatch::firstOrCreate([
            'user_a_id' => $patient->id,
            'user_b_id' => User::factory()->create()->id,
        ], [
            'uuid' => (string) Str::uuid(),
            'status' => 'active',
        ]);

        // Output success info
        echo "Database populated successfully!\n";
        echo "Login Accounts (Password for all: password):\n";
        echo "Admin: admin@af-iyi.com\n";
        echo "Patient: patient@example.com\n";
        echo "Therapist: therapist@example.com\n";
        echo "Seller: seller@example.com\n";
    }
}
