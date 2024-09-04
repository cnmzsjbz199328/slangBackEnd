<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'inc/db_connection.php';

$data = json_decode(file_get_contents("php://input"));

$username = $data->username;
$password = $data->password;

if (empty($username) || empty($password)) {
    echo json_encode(array("success" => false, "message" => "Username and password are required"));
    exit;
}

$sql = "SELECT password FROM user_info WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];

    if (password_verify($password, $hashed_password)) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false, "message" => "Invalid username or password"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid username or password"));
}

$stmt->close();
$conn->close();
?>