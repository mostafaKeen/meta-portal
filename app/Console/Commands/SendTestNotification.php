<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\TestAdminNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendTestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-test-notification {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test notification to the admin or a specific email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        if ($email) {
            $user = User::where('email', $email)->first();
        } else {
            $user = User::where('role', 'super_admin')->first();
        }

        if (!$user) {
            $this->error('User not found.');
            Log::error('Test notification failed: User not found.');
            return;
        }

        $this->info("Sending test notification to: {$user->email} (Role: {$user->role})");
        Log::info("Attempting to send TestAdminNotification to: {$user->email}");

        try {
            $user->notify(new TestAdminNotification());
            $this->info('Notification sent successfully (or queued).');
            Log::info("TestAdminNotification sent successfully to: {$user->email}");
        } catch (\Exception $e) {
            $this->error('Failed to send notification: ' . $e->getMessage());
            Log::error('TestAdminNotification failed: ' . $e->getMessage(), [
                'exception' => $e
            ]);
        }
    }
}
