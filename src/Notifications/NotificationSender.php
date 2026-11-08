<?php

require_once __DIR__ . '/NotificationFactory.php';

final class NotificationSender
{
    public function enviar(string $tipo, string $destino, string $mensaje): void
    {
        // La fábrica se encarga de darme el objeto correcto
        $canal = NotificationFactory::make($tipo);

        // Polimorfismo: no me importa qué canal sea, sé que tiene el método send()
        $canal->send($mensaje, $destino);
    }
}
