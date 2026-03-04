<?php

declare(strict_types=1);

namespace App\Core;

final class Mailer
{
    public static function enviar(string $para, string $asunto, string $mensaje): void
    {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/plain;charset=UTF-8\r\n";
        $headers .= "From: no-reply@sistema-escolar.local\r\n";

        $enviado = @mail($para, $asunto, $mensaje, $headers);
        if (!$enviado) {
            $linea = sprintf("[%s] PARA:%s | ASUNTO:%s | MENSAJE:%s\n", date('c'), $para, $asunto, $mensaje);
            file_put_contents(base_path('storage/logs/correos.log'), $linea, FILE_APPEND);
        }
    }
}
