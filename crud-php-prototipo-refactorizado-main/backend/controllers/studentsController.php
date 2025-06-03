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
    try{
        $input = json_decode(file_get_contents("php://input"), true);
        //Se toman los valores enviados a traves del formulario HTML
        $response = createStudent($conn, $input['fullname'], $input['email'], $input['age']);
        if ($response==true){
            echo json_encode(["message" => "Estudiante agregado correctamente"]);
        }
    }catch(Exception $e){
        //Tomamos el error para poder mostrarlo
        http_response_code($e->getCode() ?: 500); // Si no hay código, usamos 500
        //Devolvemos el error en formato JSON
        echo json_encode(["error" => $e->getMessage()]);
        return;
    }
}

//Si el metodo es PUT, es porque se quiere actualizar al estudiante
function handlePut($conn) {
    try{
        $input = json_decode(file_get_contents("php://input"), true);
        //Se toman los valores enviados a traves del formulario HTML
        $response = updateStudent($conn, $input['id'], $input['fullname'], $input['email'], $input['age']);
        if ($response==true){
            echo json_encode(["message" => "Estudiante actualizado correctamente"]);
        }
    }catch(Exception $e){
        //Tomamos el error para poder mostrarlo
        http_response_code($e->getCode() ?: 500); // Si no hay código, usamos 500
        //Devolvemos el error en formato JSON
        echo json_encode(["error" => $e->getMessage()]);
        return;
    }
}
//Se quiere eliminar un estudiante

function handleDelete($conn) {
    try{
        $input = json_decode(file_get_contents("php://input"), true);
        $response = deleteStudent($conn, $input['id']);
        if ($response==true){
            echo json_encode(["message" => "Eliminado correctamente"]);
        }
    }catch(Exception $e){
        http_response_code($e->getCode() ?: 500); // Si no hay código, usamos 500
        //Devolvemos el error en formato JSON
        echo json_encode(["error" => $e->getMessage()]);
        return;
    }
}
?>