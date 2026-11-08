<?php

/**
 * ============================================================================
 *  ADAPTER: LEGACY NOTIFIER → NOTIFICATION
 * ============================================================================
 *
 *  El sistema heredado del laboratorio (LegacyNotifier) tiene un metodo
 *  sendMessage() con firma distinta a nuestro contrato Notification.
 *
 *  Este Adapter traduce la llamada usando COMPOSICION:
 *    Nuestro sistema -> Notification <- Adapter -> LegacyNotifier
 *
 *  Consecuencia negativa: agrega una capa de indirección (un archivo más).
 *  Si mañana el proveedor cambia sendMessage() por dispatch(), solo hay
 *  que tocar este archivo.
 * ============================================================================
 */

// Mientras no este el autoload, el adapter carga su contrato y la clase que adapta.
require_once __DIR__ . '/../Notifications/Notification.php';
require_once __DIR__ . '/LegacyNotifier.php';

class LegacyNotifierAdapter implements Notification
{
    /** COMPOSICION: el adapter TIENE el sistema viejo adentro */
    public function __construct(private LegacyNotifier $legacy)
    {
    }

    /**
     * Traduce Notification::send() → LegacyNotifier::sendMessage()
     * Unico punto de traduccion en todo el sistema.
     */
    public function send(string $message): void
    {
        $this->legacy->sendMessage($message);
    }
}
