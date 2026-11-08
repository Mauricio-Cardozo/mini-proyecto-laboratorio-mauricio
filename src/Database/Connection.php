<?php

/**
 * ============================================================================
 *  CONEXION A LA BASE DE DATOS
 *  Patron esperado: SINGLETON (creacional)
 * ============================================================================
 *
 *  ❌ DEUDA SEMBRADA
 *     1. obtener() crea una conexion NUEVA en cada llamada.
 *     2. Las credenciales llegan por constantes globales definidas en index.php.
 *     3. La excepcion se traga en silencio y el sistema sigue como si nada.
 *
 *  ✅ FORMA CORRECTA
 *     1. Singleton: constructor privado + instancia estatica + getInstance().
 *     2. La configuracion se inyecta, no se lee de constantes globales.
 *     3. Si la conexion falla, el error se propaga: fallar rapido y fuerte.
 *
 *  ⚠️ Advertencia de la clase: Singleton sirve para conexion, logger y
 *     configuracion. NO para guardar el pedido actual ni el usuario logueado.
 *     Ahi deja de ser un patron y pasa a ser una variable global disfrazada.
 * ============================================================================
 */

class Connection
{
    /** Almacen en memoria para poder correr la demo sin MySQL levantado. */
    private static array $tablaEnMemoria = [];

    /** Unica instancia compartida por todo el request. */
    private static ?Connection $instance = null;

    /**
     * ✅ Constructor privado: nadie puede hacer new Connection() desde afuera
     *    y romper la garantia de instancia unica.
     */
    private function __construct()
    {
    }

    /** ✅ Singleton: la primera llamada crea la conexion, las demas la reutilizan. */
    public static function getInstance(): Connection
    {
        if (self::$instance === null) {
            self::$instance = new Connection();
        }
        return self::$instance;
    }

    public function ejecutar(string $sql): void
    {
        try {
            self::$tablaEnMemoria[] = $sql;
        } catch (Throwable $e) {
            // ✅ Fallar rapido y fuerte: si no se pudo guardar, el error se propaga.
            throw new RuntimeException('No se pudo ejecutar la consulta: ' . $sql, 0, $e);
        }
    }

    public function consultas(): array
    {
        return self::$tablaEnMemoria;
    }
}
