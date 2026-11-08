<?php

// Mientras no este el autoload (PR #4), la fabrica carga sus propios canales.
require_once __DIR__ . '/EmailChannel.php';
require_once __DIR__ . '/SmsChannel.php';

final class NotificationFactory
{
    public static function make(string $type): NotificationChannel
    {
        return match ($type) {
            'email' => new EmailChannel(),
            'sms'   => new SmsChannel(),
            // EJERCICIO 2 (TP): Si el profesor les pide agregar "whatsapp",
            // solo se agrega una línea acá y se crea la clase WhatsappChannel.php
            default => throw new InvalidArgumentException("El canal [{$type}] no está soportado.")
        };
    }
}
