<?php
/**
 * DEBUG MODE
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Habilita la visualización de errores en pantalla.
 * Muy útil durante desarrollo para detectar errores rápidamente.
 */



header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

/**
* Permite que tu API sea llamada desde otras páginas (por ejemplo, desde tu frontend).
* permite cualquier origen. En producción, podrías limitarlo.
* También acepta métodos comunes (GET, POST, etc.).
* Acepta cabecera Content-Type (útil para JSON).
*/

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

/**
 * Esto responde inmediatamente con 200 OK para permitir continuar.
 * Navegadores mandan peticiones OPTIONS antes de POST/PUT/DELETE.
 */

 $requestUri = $_SERVER['REQUEST_URI'];
 $scriptName = $_SERVER['SCRIPT_NAME'];

 /**
  * Extraemos la ruta solicitada
  */

$basePath = dirname($scriptName);
/**
 * Obtenemos el directorio en el que se alamacena el .php
 */

$routePath = str_replace($basePath . '/server.php', '', $requestUri);

/**
 * Busca la primera cadena en la tercera y si la encuentra la reemplazar por la segunda.
 */

$segments = explode('/', trim($routePath, '/'));
/**
 * Separa el resultado de $routePath en una tupla (Hablando en Python)
 */

$module = $segments[0] ?? null;
/**
 * Se queda con el primer valor encontrado que seria el modulo
 */

 $_GET['modulo'] = $segments[0];


 $routeFile = __DIR__ . "/routes/Routes.php";

 /**
  * Creamos la ruta y verificamos que exista! 
  */

 if (file_exists($routeFile)) {
    require_once($routeFile);
} else {
    http_response_code(404);
    echo json_encode(["error" => "Module not found: $module"]);
}
