<?php
function getAllSubjects($conn) {
    $sql = "SELECT * FROM subjects";
    return $conn->query($sql);
}

function getSubjectById($conn, $id) {
    $sql = "SELECT * FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

function createSubject($conn, $subname) {
    $sql = "INSERT INTO subjects (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $subname);
    try{
        if($stmt->execute())
            return true;
        else
            throw new Exception("Error al agregar la materia: " . $stmt->error, $stmt->errno);
    }catch (Exception $e) {
        if ($e->getCode() == 1062) { //Entrada duplicada
            throw new Exception ("Materia ya registrada",409);
        }else{
            throw $e;
        }
    }
}

function updateSubject($conn, $id, $subname) {
    $sql = "UPDATE subjects SET name = ?  WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $subname, $id);
    try{
        if($stmt->execute())
            return true;
        else
            throw new Exception("Error al actualizar la materia: " . $stmt->error, $stmt->errno);
    }catch (Exception $e) {
        if ($e->getCode() == 1062) { //Entrada duplicada
            throw new Exception ("Materia ya registrada",409);
        }else{
            throw $e;
        }
    }
}

function deleteSubject($conn, $id) {
    $sql = "DELETE FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>