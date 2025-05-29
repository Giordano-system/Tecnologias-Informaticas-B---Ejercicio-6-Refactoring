<?php

//Desde el HandleGet se llama a esta funcion para traer a todos los estudiantes

function getAllStudentsSubjects($conn) {
    $sql = "SELECT students.fullname as fullname, subjects.name as name, students_subjects.approved as approved, students_subjects.id as ss_id, students.id as stu_id, subjects.id as sub_id FROM students_subjects
            INNER JOIN students on students_subjects.student_id = students.id
            INNER JOIN subjects on students_subjects.subject_id = subjects.id
     ORDER BY students.fullname";
    return $conn->query($sql);
}

//Desde el HandleGet se llama a esta funcion para traer al estudiante con tal ID, normalmente, si se lo llama es porque luego se lo va a actualizar mediante un Put

function getStudentSubjectById($conn, $id) {
    $sql = "SELECT * FROM students_subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

//Desde el HandlePost se llama a esta funcion para agregar al estudiante

function createStudentSubject($conn, $student_id, $subject_id, $condition) {
    $sql = "INSERT INTO students_subjects (student_id, subject_id, approved) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $student_id, $subject_id, $condition);
    try{
        if ($stmt->execute())
            return true;
        else
            throw new Exception("Error al asignar la materia al estudiante: " . $stmt->error, $stmt->errno);
    } catch (Exception $e) {
        if ($e->getCode() == 1062) { // Duplicate entry error code
            throw new Exception("Ya existe una asignación de esta materia para este estudiante", 409);
        } else {
            throw $e;
        }
    }
}

//Desde el HandlePut se llama a esta funcion para actualizar la informacion del estudiante

function updateStudentSubject($conn, $id, $student_id, $subject_id, $condition) {
    $sql = "UPDATE students_subjects SET student_id = ?, subject_id = ?, approved = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $student_id, $subject_id, $condition, $id);
    try{
        if ($stmt->execute())
            return true;
        else
            throw new Exception("Error al actualizar la materia del estudiante: " . $stmt->error, $stmt->errno);
    } catch (Exception $e) {
        if ($e->getCode() == 1062) { // Duplicate entry error code
            throw new Exception("Ya existe una asignación de esta materia para este estudiante", 409);
        } else {
            throw $e;
        }
    }
}

//Desde el HandleDelete se llama a esta funcion para eliminar al estudiante con ID 

function deleteStudentSubject($conn, $id) {
    $sql = "DELETE FROM students_subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>