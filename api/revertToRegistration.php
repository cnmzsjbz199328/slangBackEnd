<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include 'inc/db_connection.php';

$data = json_decode(file_get_contents("php://input"), true); // 确保数据解析为关联数组
$id = $data['id'];

$sql = "INSERT INTO user_registration (email, username, password)
        SELECT email, username, password FROM user_info WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // 删除用户信息
    $deleteSql = "DELETE FROM user_info WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $id);
    $deleteStmt->execute();

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$deleteStmt->close();
$conn->close();
?>