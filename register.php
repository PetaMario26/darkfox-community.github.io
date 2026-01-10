<?php
session_start();

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
    $nume = $conn->real_escape_string($_POST['nume']);
    $email = $conn->real_escape_string($_POST['email']);
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($pass !== $confirm_pass) {
        $error_message = "Parolele nu sunt Identice!";
    } else {
        $checkEmail = $conn->query("SELECT IdUtilizator FROM users WHERE Email = '$email'");
        
        if ($checkEmail->num_rows > 0) {
            $error_message = "Acest email este deja folosit!";
        } else {
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            
            // 1. Inserăm utilizatorul în tabela 'users'
            $sql = "INSERT INTO users (Nume, Email, Parola, ProfilePicture) 
                    VALUES ('$nume', '$email', '$hashed_password', 'miau.jpg')";

            if ($conn->query($sql) === TRUE) {
                // 2. AFLĂM ID-UL NOU CREAT
                $new_user_id = $conn->insert_id; 

                // 3. CREĂM RÂNDUL ÎN 'players_stats' PENTRU ECONOMIE
                // Adăugăm ID-ul și suma de start în Portofel
                // În register.php, după insert_id
$sql_stats = "INSERT INTO players_stats (IdUtilizator, Portofel, total_kills, days_survived, Rank) 
              VALUES ($new_user_id, 500, 0, 0, 'Survivor')";
$conn->query($sql_stats);

                // Salvăm ID-ul în sesiune și mergem la profil
                $_SESSION['user_id'] = $new_user_id;
                header("Location: Profil.php"); 
                exit();
            } else {
                $error_message = "Eroare tehnică: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Register </title>
    <link rel="icon" type="image" href="https://cdn-icons-png.flaticon.com/256/12/12096.png">
    <link rel="stylesheet" href="register-style.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Stil pentru mesajul de eroare */
        .error-box {
   
    color: #ff4444;                         
        
    padding: 15px;                         
    margin-bottom: 20px;                   
    border-radius: 8px;                   
    text-align: center;                 
    font-weight: bold;                    
    width: 100%;                          
    box-sizing: border-box;              
}
    </style>
</head>
<body>

<div class="header">
    <a href="index.php">
        <img src="fox.png" width="80" height="80" draggable="false">
    </a>
    <nav class="Taburi">
        <a href="index.php">Home</a>
        <a href="Shop.php">Shop</a>
        <a href="Tutorials.php">Tutorials</a>
        <a href="News.php">News</a>
    </nav>
    <nav class="SignIn">
        <a href="register.php">Sign Up</a>
        <a href="login.php">Sign In</a>
    </nav>
    <span class="buton-taburi" id="menu-button">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="20" viewBox="0 0 50 40">
            <path d="M0,0h50v6h-50v-6Zm0,17h50v6h-50v-6Zm0,17h50v6h-50v-6Z" fill="white"></path>
        </svg>
    </span>
</div>

<div id="menu" class="menu-window">
    <span class="buttons-window">
        <a href="index.php"><img src="fox.png" width="80" height="80"></a>
        <svg id="menu-close" xmlns="http://www.w3.org/2000/svg" width="25" height="20" viewBox="0 0 50 40">
             <path d="M0,0h50v6h-50v-6Zm0,17h50v6h-50v-6Zm0,17h50v6h-50v-6Z" fill="white"></path>
        </svg>
    </span>
    <nav class="tab-window">
        <a href="index.php">Home</a>
        <a href="Shop.php">Shop</a>
        <a href="Tutorials.html">Tutorials</a>
        <a href="News.html">News</a>
        <a href="login.php ">Sign In</a>
    </nav>
</div>

<div class="login-tab">
    <form action="register.php" method="POST" id="login-form">
        
        <?php if (!empty($error_message)): ?>
            <div class="error-box"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <input name="nume" placeholder="Nume" required>
        <input name="email" type="email" placeholder="Email" required>
        <input name="password" type="password" placeholder="Password" required minlength="6">
        <input name="confirm_password" placeholder="Repeat Password" type="password" required minlength="6">

        <button type="submit">Register</button>
    </form>
</div>

<script>
    document.getElementById("menu-button").addEventListener("click", function() {
        document.getElementById("menu").style.top = "0";
    });

    document.getElementById("menu-close").addEventListener("click", function() {
        document.getElementById("menu").style.top = "-500vw";
    });
</script>
</body>
</html>