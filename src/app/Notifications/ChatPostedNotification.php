<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ChatPostedNotification extends Notification
{
    use Queueable;

    public $message;
    public $sender;

    public function __construct($message, $sender)
    {
        $this->message = $message;
        $this->sender  = $sender;
    }

    // 送信方法（今回はメール）
    public function via($notifiable)
    {
        return ['mail'];
    }

    // メール内容
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('【チャット通知】新しいメッセージがあります')
            ->greeting('こんにちは！')
            ->line($this->sender->name . ' さんから新しいメッセージが届きました。')
            ->line('---')
            ->line($this->message)
            ->line('---')
            ->action('チャットを確認する', url('/chat'))
            ->line('このメールは自動送信です。');
    }
}
