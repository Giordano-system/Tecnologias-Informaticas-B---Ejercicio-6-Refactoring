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
    return $stmt->execute();
}

//Desde el HandlePut se llama a esta funcion para actualizar la informacion del estudiante

function updateStudent($conn, $id, $fullname, $email, $age) {
    $sql = "UPDATE students SET fullname = ?, email = ?, age = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $fullname, $email, $age, $id);
    return $stmt->execute();
}

//Desde el HandleDelete se llama a esta funcion para eliminar al estudiante con ID 

function deleteStudent($conn, $id) {
    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>