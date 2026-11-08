<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas del patrón Decorator en src/Reports.
 * Cada decorador envuelve un Report y agrega texto al resultado de
 * generate().
 */
final class ReportDecoratorTest extends TestCase
{
    // CP-15 (positivo): el reporte base, sin decorar, solo muestra el
    // contenido.
    public function testReporteBasicoSinDecorar(): void
    {
        $reporte = new BasicReport('Pedidos del día');

        $this->assertSame('Reporte: Pedidos del día', $reporte->generate());
    }

    // CP-16 (positivo): un decorador agrega su parte al final.
    public function testUnDecoradorAgregaSuParte(): void
    {
        $reporte = new PdfReportDecorator(new BasicReport('Pedidos del día'));

        $this->assertSame('Reporte: Pedidos del día + PDF', $reporte->generate());
    }

    // CP-17 (positivo): la misma cadena que usa OrderController::report().
    // Se lee de adentro hacia afuera: firma, después PDF, después marca de agua.
    public function testCadenaCompletaDelControlador(): void
    {
        $reporte = new WatermarkDecorator(
            new PdfReportDecorator(
                new DigitalSignatureDecorator(
                    new BasicReport('Pedidos del día')
                )
            )
        );

        $this->assertSame(
            'Reporte: Pedidos del día + firma digital + PDF + marca de agua',
            $reporte->generate()
        );
    }

    // CP-18 (negativo / alternativo): cambiar el orden de los decoradores
    // cambia el resultado. Es la consecuencia negativa del patrón:
    // el orden importa y nadie lo controla.
    public function testElOrdenDeLosDecoradoresCambiaElResultado(): void
    {
        $firmaYDespuesPdf = new PdfReportDecorator(
            new DigitalSignatureDecorator(new BasicReport('X'))
        );

        $pdfYDespuesFirma = new DigitalSignatureDecorator(
            new PdfReportDecorator(new BasicReport('X'))
        );

        $this->assertSame(
            'Reporte: X + firma digital + PDF',
            $firmaYDespuesPdf->generate()
        );

        $this->assertSame(
            'Reporte: X + PDF + firma digital',
            $pdfYDespuesFirma->generate()
        );

        // assertNotSame: los dos textos tienen que ser DISTINTOS.
        $this->assertNotSame(
            $firmaYDespuesPdf->generate(),
            $pdfYDespuesFirma->generate()
        );
    }

    // CP-19 (negativo): un decorador no acepta algo que no sea un Report.
    public function testUnDecoradorNoAceptaAlgoQueNoSeaReport(): void
    {
        $this->expectException(TypeError::class);

        new WatermarkDecorator(new stdClass());
    }
}
