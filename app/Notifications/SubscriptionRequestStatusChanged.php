<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Plans\Models\SubscriptionRequest;

class SubscriptionRequestStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $request;

    /**
     * Create a new notification instance.
     */
    public function __construct(SubscriptionRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        $status = ucfirst($this->request->status);
        return (new MailMessage)
                    ->subject("Subscription Request {$status}")
                    ->line("Your request to {$this->request->type} your subscription to the '{$this->request->plan->name}' plan has been {$this->request->status}.")
                    ->action('View Subscription', url('/company/subscription'))
                    ->line('If you have any questions, please contact support.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'subscription_request',
            'request_id' => $this->request->id,
            'status' => $this->request->status,
            'plan_name' => $this->request->plan->name,
            'message' => "Your subscription request for '{$this->request->plan->name}' has been {$this->request->status}.",
            'url' => route('company.subscription.index'),
        ];
    }
}
