<?php

namespace App\Console\Commands;

use App\Models\PreparingFor;
use App\Models\State;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateReviewerAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviewer:account 
                            {email=reviewer@crackaeee.app : Email for the test/reviewer account} 
                            {password=Reviewer@2026 : Password for the account}
                            {--name=Play Store Reviewer : Display name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update a student account in the database for Play Store reviewer/testing';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));
        $password = $this->argument('password');
        $name = $this->option('name') ?: 'Play Store Reviewer';

        $defaultClass = StudentClass::first();
        $defaultState = State::first();
        $defaultTarget = PreparingFor::first();

        $student = Student::where('email', $email)->first();

        if ($student) {
            $student->update([
                'password' => Hash::make($password),
                'name' => $name,
                'is_phone_verified' => true,
                'is_profile_completed' => true,
                'status' => 'active',
                'class_id' => $student->class_id ?: $defaultClass?->id,
                'state_id' => $student->state_id ?: $defaultState?->id,
                'city' => $student->city ?: 'Kollam',
                'preparing_for_id' => $student->preparing_for_id ?: $defaultTarget?->id,
            ]);
            $this->info("Updated existing student account: {$email}");
        } else {
            $student = Student::create([
                'google_id' => 'reviewer_' . substr(md5($email), 0, 16),
                'email' => $email,
                'password' => Hash::make($password),
                'name' => $name,
                'phone' => '9999999999',
                'is_phone_verified' => true,
                'is_profile_completed' => true,
                'class_id' => $defaultClass?->id,
                'state_id' => $defaultState?->id,
                'city' => 'Kollam',
                'preparing_for_id' => $defaultTarget?->id,
                'status' => 'active',
                'last_active_at' => now(),
            ]);
            $this->info("Created new test/reviewer account: {$email}");
        }

        $this->table(
            ['Field', 'Value'],
            [
                ['Email', $email],
                ['Password', $password],
                ['Status', $student->status],
                ['Profile Completed', $student->is_profile_completed ? 'Yes' : 'No'],
                ['Phone Verified', $student->is_phone_verified ? 'Yes' : 'No'],
            ]
        );

        $this->info("Account is now ready for Play Store reviewer login.");
        return Command::SUCCESS;
    }
}
