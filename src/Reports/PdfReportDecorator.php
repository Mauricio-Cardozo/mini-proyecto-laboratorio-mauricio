<?php

require_once __DIR__ . '/Report.php';
/**
 * ============================================================================
 *  DECORATOR: PDF
 * ============================================================================
 *
 *  Envuelve un Report y agrega "+ PDF" al resultado.
 *  Consecuencia: el orden con respecto a otros decoradores cambia el resultado.
 * ============================================================================
 */

final class PdfReportDecorator implements Report
{
    public function __construct(private Report $report)
    {
    }

    public function generate(): string
    {
        return $this->report->generate() . ' + PDF';
    }
}
