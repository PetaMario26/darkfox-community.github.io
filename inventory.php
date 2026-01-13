<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("db", "root", "root", "vulpecola");
if ($conn->connect_error) { die("Conexiune esuata: " . $conn->connect_error); }

$userId = (int) $_SESSION['user_id'];

// Luăm numele utilizatorului pentru Nav-Bar
$userResult = $conn->query("SELECT Nume FROM users WHERE IdUtilizator = $userId");
$user = $userResult->fetch_assoc();

// Luăm obiectele din inventar cu JOIN pentru a avea pozele și numele din tabelul shop
$sql = "SELECT shop.Item, shop.Image_URL, shop.Type_Item, user_inventory.quantity 
        FROM user_inventory 
        INNER JOIN shop ON user_inventory.item_id = shop.Item_id 
        WHERE user_inventory.user_id = $userId";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>DarkFox Inventory</title>
    <link rel="icon" type="image" href="https://cdn-icons-png.flaticon.com/256/12/12096.png">

    <style>
        /* CSS-ul tău de la Shop.php pentru a păstra aceeași estetică */
        body {
            background: linear-gradient(to right, rgb(50 0 0), rgb(10 10 10));
            margin: 0px;
            display: flex;
            flex-direction: column;
            gap: 11vh;
            color: white;
            font-family: sans-serif;
        }

        .nav-bar {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-around;
            background: linear-gradient(to top, rgb(0 0 0) 0%, rgb(20 20 20) 35%);
            padding: 10px;
        }

        .nav-bar-acc {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 15px;
        }

        .nav-bar-acc a {
            text-decoration: none;
            color: white;
            font-size: 20px;
            padding: 5px 15px;
            transition: 0.3s;
        }

        .nav-bar-acc a:hover {
            border: 2px solid rgb(255 255 255 / 50%);
            border-radius: 21px;
        }

        .product-section {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .products {
            display: flex;
            background: radial-gradient(rgb(150 0 0), rgb(75, 0, 0));
            flex-direction: row;
            width: 85%;
            border-radius: 15px;
            min-height: 70vh;
            box-shadow: 3px -4px 20px 3px black;
            padding: 20px;
        }

        .collection {
            padding: 0px;
            display: flex;
            flex-wrap: wrap;
            text-shadow: 2px -1px 4px black;
            list-style: none;
            justify-content: center;
            width: 100%;
        }

        .collection li {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 30px;
            position: relative; /* Pentru a poziționa cantitatea */
        }

        .collection img {
            height: 200px;
            width: 200px;
            border-radius: 15px;
            transition: linear 0.3s;
            box-shadow: 3px 0px 10px 0px black;
            object-fit: cover;
        }

        .collection img:hover {
            transform: scale(1.1);
        }

        .quantity-badge {
            background: rgba(255, 0, 0, 0.9);
            color: white;
            padding: 5px 12px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 18px;
            position: absolute;
            top: -10px;
            right: -10px;
            border: 2px solid white;
            box-shadow: 0px 0px 10px black;
            z-index: 10;
        }

        h1 { text-align: center; text-shadow: 2px 2px 10px black; }
    </style>
</head>
<body>

<nav class="nav-bar">
    <div>
        <a href="index.php">
            <img src="fox.png" height="80" width="80">
        </a>
    </div>

    <h1 style="margin: 0;">Inventory</h1>

    <div class="nav-bar-acc">
        <div style="text-align: right; margin-right: 10px;">
            
            <span style="font-weight: bold;"><?php echo htmlspecialchars($user['Nume']); ?></span>
        </div>
        <a href="Shop.php">Shop</a>
        <a href="Profil.php">Account</a>
    </div>
</nav>

<div class="product-section">
    <section class="products">
        <ul class="collection">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <li>
                        <div class="quantity-badge">x<?php echo $row['quantity']; ?></div>
                        
                        <figure> 
                            <img src="<?php echo htmlspecialchars($row['Image_URL']); ?>"> 
                        </figure>
                        
                        <div style="text-align: center; margin-top: 15px;"> 
                            <h3><?php echo htmlspecialchars($row['Item']); ?></h3> 
                            <p style="color: #ccc; font-style: italic;"><?php echo htmlspecialchars($row['Type_Item']); ?></p>
                        </div>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="text-align: center; width: 100%; margin-top: 100px;">
                    <h2>Your inventory is empty.</h2>
                    <p>Go to the shop to buy some survival gear!</p>
                    <a href="Shop.php" style="color: gold; font-size: 20px;">Open Shop</a>
                </div>
            <?php endif; ?>
        </ul>
    </section>
</div>

</body>
</html>