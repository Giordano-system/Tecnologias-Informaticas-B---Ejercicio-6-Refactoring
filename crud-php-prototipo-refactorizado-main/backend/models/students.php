<?php

//Desde el HandleGet se llama a esta funcion para traer a todos los estudiantes

function getAllStudents($conn) {
    $sql = "SELECT * FROM students ORDER BY fullname";
    return $conn->query($sql);
}

//Desde el HandleGet se llama a esta funcion para traer al estudiante con tal ID, normalmente, si se lo llama es porque luego se lo va a actualizar mediante un Put

function getStudentById($conn, $id) {
    $sql = "SELECT * FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

//Desde el HandlePost se llama a esta funcion para agregar al estudiante

function createStudent($conn, $fullname, $email, $age) {
    $sql = "INSERT INTO students (fullname, email, age) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $fullname, $email, $age);
    try{
        if ($stmt->execute())
            return true;
        else
            throw new Exception("Error al agregar al estudiante" . $stmt->error, $stmt->errno); // Devuelve el ID del nuevo estudiante
    }catch (Exception $e) {
        if ($e->getCode() == 1062) { //Codigo de entrada duplicada
            throw new Exception ("Ya existe un estudiante con el mismo mail",409);
        }else{
            throw $e;
        }
    }
}

//Desde el HandlePut se llama a esta funcion para actualizar la informacion del estudiante

function updateStudent($conn, $id, $fullname, $email, $age) {
    $sql = "UPDATE students SET fullname = ?, email = ?, age = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $fullname, $email, $age, $id);
    try{
        if ($stmt->execute())
            return true;
        else
            throw new Exception("Error al actualizar al estudiante" . $stmt->error, $stmt->errno);
    }catch (Exception $e) {
        if ($e->getCode() == 1062) { //Codigo de entrada duplicada
            throw new Exception ("Ya existe un estudiante con el mismo mail",409);
        }else{
            throw $e;
        }
    }
}

//Desde el HandleDelete se llama a esta funcion para eliminar al estudiante con ID 

function deleteStudent($conn, $id) {
    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    try{
        if ($stmt->execute())
            return true;
        else
            throw new Exception("Error al eliminar al estudiante" . $stmt->error, $stmt->errno);
    }catch (Exception $e) {
        if ($e->getCode() == 1451) { //Codigo de eliminar llave foranea
            throw new Exception ("No se puede eliminar al estudiante porque tiene cursos asociados",409);
        }else{
            throw $e;
        }
    }
}
?>