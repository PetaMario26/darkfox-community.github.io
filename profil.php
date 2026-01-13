<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("db", "root", "root", "vulpecola");
if ($conn->connect_error) {
    die("Eroare DB");
}

$user_id = $_SESSION['user_id'];

$sql = "
SELECT 
    u.Nume,
    u.JoinDate,
    u.ProfilePicture,
    u.Banner,
    s.total_kills,
    s.days_survived,
    s.Rank
FROM users u
LEFT JOIN players_stats s ON u.IdUtilizator = s.IdUtilizator
WHERE u.IdUtilizator = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $nume_user     = "User necunoscut";
    $avatar        = "miau.jpg";
    $banner        = "background-image.png";
    $total_kills   = 0;
    $days_survived = 0;
    $rank_user     = "N/A";
    $joined_since  = "-";
} else {
    $row = $result->fetch_assoc();

    $nume_user     = $row['Nume'];
    $avatar        = $row['ProfilePicture'] ?: "miau.jpg";
    $banner        = $row['Banner'] ?: "background-image.png";
    $total_kills   = $row['total_kills'] ?? 0;
    $days_survived = $row['days_survived'] ?? 0;
    $rank_user     = $row['Rank'] ?? "N/A";
    $joined_since  = $row['JoinDate'];
}
?>





<!DOCTYPE html>
<html lang="en">



<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DarkFox - Profil</title>
    <link rel="icon" type="image" href="https://cdn-icons-png.flaticon.com/256/12/12096.png" alt="fox.png">

    <link rel="stylesheet" href="profil-style.css">



    <style>

    .show{display:flex}

    </style>

</head>




<body>


<!-- INCEPUT MODIFICARE -->

<?php


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("db", "root", "root", "vulpecola");
if ($conn->connect_error) {
    die("Eroare DB");
}

$user_id = $_SESSION['user_id'];

$sql = "
SELECT 
    u.Nume,
    u.JoinDate,
    u.ProfilePicture,
    u.Banner,
    s.total_kills,
    s.days_survived,
    s.Rank
FROM users u
LEFT JOIN players_stats s ON u.IdUtilizator = s.IdUtilizator
WHERE u.IdUtilizator = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $nume_user     = "User necunoscut";
    $avatar        = "miau.jpg";
    $banner        = "background-image.png";
    $total_kills   = 0;
    $days_survived = 0;
    $rank_user     = "N/A";
    $joined_since  = "-";
} else {
    $row = $result->fetch_assoc();

    $nume_user     = $row['Nume'];
    $avatar        = $row['ProfilePicture'] ?: "miau.jpg";
    $banner        = $row['Banner'] ?: "background-image.png";
    $total_kills   = $row['total_kills'] ?? 0;
    $days_survived = $row['days_survived'] ?? 0;
    $rank_user     = $row['Rank'] ?? "N/A";
    $joined_since  = $row['JoinDate'];
}
?>





<!DOCTYPE html>
<html lang="en">



<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DarkFox - Profil</title>
    <link rel="icon" type="image" href="https://cdn-icons-png.flaticon.com/256/12/12096.png" alt="fox.png">

    <link rel="stylesheet" href="profil-style.css">



    <style>

    .show{display:flex}

    </style>

</head>




<body>


<!-- INCEPUT MODIFICARE -->


<div class="change-nickname">

<div class="meniu-change-nickname">



<a id="close-meniu" style="
    cursor: pointer;
    transform: translateX(18vh);
">
<svg aria-hidden="true" focusable="false" class="octicon octicon-x" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" display="inline-block" overflow="visible" style=""><path d="M3.72 3.72a.75.75 0 0 1 1.06 0L8 6.94l3.22-3.22a.749.749 0 0 1 1.275.326.749.749 0 0 1-.215.734L9.06 8l3.22 3.22a.749.749 0 0 1-.326 1.275.749.749 0 0 1-.734-.215L8 9.06l-3.22 3.22a.751.751 0 0 1-1.042-.018.751.751 0 0 1-.018-1.042L6.94 8 3.72 4.78a.75.75 0 0 1 0-1.06Z"></path></svg>
</a>


<h4 style="
    margin: 0px;
">New Nickname</h4>


