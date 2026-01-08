<?php
session_start();

// 1. Verificăm dacă utilizatorul este logat. Dacă nu, îl trimitem la login.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Conectarea la baza de date
$conn = new mysqli("localhost", "root", "", "vulpecola");

// 3. Luăm datele utilizatorului curent
$userId = $_SESSION['user_id'];
$query = "SELECT Nume, Email, ProfilePicture, JoinDate FROM users WHERE IdUtilizator = $userId";
$result = $conn->query($query);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profilul lui <?php echo $user['Nume']; ?></title>
</head>
<body>
    <h1>Bun venit, <?php echo $user['Nume']; ?>!</h1>
    <img src="<?php echo $user['ProfilePicture']; ?>" width="150">
    <p>Email: <?php echo $user['Email']; ?></p>
    <p>Membru din: <?php echo $user['JoinDate']; ?></p>
    
    <a href="logout.php">Deconectare</a>
</body>
</html>