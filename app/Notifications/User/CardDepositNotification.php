<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CardDepositNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $data;
    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $data = $this->data;
        $trx_id = $this->data->trx_id;
        $date = Carbon::now();
        $dateTime = $date->format('Y-m-d h:i:s A');
        return (new MailMessage)
            ->subject($data->title)
            ->greeting(__("Hello") . " " . $notifiable->fullname . ",")
            ->line(__("Your virtual card deposit successful"))
            ->line(__("TRX ID") . ": " . $trx_id)
            ->line(__("Request Amount") . ": " . $data->request_amount)
            ->line(__("Fees & Charges") . ": " . $data->charges)
            ->line(__("Total Payable Amount") . ": " . $data->payable)
            ->line(__("Card Deposit Amount") . ": " . $data->card_amount)
            ->line(__("Card Pan") . ": " . $data->card_pan)
            ->line(__("Card Status") . ": " . $data->status)
            ->line(__("Date And Time") . ": " . $dateTime)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
