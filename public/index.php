<?php

/**
 * ============================================================================
 *  laboratorio-pedidos — PUNTO DE ENTRADA
 *  Metodología de Sistemas II — UTN FRRe (sede Formosa)
 * ============================================================================
 *
 *  ❌ DEUDA SEMBRADA EN ESTE ARCHIVO
 *     1. Carga manual de dependencias con require (se rompe al agregar clases).
 *     2. Credenciales de base de datos escritas en el código y versionadas.
 *     3. Ruteo resuelto con una cadena de if que crece con cada pantalla.
 *
 *  ✅ FORMA CORRECTA
 *     1. spl_autoload_register() o Composer con PSR-4.
 *     2. Configuración en /config/database.php, fuera del repositorio (.gitignore).
 *     3. Un mapa de rutas (array ruta => callable).
 *
 *  Unidad 1: los puntos 2 y 3 también son deuda de PROCESO, no solo de diseño.
 * ============================================================================
 */

// ✅ Autoload: cada clase se carga sola la primera vez que se usa.
//    Las clases no tienen namespace, asi que se busca el archivo
//    <Clase>.php en las subcarpetas de src/ (Database, Models, ...).
spl_autoload_register(function (string $class): void {
    foreach (glob(__DIR__ . '/../src/*/' . $class . '.php') as $file) {
        require_once $file;
        return;
    }
});

// ✅ Configuracion externa: las credenciales viven en config/database.php,
//    que esta en .gitignore. El repo solo versiona config/database.example.php.
//    Si todavia no se creo el archivo local, se usa la plantilla (valores vacios)
//    para que la demo arranque igual sin MySQL.
$configLocal = __DIR__ . '/../config/database.php';
$config = require(is_file($configLocal) ? $configLocal : __DIR__ . '/../config/database.example.php');

$accion = $_GET['accion'] ?? 'crear';

// ✅ Tabla de rutas: agregar una pantalla es agregar una linea al array,
//    sin modificar la logica que despacha (Abierto/Cerrado).
$controller = new OrderController();

$rutas = [
    'crear'   => fn() => $controller->create(),
    'listar'  => fn() => $controller->index(),
    'reporte' => fn() => $controller->report(),
];

($rutas[$accion] ?? fn() => print 'Accion no encontrada')();
