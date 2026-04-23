<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Plans\Models\SubscriptionRequest;

class NewSubscriptionRequest extends Notification implements ShouldQueue
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
        return (new MailMessage)
                    ->subject("New Subscription Request: {$this->request->company->name}")
                    ->line("Company '{$this->request->company->name}' has sent a new {$this->request->type} request for the '{$this->request->plan->name}' plan.")
                    ->action('Review Request', url('/admin/subscription-requests/' . $this->request->id))
                    ->line('Please review the request in the admin panel.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_subscription_request',
            'request_id' => $this->request->id,
            'company_name' => $this->request->company->name,
            'plan_name' => $this->request->plan->name,
            'message' => "New {$this->request->type} request from '{$this->request->company->name}' for {$this->request->plan->name}.",
            'url' => route('admin.requests.show', $this->request->id),
        ];
    }
}
