<?php

require_once __DIR__ . '/Report.php';
/**
 * ============================================================================
 *  DECORATOR: MARCA DE AGUA
 * ============================================================================
 *
 *  Envuelve un Report y agrega "+ marca de agua" al resultado.
 *  Consecuencia: el orden con respecto a otros decoradores cambia el resultado.
 * ============================================================================
 */

final class WatermarkDecorator implements Report
{
    public function __construct(private Report $report)
    {
    }

    public function generate(): string
    {
        return $this->report->generate() . ' + marca de agua';
    }
}
