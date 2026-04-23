<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Plans\Models\Subscription;

class SubscriptionStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subscription;

    /**
     * Create a new notification instance.
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = ucfirst($this->subscription->status);
        return (new MailMessage)
                    ->subject("Subscription Update: {$status}")
                    ->line("There has been an update to your subscription for the '{$this->subscription->plan->name}' plan.")
                    ->line("Current Status: {$status}")
                    ->action('View Subscription', url('/company/subscription'))
                    ->line('If you have any questions, please contact our support team.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'subscription_status_manual',
            'subscription_id' => $this->subscription->id,
            'status' => $this->subscription->status,
            'plan_name' => $this->subscription->plan->name,
            'message' => "Your subscription status for '{$this->subscription->plan->name}' has been updated to {$this->subscription->status}.",
            'url' => route('company.subscription.index'),
        ];
    }
}
