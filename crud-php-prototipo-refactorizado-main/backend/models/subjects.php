<?php
function getAllSubjects($conn) {
    $sql = "SELECT * FROM subjects ORDER BY credits";
    return $conn->query($sql);
}

function getSubjectById($conn, $id) {
    $sql = "SELECT * FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

function createSubject($conn, $subname, $professor, $optional, $credits) {
    $sql = "INSERT INTO subjects (subname, professor, optional, credits) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $subname, $professor, $optional, $credits);
    return $stmt->execute();
}

function updateSubject($conn, $id, $subname, $professor, $optional, $credits) {
    $sql = "UPDATE subjects SET subname = ?, professor = ?, optional = ?, credits = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $subname, $professor, $optional, $credits, $id);
    return $stmt->execute();
}

function deleteSubject($conn, $id) {
    $sql = "DELETE FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>