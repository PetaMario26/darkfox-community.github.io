<?php
session_start();
if (!isset($_SESSION['user_id'])) exit;

$conn = new mysqli("localhost", "root", "", "vulpecola");
$user_id = $_SESSION['user_id'];

$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_FILES['avatar'])) {
    $fileName = uniqid() . "_" . $_FILES['avatar']['name'];
    move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $fileName);

    $stmt = $conn->prepare("UPDATE users SET ProfilePicture=? WHERE IdUtilizator=?");
    $stmt->bind_param("si", $fileName, $user_id);
    $stmt->execute();
}

if (isset($_FILES['banner'])) {
    $fileName = uniqid() . "_" . $_FILES['banner']['name'];
    move_uploaded_file($_FILES['banner']['tmp_name'], $uploadDir . $fileName);

    $stmt = $conn->prepare("UPDATE users SET Banner=? WHERE IdUtilizator=?");
    $stmt->bind_param("si", $fileName, $user_id);
    $stmt->execute();
}
?>
