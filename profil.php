<?php
session_start();

// 1. Verificăm dacă utilizatorul este logat
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// 2. Conectarea la baza de date
$conn = new mysqli("localhost", "root", "", "vulpecola");
if ($conn->connect_error) {
    die("Eroare conexiune DB");
}

// 3. Luăm datele utilizatorului curent
$userId = (int) $_SESSION['user_id'];
$query = "SELECT Nume, Email, ProfilePicture, JoinDate 
          FROM users 
          WHERE IdUtilizator = $userId";
$result = $conn->query($query);

if (!$result || $result->num_rows === 0) {
    echo "Utilizator inexistent.";
    exit();
}

$user = $result->fetch_assoc();
?>
