<?php

/**
 * ============================================================================
 *  CONTRATO DE NOTIFICACIONES
 * ============================================================================
 *
 *  Interfaz comun para todas las notificaciones.
 *  Permite polimorfismo: el cliente no conoce la clase concreta.
 *
 *  Implementada por: LegacyNotifierAdapter
 * ============================================================================
 */

interface Notification
{
    public function send(string $message): void;
}
