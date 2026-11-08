<?php

/**
 * ============================================================================
 *  CONTRATO DE REPORTES
 * ============================================================================
 *
 *  Interfaz comun para reportes y decoradores.
 *  Permite composicion: decoradores envuelven reportes.
 * ============================================================================
 */

interface Report
{
    public function generate(): string;
}
