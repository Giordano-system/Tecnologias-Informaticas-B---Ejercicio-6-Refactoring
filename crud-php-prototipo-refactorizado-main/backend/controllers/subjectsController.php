<?php
require_once("./models/subjects.php");

function handleGet($conn) {
    if (isset($_GET['id'])) {
        $result = getSubjectById($conn, $_GET['id']);
        echo json_encode($result->fetch_assoc());
    } else {
        $result = getAllSubjects($conn);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }
}

function handlePost($conn) {
    try{
        $input = json_decode(file_get_contents("php://input"), true);
        $response = createSubject($conn, $input['name']);
        // Si la respuesta es verdadera, significa que se agregó correctamente
        if ($response==true) {
            echo json_encode(["message" => "Materia agregada correctamente"]);
        }
    } catch(Exception $e) {
        //Tomamos el error para poder mostrarlo
        http_response_code($e->getCode() ?: 500); // Si no hay código, usamos 500
        //Devolvemos el error en formato JSON
        echo json_encode(["error" => $e->getMessage()]);
        return;
    }
}

function handlePut($conn) {
    try{
        $input = json_decode(file_get_contents("php://input"), true);
        // Se toman los valores enviados a traves del formulario HTML
        $response = updateSubject($conn, $input['id'], $input['name']);
        if ($response == true) {
            echo json_encode(["message" => "Materia actualizada correctamente"]);
        }
    }catch(Exception $e) {
        //Tomamos el error para poder mostrarlo
        http_response_code($e->getCode() ?: 500); // Si no hay código, usamos 500
        //Devolvemos el error en formato JSON
        echo json_encode(["error" => $e->getMessage()]);
        return;
    }
}

function handleDelete($conn) {
    try{
        $input = json_decode(file_get_contents("php://input"), true);
        $response = deleteSubject($conn, $input['id']);
        if ($response == true) {
            echo json_encode(["message" => "Materia eliminada correctamente"]);
        }
    } catch(Exception $e) {
        //Tomamos el error para poder mostrarlo
        http_response_code($e->getCode() ?: 500); // Si no hay código, usamos 500
        //Devolvemos el error en formato JSON
        echo json_encode(["error" => $e->getMessage()]);
        return;
    }
}
?>