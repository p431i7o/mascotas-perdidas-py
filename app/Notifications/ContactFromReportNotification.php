<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\HtmlString;

class ContactFromReportNotification extends Notification
{
    use Queueable;


    public $message;
    public $name;
    public $report;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $name, $report, $user)
    {
        $this->message = $message;
        $this->name = $name;
        $this->report = $report;
        $this->user = $user;
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
        return (new MailMessage)
                    ->subject('Mascotas Perdidas PY - Nuevo contacto sobre su reporte')
                    ->greeting('Hola!')
                    ->line('Has recibido un nuevo contacto sobre tu reporte, con el siguiente mensaje:')
                    ->line('')
                    ->line($this->message)
                    ->line('')
                    ->line('Nombre del contacto: '.$this->name)
                    ->line(new HtmlString(
                        '<a target="_blank" data-uid="'.Crypt::encrypt($this->user->id).'" href="'.route('reports.show', $this->report->id).'">Reporte</a>'))
                    ->salutation(new HtmlString('Saludos cordiales,<br/>El equipo de Mascotas Perdidas PY'));
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