<input id="new-nickname" placeholder="Enter new nickname" style="
    display: flex;
    margin: 0px 40px;
    border-radius: 8px;
">



<button type="button" id="submit-Nickname" style="margin: 0px 15vh;border-radius: 9px;background: white;">Submit</button>
    
</div>
    

</div>









<!-- MODIFICARE -->


<div class=" pagina " > 




    <div class=" nav-bar" > 


      <span id="buton-taburi" class="buton-taburi" onclick="openNav()">
            <svg id="menu-button" xmlns="http://www.w3.org/2000/svg" width="25" height="20" viewBox="0 0 50 40">
                     <path class="close-path-h" d="M4513,179h50v6h-50v-6Zm0,17h50v6h-50v-6Zm0,17h50v6h-50v-6Z" transform="translate(-4513 -179)">                                     
                     </path>
  
             </svg>

     </span>




            <div class="top-nav-bar">

                <nav class="top-nav-buttons">

                    <a href="index.php">🏠Home</a>
                    <a href="Shop.php">🛒Shop</a>
                    <a href="Tutorials.php">🔍Tutorials</a>
                    <a href="News.php">&#128226 News</a>

                </nav>

            </div>


        <div class="bottom-nav-bar">

            <nav class="bottom-nav-buttons">

                    <a href="javascript:void(0)" id="friends-toggle-btn" onclick="toggleFriendsChat()">&#128491 Friends</a>
                 

            </nav>

        </div>



     </div>




        <div class=" header " >  

          <a href="index.php">

             <img src="fox.png" width ="80" height="80" style="border-radius:10000px;">

          </a>

 </div>


<div class=" profile"> 


    
   <!-- <div class="style-profile"> -->


<div class="style-profile"
     style="background-image: url('uploads/<?php echo htmlspecialchars($banner); ?>');">




        <div class="continut-profile">


             <div class="background-image">


                <div class="profile-menu">


                                         <div class="profile-content">
    
                                                                  <span>


                                                                                     <!--  <img class="profile-image" src="miau.jpg" draggable="false">   -->   

											<img class="profile-image"
     src="uploads/<?php echo htmlspecialchars($avatar); ?>">


                                                                  </span>

                                           </div>


                                              <div class="profile-name" style="color: white;">

                                                               <h2 id="nume"><?php echo htmlspecialchars($nume_user); ?></h2>


                                                </div>


                     </div>

                    
                </div>

            

                                  <div class="edit">


                                                   <button id="Edit-Profile" class="Edit-Profile"  style="display: flex;align-items: center;"> Edit Profile 
                                                             <i class="styles__Holder-sc-b4ec5d1e-0 ktOtlK" role="img">

                                                                       <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" height="20" width="20" class="styles__StyledISvg-sc-b4ec5d1e-1 bLBxpr">

                                                                                 <path fill-rule="evenodd" clip-rule="evenodd" d="M12 17l6-6-1.414-1.414L12 14.17 7.414 9.587 6 11l6 6z" fill="currentColor"></path></svg></i> 

                                                   </button>


                                                  <div id="dropdown-menu" class="dropdown-menu"> 


                                                         <div class="style-dropdown-menu">

                                                             <button id = "NewNickname">Nickname</button>
                                                             <button id="NewAvatar">Avatar</button>
                                                             <button id="NewBackground">Background</button>

                                                  </div>


                            </div>


                                  </div>


                             <div id="dropdown-menu" class="dropdown-menu" > 


                                 <div class = "style-dropdown-menu">

                                     <button>Nickname</button>
                                     <button>Avatar</button>
                                     <button>Background</button>

                                 </div>


                            </div>


        </div>

        
<!--   Form pentru functionalitatea butoanelor Avatar si Background -->


<form id="avatarForm" enctype="multipart/form-data">
    <input type="file" id="avatarInput" name="avatar" accept="image/*" hidden>
</form>

<form id="bannerForm" enctype="multipart/form-data">
    <input type="file" id="bannerInput" name="banner" accept="image/*" hidden>
