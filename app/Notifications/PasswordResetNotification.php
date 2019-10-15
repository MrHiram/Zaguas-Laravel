<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
class PasswordResetNotification extends Notification
{
    use Queueable;
   
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
       
       
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }
    public function toMail($notifiable)
    {
        // $url = 'exp://192.168.1.4:19000/--/resetPassword/'.$this->token;
    
        $url = 'exp://192.168.1.6:19000/--/checkForgotPasswordToken/'.$notifiable->remember_token;
        
        
        return (new MailMessage)
            ->subject('Hey! '.$notifiable->name.'! Your Password at Zaguas.')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $url)
            ->line('This password reset link will expire in 10 minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')])
            ->line('If you did not request a password reset, no further action is required. Token ');
    
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}