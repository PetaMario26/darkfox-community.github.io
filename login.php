<?php
session_start();

// 1. Conectarea la baza de date vulpecola
$servername = "db";
$username = "root";
$password = "root";
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
    

    header("Location: profil.php"); 
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login </title>
    <link rel="icon" type="image" href="https://cdn-icons-png.flaticon.com/256/12/12096.png">


    <link rel="stylesheet" href="login-style.css">
     <link rel="stylesheet" href="style.css">

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
<div class = "header">

    <img src="fox.png" width ="80" height="80" draggable="false">
    
    <nav class = " Taburi ">
	 
        <a href = "index.html">Home</a>
        <a href = "Shop.php">Shop</a>
        <a href = "Tutorials.html">Tutorials</a>
        <a href = "News.html">News</a>

	
    </nav>

    <nav class = "SignIn">
<a href = "register.php">Sign Up</a>
    <a href = "login.php">Sign In</a>

    </nav>

    <span class="buton-taburi">
            <svg id="menu-button" xmlns="http://www.w3.org/2000/svg" width="25" height="20" viewBox="0 0 50 40">
              <path class="close-path-h" d="M4513,179h50v6h-50v-6Zm0,17h50v6h-50v-6Zm0,17h50v6h-50v-6Z" transform="translate(-4513 -179)" ></path>

            </svg>

    </span>

</div>
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
<script>
window.onload = function() {
    // Verificăm dacă există un email salvat
    const savedEmail = localStorage.getItem('userEmail');
    
    if (savedEmail) {
        // Căutăm input-ul de email din pagina de login
        // Folosim name="email" pentru că așa l-ai definit în formularul de login
        const emailInput = document.querySelector('input[name="email"]');
        
        if (emailInput) {
            emailInput.value = savedEmail;
            
            // Opțional: Ștergem din memorie după ce l-am folosit (curățenie)
            localStorage.removeItem('userEmail');
        }
    }
};
</script>

</body>
</html>