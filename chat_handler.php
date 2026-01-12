<?php
session_start();
$conn = new mysqli("localhost", "root", "", "vulpecola");
if ($conn->connect_error) { die("Eroare DB"); }

if (!isset($_SESSION['user_id'])) { exit("Neautorizat"); }
$my_id = $_SESSION['user_id'];

// --- 1. ADUCEREA LISTEI DE PRIETENI ---
// --- 1. MOD DE TEST: ADUCE TOȚI UTILIZATORII ---
if (isset($_GET['action']) && $_GET['action'] == 'get_friends') {
    // Selectăm toți utilizatorii în afară de tine
    $sql = "SELECT IdUtilizator, Nume, ProfilePicture FROM users WHERE IdUtilizator != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $my_id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    while($f = $res->fetch_assoc()) {
        $avatar = $f['ProfilePicture'] ?: "miau.jpg";
        echo "<div class='friend-item' onclick='selectFriend(".$f['IdUtilizator'].", \"".htmlspecialchars($f['Nume'])."\")' style='cursor:pointer; padding:10px; border-bottom:1px solid #444; color:white;'>
                <img src='uploads/".$avatar."' style='width:30px; border-radius:50%; margin-right:10px;'>
                <span>".htmlspecialchars($f['Nume'])."</span>
              </div>";
    }
    exit();
}

// --- 2. ADUCEREA MESAJELOR PRIVATE ---
if (isset($_GET['action']) && $_GET['action'] == 'fetch_private') {
    $friend_id = intval($_GET['friend_id']);

    $sql = "SELECT sender_id, content, DATE_FORMAT(sent_time, '%H:%i') as ora 
            FROM mesaje 
            WHERE (sender_id = ? AND receiver_id = ?) 
               OR (sender_id = ? AND receiver_id = ?) 
            ORDER BY sent_time ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $my_id, $friend_id, $friend_id, $my_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $clasa = ($row['sender_id'] == $my_id) ? 'msg-me' : 'msg-friend';
        echo "<div class='message $clasa'>";
        echo "<p>" . htmlspecialchars($row['content']) . "</p>";
        echo "<span>" . $row['ora'] . "</span>";
        echo "</div>";
    }
    exit();
}

// --- 3. TRIMITE MESAJ ---
if (isset($_POST['action']) && $_POST['action'] == 'send_private') {
    $receiver_id = intval($_POST['receiver_id']);
    $content = trim($_POST['content']);

    if (!empty($content)) {
        $stmt = $conn->prepare("INSERT INTO mesaje (sender_id, receiver_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $my_id, $receiver_id, $content);
        $stmt->execute();
    }
    exit();
}
?>