</form>





    </div>


         <div class = " Stats " >

             <header> <h2>STATISTICI</h2> </header>



                        <div class = "Style-Stats">


                                 <div class= " Column-Stats">

                                           <div class="Column-Style">

                                                     <span> Total Kills </span>
                                                     <span id="TotalKills">  <?php echo $total_kills; ?>  </span>

                                           </div>


                                           <div class="Column-Style">

                                                     <span>Days Survived</span>
                                                     <span id="DaysSurvived"> <?php echo $days_survived; ?>  </span>

                                           </div>



                                 </div>

                                 <div class="Column-Stats">

                                           <div class="Column-Style">

                                                     <span>Joined Since</span>
                                                     <span id="JoinedSince"> <?php echo htmlspecialchars($joined_since); ?>  </span>

                                           </div>

                                           <div class="Column-Style">

                                                     <span>Rank</span>
                                                     <span id="RankUser"> <?php echo htmlspecialchars($rank_user); ?> </span>
                                                     <!-- <span style="color: red; text-shadow: 0 0 20px red;">Administrator</span> -->

                                           </div>

                                 </div>


                    </div>



           </div>





                                           <div class="Misiuni">

                                               <h2>Missions</h2>





                                                            <div class="Reward">





                                               <span class="To-do1">


                                                                                                              <h2>Reward</h2>
                                                                                                              <h3>100 cooper coins</h3>


                                                                      </span>                    





                                  <span class="To-do2" >



                                                                <h3>Invite a friend on server</h3>



                                               <div class="progress">


                                                                         <h3 style="margin: 0;">0/1</h3>
                                                                         <h3 style="display:none;">&#10004</h3>

                                                                          <div class="progress-bar">
                                                                                                                      <div class="style-progress-bar"></div>
                                                                           </div>



                                                                           </div>
                                  </span>




                                                            </div>






                                           </div>




  </div> 



</div><!-- Pagina end -->





<div id="slide-navbar" class="slide-navbar">  

<div class=" navbar-header ">

    <img src="fox.png" width="80" height="80">

<span id="navbar-menu-button" class="navbar-menu-button" onclick="closeNav()" style="fill:white;">

      <svg id="menu-button" xmlns="http://www.w3.org/2000/svg" width="25" height="20" viewBox="0 0 50 40">

           <path class="close-path-h" d="M4513,179h50v6h-50v-6Zm0,17h50v6h-50v-6Zm0,17h50v6h-50v-6Z" transform="translate(-4513 -179)">
           </path>

         </svg>

</span>
    
</div>




<div>



    
                    <nav class="slide-nav-buttons">

                    <a href="index.php">Home</a>
                    <a href="shop.php">Shop</a>
                    <a href="Tutorials.php">Tutorials</a>
                    <a href="News.php">News</a>
                    <a href="#Friends"> Friends</a>
                    <a href="#Faction"> Faction</a>

                </nav>


</div> <!-- slide-navbar -->










<div id="lol-chat-container">
    <div id="lol-friends-sidebar">
      
        <div id="friends-container">
            </div>
    </div>

    <div id="lol-chat-window">
        <div id="lol-chat-header" onclick="toggleChatWindow()">
            <span id="chat-target-name">Chat</span>
            <span id="minimize-icon">_</span>
        </div>
        <div id="chat-history">
            </div>
        <div id="lol-chat-input-area">
            <input type="hidden" id="selected-friend-id">
            <input type="text" id="private-msg-input" placeholder="Trimite un mesaj...">
            <button id="btn-send-private">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="#c89b3c">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
// --- 1. LOGICA INTERFETEI (Dropdown & Nav) ---
const editBtn = document.getElementById("Edit-Profile");
const dropdown = document.getElementById("dropdown-menu");

if(editBtn) {
    editBtn.addEventListener("click", () => dropdown.classList.toggle("show"));
}

window.addEventListener("click", (e) => {
    if(dropdown && !dropdown.contains(e.target) && e.target !== editBtn){
        dropdown.classList.remove("show");
    }
});

function openNav() {
    const nav = document.getElementById("slide-navbar");
    nav.style.width = "100%"; nav.style.left = "0px";
}

function closeNav() {
    const nav = document.getElementById("slide-navbar");
    nav.style.width = "0%"; nav.style.left = "-500px";
}

// --- 2. LOGICA PROFIL (Nickname & Upload) ---
const nicknameBtn = document.getElementById("NewNickname");
const changeNicknameMenu = document.querySelector(".change-nickname");

