<?php

/**
 * ============================================================================
 *  SISTEMA HEREDADO DEL LABORATORIO
 *  Patron esperado: ADAPTER (estructural)
 * ============================================================================
 *
 *  CONTEXTO: el laboratorio ya tenia un sistema de avisos hecho por otro
 *  proveedor. No lo podemos modificar: lo usan tambien Recepcion y Facturacion.
 *
 *  NOTA: el metodo send() y la clase CopiaDeLegacyEnNuestroSistema fueron
 *  eliminados. Ahora todo pasa por LegacyNotifierAdapter.
 * ============================================================================
 */

class LegacyNotifier
{
    /** Metodo original del proveedor. No lo podemos cambiar. */
    public function sendMessage(string $text): void
    {
        echo "[LEGACY] {$text}<br>";
    }
}
