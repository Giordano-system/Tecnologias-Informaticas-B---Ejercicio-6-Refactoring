<?php
require_once("./models/students.php");

//Cuanto el metodo es GET, normalmente es por que se quiere tomar a los estudiantes y mostrarlos

function handleGet($conn) {
    if (isset($_GET['id'])) {
        $result = getStudentById($conn, $_GET['id']);
        echo json_encode($result->fetch_assoc());
    } else {
        $result = getAllStudents($conn);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }
}

//Cuando el metodo es POST, es por que se quiere agregar a un estudiante

function handlePost($conn) {
    $input = json_decode(file_get_contents("php://input"), true);
    //Se toman los valores enviados a traves del formulario HTML
    if (createStudent($conn, $input['fullname'], $input['email'], $input['age'])) {
        echo json_encode(["message" => "Estudiante agregado correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo agregar"]);
    }
}

//Si el metodo es PUT, es porque se quiere actualizar al estudiante
function handlePut($conn) {
    $input = json_decode(file_get_contents("php://input"), true);
    //Se toman los valores enviados a traves del formulario HTML
    if (updateStudent($conn, $input['id'], $input['fullname'], $input['email'], $input['age'])) {
        echo json_encode(["message" => "Actualizado correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo actualizar"]);
    }
}
//Se quiere eliminar un estudiante

function handleDelete($conn) {
    $input = json_decode(file_get_contents("php://input"), true);
    if (deleteStudent($conn, $input['id'])) {
        echo json_encode(["message" => "Eliminado correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo eliminar"]);
    }
}
?>