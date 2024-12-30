<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Action;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class EmailConfirmationForReport extends Notification implements ShouldQueue
{
    use Queueable;

    private $linkForPublishing;
    private $linkForEditing;
    private $linkForDeleting;


    /**
     * Create a new notification instance.
     */
    public function __construct($linkForPublishing,$linkForEditing,$linkForDeleting)
    {
        $this->linkForPublishing = $linkForPublishing;
        $this->linkForEditing = $linkForEditing;
        $this->linkForDeleting = $linkForDeleting;
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
        $simpleMessageInstance = (new MailMessage)
            ->subject('Mascotas Perdidas PY - Confirmacion de envio de reporte')
                    ->greeting('Hola!')
                    ->line('Hemos recibido tu solicitud y tu reporte ha sido guardado correctamente en nuestra base de datos.')
                    ->line('Para publicarlo o editarlo debes hacer click en uno de los siguientes enlaces:')
                    ->action('Publicar reporte', $this->linkForPublishing)
                    ->line('Recuerda que debes hacer click en el enlace de publicacion para que tu reporte sea publicado.')
                    ->line('')
                    ->line( new HtmlString('Para borrar tu reporte debes hacer click en <a target="_blank" href="'.$this->linkForDeleting.'">Borrar reporte</a>'))
                    ->salutation('Gracias por usar Mascotas Perdidas PY');
        $simpleMessageInstance->viewData['anotherActionText'] = 'Editar el reporte';
        $simpleMessageInstance->viewData['anotherActionUrl'] = $this->linkForEditing;
        //$simpleMessageInstance->anotherActionText = 'Editar el reporte';
//        $simpleMessageInstance->anotherActionUrl = $this->linkForEditing;
        return  $simpleMessageInstance;
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
