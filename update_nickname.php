<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(["success"=>false,"message"=>"Nu ești logat!"]);
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nickname = trim($_POST['nickname']);
    if($nickname === ""){
        echo json_encode(["success"=>false,"message"=>"Nickname invalid"]);
        exit();
    }

    $conn = new mysqli("localhost","root","","vulpecola");
    if($conn->connect_error){
        echo json_encode(["success"=>false,"message"=>"Eroare DB"]);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("UPDATE users SET Nume = ? WHERE IdUtilizator = ?");
    $stmt->bind_param("si", $nickname, $user_id);
    if($stmt->execute()){
        echo json_encode(["success"=>true,"message"=>"Nickname actualizat"]);
    } else {
        echo json_encode(["success"=>false,"message"=>"Eroare actualizare"]);
    }

    $stmt->close();
    $conn->close();
}
?>
