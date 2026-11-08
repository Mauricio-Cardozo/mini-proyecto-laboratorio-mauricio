<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas del patrón Adapter: LegacyNotifierAdapter adapta el sistema
 * heredado (LegacyNotifier::sendMessage) a nuestro contrato
 * Notification::send.
 */
final class LegacyNotifierAdapterTest extends TestCase
{
    // CP-10 (positivo): el adapter cumple el contrato Notification.
    // Si no lo cumpliera, el resto del sistema no podría usarlo como
    // Notification.
    public function testElAdapterImplementaNotification(): void
    {
        $adapter = new LegacyNotifierAdapter(new LegacyNotifier());

        // assertInstanceOf verifica que el objeto es de esa clase o interfaz.
        $this->assertInstanceOf(Notification::class, $adapter);
    }

    // CP-11 (positivo): send() termina mostrando el mensaje con el formato
    // del sistema heredado.
    public function testSendMuestraElMensajeConFormatoLegacy(): void
    {
        $adapter = new LegacyNotifierAdapter(new LegacyNotifier());

        // LegacyNotifier hace echo. expectOutputString se declara ANTES de
        // ejecutar y PHPUnit compara todo lo que se imprimió durante la prueba.
        $this->expectOutputString('[LEGACY] Pedido 1 creado<br>');

        $adapter->send('Pedido 1 creado');
    }

    // CP-12 (positivo): el adapter delega en sendMessage() una sola vez y
    // con el mismo texto.
    public function testSendDelegaEnSendMessage(): void
    {
        // createMock crea un LegacyNotifier falso: no imprime nada,
        // solo registra las llamadas que recibe.
        $legacy = $this->createMock(LegacyNotifier::class);

        // Expectativa: sendMessage se llama exactamente una vez ('once')
        // y con el argumento 'hola' ('with'). Si no pasa, la prueba falla.
        $legacy->expects($this->once())
            ->method('sendMessage')
            ->with('hola');

        $adapter = new LegacyNotifierAdapter($legacy);
        $adapter->send('hola');
    }

    // CP-13 (negativo): un mensaje vacío no rompe el adapter.
    public function testMensajeVacioNoLanzaError(): void
    {
        $adapter = new LegacyNotifierAdapter(new LegacyNotifier());

        $this->expectOutputString('[LEGACY] <br>');

        $adapter->send('');
    }

    // CP-14 (negativo): el adapter no acepta algo que no sea un
    // LegacyNotifier. PHP lo impide por el tipo del parámetro del constructor.
    public function testNoAceptaOtroObjetoQueNoSeaLegacyNotifier(): void
    {
        // TypeError es el error que lanza PHP cuando un argumento no es del
        // tipo pedido.
        $this->expectException(TypeError::class);

        new LegacyNotifierAdapter(new stdClass());
    }
}
