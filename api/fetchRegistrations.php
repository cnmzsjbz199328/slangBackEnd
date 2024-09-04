<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include 'inc/db_connection.php';

$sql = "SELECT id, email, username FROM user_registration";
$result = $conn->query($sql);

$registrations = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $registrations[] = $row;
    }
}

echo json_encode($registrations);

$conn->close();
?>