<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "vulpecola");

if ($conn->connect_error) { die("Conexiune esuata: " . $conn->connect_error); }

// 1. Procesare Cumpărare (Trebuie să fie ÎNAINTE de restul interogărilor)
if (isset($_POST['buy_item'])) {
    $item_id = (int)$_POST['item_id']; 
    $user_id = (int)$_SESSION['user_id'];

    $item_query = $conn->query("SELECT Item, Cost FROM shop WHERE Item_id = $item_id");
    $wallet_query = $conn->query("SELECT Portofel FROM players_stats WHERE IdUtilizator = $user_id");

    if ($item_query && $wallet_query && $item_query->num_rows > 0 && $wallet_query->num_rows > 0) {
        $item_data = $item_query->fetch_assoc();
        $wallet_data = $wallet_query->fetch_assoc();

        if ($wallet_data['Portofel'] >= $item_data['Cost']) {
            $new_balance = $wallet_data['Portofel'] - $item_data['Cost'];
            
            // Scădem banii
            $conn->query("UPDATE players_stats SET Portofel = $new_balance WHERE IdUtilizator = $user_id");
            
            // Adăugăm în inventar
            $inventory_query = "INSERT INTO user_inventory (user_id, item_id, quantity) 
                                VALUES ($user_id, $item_id, 1) 
                                ON DUPLICATE KEY UPDATE quantity = quantity + 1";
            
            if($conn->query($inventory_query)) {
                echo "<script>
                        alert('Ai cumpărat " . addslashes($item_data['Item']) . "!');
                        window.location.href='shop.php'; 
                      </script>";
                exit(); // Oprește încărcarea paginii albe
            }
        } else {
            // FONDURI INSUFICIENTE - Redirect înapoi la shop.php după alertă
            echo "<script>
                    alert('Fonduri insuficiente! Ai nevoie de " . $item_data['Cost'] . " CC.');
                    window.location.href='shop.php';
                  </script>";
            exit(); 
        }
    } else {
        echo "<script>
                alert('Eroare: Produs sau profil negăsit!');
                window.location.href='shop.php';
              </script>";
        exit();
    }
}

// 2. Interogările pentru afișarea paginii (Rămân neschimbate)
$userId = (int) $_SESSION['user_id'];
$userResult = $conn->query("SELECT Nume FROM users WHERE IdUtilizator = $userId");
$user = $userResult->fetch_assoc();

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$categories = isset($_GET['category']) ? $_GET['category'] : [];

$sql = "SELECT * FROM shop WHERE 1=1";
if (!empty($search)) { $sql .= " AND Item LIKE '%$search%'"; }
if (!empty($categories)) {
    $catList = "'" . implode("','", array_map(array($conn, 'real_escape_string'), $categories)) . "'";
    $sql .= " AND Type_Item IN ($catList)";
}
$result = $conn->query($sql);
?>
<?php
if (isset($_POST['buy_item'])) {
    $item_id = (int)$_POST['item_id'];
    $user_id = (int)$_SESSION['user_id'];

    $item_query = $conn->query("SELECT Item, Cost FROM shop WHERE Item_id = $item_id");
    $wallet_query = $conn->query("SELECT Portofel FROM players_stats WHERE IdUtilizator = $user_id");

    if ($item_query && $wallet_query && $item_query->num_rows > 0 && $wallet_query->num_rows > 0) {
        $item_data = $item_query->fetch_assoc();
        $wallet_data = $wallet_query->fetch_assoc();

        if ($wallet_data['Portofel'] >= $item_data['Cost']) {
            $new_balance = $wallet_data['Portofel'] - $item_data['Cost'];
            $conn->query("UPDATE players_stats SET Portofel = $new_balance WHERE IdUtilizator = $user_id");
            $inventory_query = "INSERT INTO user_inventory (user_id, item_id, quantity) VALUES ($user_id, $item_id, 1) ON DUPLICATE KEY UPDATE quantity = quantity + 1";
            
            if($conn->query($inventory_query)) {
                echo "<script>alert('Ai cumparat " . addslashes($item_data['Item']) . "!'); window.location.href='shop.php';</script>";
                exit();
            }
        } else {
            // Aici e magia: alerta apare, dar window.location te tine pe loc
            echo "<script>alert('Fonduri insuficiente!'); window.location.href='shop.php';</script>";
            exit();
        }
    }
}
?>





