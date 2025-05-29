<?php
require_once("./models/studentsSubjects.php");

function handleGet($conn) {
    if (isset($_GET['id'])) {
        $result = getStudentSubjectById($conn, $_GET['id']);
        echo json_encode($result->fetch_assoc());
    } else {
        $result = getAllStudentsSubjects($conn);
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
        $response = createStudentSubject($conn, $input['student_id'], $input['subject_id'], $input['approved']);
        if($response == true) {
            echo json_encode(["message" => "Materia asignada al estudiante correctamente"]);
        }
    }catch(Exception $e) {
        http_response_code($e->getCode() ?: 500); // Si no hay código, usamos 500
        echo json_encode(["error" => $e->getMessage()]);
        return;
    }
}

function handlePut($conn) {
    try{
        $input = json_decode(file_get_contents("php://input"), true);
        // Se toman los valores enviados a traves del formulario HTML
        $response = updateStudentSubject($conn, $input['id'], $input['student_id'], $input['subject_id'], $input['approved']);
        if ($response == true) {
        echo json_encode(["message" => "Materia actualizada correctamente"]);
        }
    }catch(Exception $e) {
        http_response_code($e->getCode() ?: 500); // Si no hay código, usamos 500
        echo json_encode(["error" => $e->getMessage()]);
        return;
    }
}

function handleDelete($conn) {
    $input = json_decode(file_get_contents("php://input"), true);
    if (deleteStudentSubject($conn, $input['id'])) {
        echo json_encode(["message" => "Eliminado correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo eliminar"]);
    }
}
?>