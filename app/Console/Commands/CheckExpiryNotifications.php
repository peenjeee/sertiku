<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use App\Models\User;
use App\Notifications\CertificateExpired;
use App\Notifications\SubscriptionExpiringSoon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification; // Facade for sending anonymous notifications

class CheckExpiryNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:expiry-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expiring subscriptions and expired certificates, then send notifications.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting expiry check...');

        // 1a. Check Subscriptions Expiring in 3 Days (Lembaga) -> SubscriptionExpiringSoon
        $targetDate3Days = now()->addDays(3)->format('Y-m-d');
        $this->info("Checking subscriptions expiring on (3 days): $targetDate3Days");
        $users = User::whereDate('subscription_expires_at', $targetDate3Days)->get();
        $this->info("Found {$users->count()} users with subscriptions expiring in 3 days.");

        foreach ($users as $user) {
            $user->notify(new SubscriptionExpiringSoon($user));
            $this->info("Sent SubscriptionExpiringSoon to: {$user->email}");
        }

        // 1b. Check Subscriptions Expiring Today (Lembaga) -> SubscriptionExpired
        $today = now()->format('Y-m-d');
        $this->info("Checking subscriptions expiring today: $today");
        $usersExpired = User::whereDate('subscription_expires_at', $today)->get();
        $this->info("Found {$usersExpired->count()} users with subscriptions expiring today.");

        foreach ($usersExpired as $user) {
            $user->notify(new \App\Notifications\SubscriptionExpired($user));
            $this->info("Sent SubscriptionExpired to: {$user->email}");
        }

        // 2a. Check Certificates Expiring in 3 Days (Recipient) -> CertificateExpiringSoon
        $this->info("Checking certificates expiring on (3 days): $targetDate3Days");
        $certsExpiringSoon = Certificate::active()
            ->whereDate('expire_date', $targetDate3Days)
            ->get();
        $this->info("Found {$certsExpiringSoon->count()} certificates expiring in 3 days.");

        foreach ($certsExpiringSoon as $certificate) {
            if ($certificate->recipient_email) {
                Notification::route('mail', $certificate->recipient_email)
                    ->notify(new \App\Notifications\CertificateExpiringSoon($certificate));
                $this->info("Sent CertificateExpiringSoon to: {$certificate->recipient_email}");
            }
        }

        // 2b. Check Certificates Expiring Today (Recipient) -> CertificateExpired
        $this->info("Checking certificates expiring today: $today");
        $certificates = Certificate::active()
            ->whereDate('expire_date', $today)
            ->get();

        $this->info("Found {$certificates->count()} certificates expiring today.");

        foreach ($certificates as $certificate) {
            // Send to recipient email
            if ($certificate->recipient_email) {
                Notification::route('mail', $certificate->recipient_email)
                    ->notify(new CertificateExpired($certificate));
                $this->info("Sent certificate expired notice to: {$certificate->recipient_email}");
            }
        }

        $this->info('Expiry check completed.');
    }
}
