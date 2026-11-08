<?php

// TestCase es la clase base de PHPUnit: trae todos los assert...()
use PHPUnit\Framework\TestCase;

/**
 * Pruebas unitarias de OrderValidator.
 * Regla de PHPUnit: el nombre de la clase termina en "Test" y es igual
 * al nombre del archivo (OrderValidatorTest.php).
 */
final class OrderValidatorTest extends TestCase
{
    // CP-01 (positivo): un pedido con paciente y monto validos no tiene error.
    // Cada prueba es un metodo publico que empieza con "test" y no devuelve nada.
    public function testPedidoValidoNoDevuelveError(): void
    {
        // 1. Preparar: crear el objeto que vamos a probar
        $validator = new OrderValidator();

        // 2. Ejecutar: llamar al metodo con datos validos
        $error = $validator->validar('Juan Perez', 15000);

        // 3. Verificar: si es valido, validar() devuelve null
        $this->assertNull($error);
    }

    // CP-02 (negativo): un paciente con solo espacios se rechaza.
    public function testPacienteVacioDevuelveError(): void
    {
        $validator = new OrderValidator();

        // '   ' tiene solo espacios: trim() lo deja vacio
        $error = $validator->validar('   ', 15000);

        // assertSame compara valor Y tipo: tiene que ser exactamente ese texto
        $this->assertSame('Falta el paciente', $error);
    }

    // CP-03 (negativo): un monto en cero se rechaza.
    public function testMontoCeroDevuelveError(): void
    {
        $validator = new OrderValidator();

        $error = $validator->validar('Juan Perez', 0);

        $this->assertSame('Monto invalido', $error);
    }

    // CP-04 (negativo): un monto negativo se rechaza.
    public function testMontoNegativoDevuelveError(): void
    {
        $validator = new OrderValidator();

        $error = $validator->validar('Juan Perez', -500);

        $this->assertSame('Monto invalido', $error);
    }
}