if(nicknameBtn) {
    nicknameBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        changeNicknameMenu.style.visibility = "visible";
    });
}

document.getElementById("close-meniu")?.addEventListener("click", () => {
    changeNicknameMenu.style.visibility = "hidden";
});

document.getElementById("submit-Nickname")?.addEventListener("click", function() {
    const input = document.getElementById("new-nickname");
    const val = input.value.trim();
    if(val === "") return alert("Introdu un nume!");

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_nickname.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        const resp = JSON.parse(xhr.responseText);
        if(resp.success) {
            document.getElementById("nume").textContent = val;
            changeNicknameMenu.style.visibility = "hidden";
            input.value = "";
        }
    };
    xhr.send("nickname=" + encodeURIComponent(val));
});

function uploadImage(type) {
    const input = document.getElementById(type + 'Input');
    if(!input.files[0]) return;
    const formData = new FormData();
    formData.append(type, input.files[0]);

    fetch("update_profile_images.php", { method: "POST", body: formData })
        .then(() => location.reload());
}
function toggleFriendsChat() {
    const chatContainer = document.getElementById('lol-chat-container');
    // Verificăm dacă e ascuns (stilul display:none) și îl afișăm
    if (chatContainer.style.display === "none" || chatContainer.style.display === "") {
        chatContainer.style.display = "flex";
        loadFriends(); // Reîncărcăm lista de prieteni la deschidere
    } else {
        chatContainer.style.display = "none";
    }
}
document.getElementById("NewAvatar")?.addEventListener("click", () => document.getElementById("avatarInput").click());
document.getElementById("NewBackground")?.addEventListener("click", () => document.getElementById("bannerInput").click());
document.getElementById("avatarInput")?.addEventListener("change", () => uploadImage("avatar"));
document.getElementById("bannerInput")?.addEventListener("change", () => uploadImage("banner"));

// --- 3. LOGICA CHAT (LoL Style) ---
let currentFriendId = null;

function toggleChatWindow() {
    const container = document.getElementById('lol-chat-container');
    container.classList.toggle('minimized');
    document.getElementById('minimize-icon').innerText = container.classList.contains('minimized') ? '▲' : '_';
}

function loadFriends() {
    fetch('chat_handler.php?action=get_friends')
        .then(r => r.text())
        .then(data => document.getElementById('friends-container').innerHTML = data);
}

function selectFriend(id, nume) {
    currentFriendId = id;
    document.getElementById('selected-friend-id').value = id;
    document.getElementById('chat-target-name').innerText = nume;
    fetchPrivateMessages();
}

function fetchPrivateMessages() {
    if(!currentFriendId) return;
    fetch('chat_handler.php?action=fetch_private&friend_id=' + currentFriendId)
        .then(r => r.text())
        .then(data => {
            const history = document.getElementById('chat-history');
            history.innerHTML = data;
            history.scrollTop = history.scrollHeight;
        });
}

document.getElementById('btn-send-private')?.addEventListener('click', function() {
    const input = document.getElementById('private-msg-input');
    const msg = input.value.trim();
    if(!currentFriendId || msg === "") return;

    const fd = new FormData();
    fd.append('action', 'send_private');
    fd.append('receiver_id', currentFriendId);
    fd.append('content', msg);

    fetch('chat_handler.php', { method: 'POST', body: fd })
        .then(() => {
            input.value = "";
            fetchPrivateMessages();
        });
});

// Pornire automata
document.addEventListener('DOMContentLoaded', () => {
    loadFriends();
    setInterval(fetchPrivateMessages, 2000);
});
</script>
<script>
        // Codul tău vechi (Edit Profile, Nickname, etc.)

        // ADAUGĂ FUNCȚIA DE TOGGLE AICI
        function toggleChatWindow() {
            const chatContainer = document.getElementById('lol-chat-container');
            chatContainer.classList.toggle('minimized');
            
            const icon = document.getElementById('minimize-icon');
            icon.innerText = chatContainer.classList.contains('minimized') ? '▲' : '_';
        }

        // Restul codului de Chat (loadFriends, fetchPrivateMessages, etc.)
    </script>

</html>



</body>

</html>


</html>

