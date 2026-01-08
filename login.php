<?php
session_start();

// 1. Conectarea la baza de date vulpecola
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vulpecola";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $pass = $_POST['password'];

  
    $sql = "SELECT IdUtilizator, Parola, Nume FROM users WHERE Email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
 
    if (password_verify($pass, $row['Parola'])) {
    $_SESSION['user_id'] = $row['IdUtilizator'];
    $_SESSION['user_nume'] = $row['Nume'];
    

    header("Location: shop.php"); 
    exit();
} else {
            $error_message = "Parolă incorectă!";
        }
    } else {
        $error_message = "Acest email nu este înregistrat!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - DarkFox</title>
    <link rel="stylesheet" href="register-style.css"> <style>
        .error-text {
            color: #ff0000;
            font-weight: bold;
            display: block;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-tab">
    <h2>Sign In</h2>
    <form action="login.php" method="POST" id="login-form">
        
        <?php if (!empty($error_message)): ?>
            <span class="error-text"><?php echo $error_message; ?></span>
        <?php endif; ?>

        <input name="email" type="email" placeholder="Email" required>
        <input name="password" type="password" placeholder="Password" required>

        <button type="submit">Login</button>
        <p style="color:white; text-align:center; margin-top:10px;">
            Nu ai cont? <a href="register.php" style="color:red;">Sign Up</a>
        </p>
    </form>
</div>

</body>
</html>