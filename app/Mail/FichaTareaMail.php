<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Tarea;

class FichaTareaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tarea; // Aquí guardaremos la tarea

    /**
     * El constructor recibe la tarea desde el controlador
     */
    public function __construct(Tarea $tarea)
    {
        $this->tarea = $tarea;
    }

    /**
     * Aquí definimos el asunto y qué vista usar para el cuerpo del correo
     */
    public function build()
    {
        return $this->view('emails.ficha')
                    ->subject('Te han compartido una tarea: ' . $this->tarea->nombre);
    }
}