<?php

//Desde el HandleGet se llama a esta funcion para traer a todos los estudiantes

function getAllStudentsSubjects($conn) {
    $sql = "SELECT students.fullname as fullname, subjects.subname as subname, studentssubjects.condicion as condicion, studentssubjects.id as ss_id, students.id as stu_id, subjects.id as sub_id FROM studentssubjects
            INNER JOIN students on studentssubjects.student_id = students.id
            INNER JOIN subjects on studentssubjects.subject_id = subjects.id
     ORDER BY students.fullname";
    return $conn->query($sql);
}

//Desde el HandleGet se llama a esta funcion para traer al estudiante con tal ID, normalmente, si se lo llama es porque luego se lo va a actualizar mediante un Put

function getStudentSubjectById($conn, $id) {
    $sql = "SELECT * FROM studentssubjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

//Desde el HandlePost se llama a esta funcion para agregar al estudiante

function createStudentSubject($conn, $student_id, $subject_id, $condition) {
    $sql = "INSERT INTO studentssubjects (student_id, subject_id, condicion) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $student_id, $subject_id, $condition);
    return $stmt->execute();
}

//Desde el HandlePut se llama a esta funcion para actualizar la informacion del estudiante

function updateStudentSubject($conn, $id, $student_id, $subject_id, $condition) {
    $sql = "UPDATE studentssubjects SET student_id = ?, subject_id = ?, condicion = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $student_id, $subject_id, $condition, $id);
    return $stmt->execute();
}

//Desde el HandleDelete se llama a esta funcion para eliminar al estudiante con ID 

function deleteStudentSubject($conn, $id) {
    $sql = "DELETE FROM studentssubjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>