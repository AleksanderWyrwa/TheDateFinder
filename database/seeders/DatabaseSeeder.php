<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PersonalProfile;
use App\Models\Preference;
use App\Models\Photo;
use App\Models\MeetingAvailability;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tworzenie 10 użytkowników, 5 mężczyzn i 5 kobiet
        User::factory(10)->create()->each(function ($user, $index) {
            // Ustaw płeć w zależności od indeksu (5 mężczyzn, 5 kobiet)
            $gender = $index < 5 ? 'male' : 'female';

            // Powiązany profil
            $user->personalProfile()->create(
                PersonalProfile::factory()->make([
                    'first_name' => $gender === 'male' ? fake()->firstNameMale() : fake()->firstNameFemale(),
                ])->toArray()
            );

            // Powiązane preferencje
            $user->preferences()->create(Preference::factory()->make()->toArray());

            // Powiązane zdjęcia
            $user->photos()->createMany(Photo::factory(3)->make()->toArray());

            // Powiązane dostępności spotkań (3 dostępności na użytkownika)
            $user->meetingAvailabilities()->createMany(
                MeetingAvailability::factory(3)->make()->toArray()
            );
        });
    }
}
