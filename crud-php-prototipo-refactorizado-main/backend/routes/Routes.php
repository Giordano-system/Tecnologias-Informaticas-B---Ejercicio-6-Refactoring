<?php
require_once("./config/databaseConfig.php");

//Tomo el valor del modulo pasado por server.php para usarlo y armar la ruta para llamar al debido controlador

$modulo=$_GET['modulo'];
$path = "./controllers/{$modulo}Controller.php";
require_once($path);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        handleGet($conn);
        break;
    case 'POST':
        handlePost($conn);
        break;
    case 'PUT':
        handlePut($conn);
        break;
    case 'DELETE':
        handleDelete($conn);
        break;
    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>