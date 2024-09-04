<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'inc/db_connection.php';

// 获取POST数据
$imageSrc = '';
$audioSrc = '';

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $imageSrc = 'uploads/images/' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $imageSrc);
}

if (isset($_FILES['audio']) && $_FILES['audio']['error'] == 0) {
    $audioSrc = 'uploads/audio/' . basename($_FILES['audio']['name']);
    move_uploaded_file($_FILES['audio']['tmp_name'], $audioSrc);
}

$slang = $_POST['slang'];
$explanation = $_POST['explanation'];

// 插入数据
$sql = "INSERT INTO slangs (imageSrc, slang, explanation, audioSrc) VALUES ('$imageSrc', '$slang', '$explanation', '$audioSrc')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array("message" => "New record created successfully"));
} else {
    echo json_encode(array("message" => "Error: " . $sql . "<br>" . $conn->error));
}

$conn->close();
?>