<!DOCTYPE html>
<html lang="en">




<head>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <meta name="description" content="DarkFox Community - apocalipsa zombie cu prieteni pe Project Zomboid.">
    <title>DarkFox Shop</title>
    <link rel="icon" type="image" href="https://cdn-icons-png.flaticon.com/256/12/12096.png">



   <style>

        body{

    background: linear-gradient(to right, rgb(50 0 0), rgb(10 10 10));
    margin: 0px;
    display: flex;
    flex-direction: column;
    gap: 11vh;

        }


        .nav-bar{

    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    background: linear-gradient(to top, rgb(0 0 0) 0%, rgb(20 20 20) 35%);
    

        }


        .search-bar-decor{

    max-width: 700px;
    height: 35px;
    border-radius: 100px;
    background: white;

        }



        .search-bar{

    margin-left: 20px;
    border-style: hidden;
    border-radius: 1000px;
    width: 35vw;
    max-width: 600px;
    height: 33px;
    outline: none;
    font-size: 21px;

        }



        .filter{

    background: radial-gradient(rgb(150 0 0), rgb(100 0 0));
    display: flex;
    justify-content: flex-start;
    flex-direction: row;
    height: 75vh;
    width: 17%;
    margin: 0px 20px;
    border-radius: 15px;
    box-shadow: -3px 3px 20px 3px black;
    color: white;
    text-shadow: 2px -1px 4px black;
    }


        .products{

    display: flex;
    background: radial-gradient(rgb(150 0 0), rgb(75, 0, 0));
    flex-direction: row;
    width: 70%;
    border-radius: 15px;
    height: 100%;
    margin-bottom: 5px;
    box-shadow: 3px -4px 20px 3px black;
    color:white;
    }


      .product-section{

    display: flex;
    gap: 20px;

   }




        

   .nav-bar-acc {
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

    



        .nav-bar-acc a{

    text-decoration: none;
    color: white;
    font-size: 20px;
    margin-left: 10px;
    padding: 5px;

    }



        .nav-bar-acc a:hover{

    border: 2px solid rgb(255 255 255 / 50%);
    border-radius: 21px;

    }



        .first-list{

    padding: 0px;

    }




        .first-list li{

    display: flex;

    }


        .collection {

    padding: 0px;
    display:flex;
    flex-wrap: wrap;
    text-shadow: 2px -1px 4px black;

    }


    .collection li{

    display: flex;
    flex-direction: column;
    margin-left: 3vw;
    margin-bottom: 0px;
    margin-top: 8vh;

    }

    

    .collection figure{

        margin: 0;

    }



    .collection img{

    height: 200px;
    width: 200px;
    border-radius: 15px;
    transition: linear 0.3s;
    box-shadow: 3px 0px 10px 0px black;

    }



    .collection img:hover{

    transform: scale(1.2);

    }



        .p-sort {

    display: flex;
    width: 100%;
    flex-direction: row;

    }




    @media screen and (max-width: 1000px) {



        nav.nav-bar{

            flex-direction: column;

        }

        

        div.search-bar-decor{

            min-width: 70vw;


        }




        nav-bar-acc{

            margin: 15px;

        }




        div.product-section{

            flex-direction:column;
            align-items: center;

        }


        div.nav-bar-acc{

            margin: 20px;
         
        }




        section.filter{

            min-width: 95%;
            max-width: 320px;

        }


        section.products{

            width: 95%;

        }
        


        .collection{

            justify-content: center;

        }
        



    }  /* End of media screen 1000px */





   </style>


</head>


<body>


<nav class = "nav-bar">


<!-- ------------------------- -->


	<div>
		<a href="index.php">
   		 	<img src="fox.png" height="80" width="80">
		</a>
	</div>



<!-- ------------------------- -->


<div>
    <div class = "search-bar-decor" >
        <form method="get">
            <input type="text" name="search" class="search-bar" 
       placeholder="Search" 
       value="<?php echo htmlspecialchars($search); ?>">
        </form>
    </div>

</div>
    


<!-- ------------------------- -->



<div class = "nav-bar-acc">



 <div class="nav-bar-acc">
    <?php if(isset($_SESSION['user_id'])): 
        $u_id = $_SESSION['user_id'];
        
        // Interogăm tabela players_stats pentru coloana Portofel
        // Presupunem că legătura se face prin IdUtilizator (sau IdJucator)
        $wallet_query = $conn->query("SELECT Portofel FROM players_stats WHERE IdUtilizator = $u_id");
        
        if ($wallet_query && $wallet_query->num_rows > 0) {
            $wallet_data = $wallet_query->fetch_assoc();
            $balanta = $wallet_data['Portofel'];
        } else {
            $balanta = 0; // Valoare default dacă nu găsește înregistrarea
        }
    ?>
        <div style="display: flex; flex-direction: column; align-items: flex-end; margin-right: 15px;">
            <span style="color: #00ff00; font-weight: bold; font-size: 18px;">
                💰 <?php echo number_format($balanta); ?> CC
            </span>
            <small style="color: #aaa; font-size: 11px;">BALANCE</small>
        </div>
        
        <a href="Profil.php"> My Account </a>
    <?php else: ?>
        <a href="login.php"> Sign In </a>
    <?php endif; ?>

   <a href="inventory.php"> Inventory </a>
   
         <a><?php echo htmlspecialchars($user['Nume']); ?></a>
        
   

   
    
</header>
</div>



</div>
    
<!-- ------------------------- -->


</nav>






<div class = "product-section">





   <section class="filter">
    <form method="GET" action="shop.php" style="width: 100%;">
        <fieldset style="border: none; margin:6px;">
            <header>
                <h3>Filter</h3>
            </header>

            <ul class="first-list">
                <li>
                    <input type="checkbox" name="category[]" value="Weapons" 
                           onchange="this.form.submit()" 
                           <?php if(isset($_GET['category']) && in_array('Weapons', $_GET['category'])) echo 'checked'; ?>>
                    <label> <h3>Weapons</h3> </label>
                </li>

                <li>
                    <input type="checkbox" name="category[]" value="Starter-kits" 
                           onchange="this.form.submit()"
                           <?php if(isset($_GET['category']) && in_array('Starter-kits', $_GET['category'])) echo 'checked'; ?>>
                    <label> <h3>Starter-kits</h3> </label>
                </li>

                <li>
                    <input type="checkbox" name="category[]" value="Cars" 
                           onchange="this.form.submit()"
                           <?php if(isset($_GET['category']) && in_array('Cars', $_GET['category'])) echo 'checked'; ?>>
                    <label> <h3>Cars</h3> </label>
                </li>
            </ul>
        </fieldset>
    </form>
</section>



<!-- ------------------------- -->


   <section class="products">




      <div class="p-sort">  <!-- Actual Products -->




            <ul  class="collection">


<ul class="collection">
    <?php 
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) { ?>
            <li>
                <figure> 
                    <img src="<?php echo htmlspecialchars($row['Image_URL']); ?>"> 
                </figure>
                <div> 
                    <h3><?php echo htmlspecialchars($row['Item']); ?></h3> 
                    <p><?php echo number_format($row['Cost']); ?> credits</p> 
                    
                   <form method="POST" action="shop.php">
    <input type="hidden" name="item_id" value="<?php echo $row['Item_id']; ?>">
    
    <button type="submit" name="buy_item" 
            data-cost="<?php echo $row['Cost']; ?>" 
            onclick="return checkMoney(this);" 
            style="background: green; color: white; border: none; padding: 5px 15px; border-radius: 5px; cursor: pointer;">
        Buy Now
    </button>
</form>
                </div>
            </li>
        <?php } 
    } ?>
</ul>







            </ul>





      </div> <!-- Actual Products ending -->
    
   </section> <!-- products section class ending-->




</div> <!-- filter+products end -->


<script>

document.addEventListener("keydown", function(event) {
  console.log("Tasta apăsată: " + event.key);
});


</script>



</body>


</html>
