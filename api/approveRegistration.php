<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include 'inc/db_connection.php';

$data = json_decode(file_get_contents('php://input'), true); // 从前端获取 JSON 数据并解析为关联数组
$id = $data['id']; // 获取解析后的数据中的 'id' 字段

$sql = "INSERT INTO user_info (email, username, password)
        SELECT email, username, password FROM user_registration WHERE id = ?"; // 插入语句，从 user_registration 表中选择数据插入到 user_info 表中

$stmt = $conn->prepare($sql); // 准备 SQL 语句
$stmt->bind_param("i", $id); // 绑定参数，"i" 表示整数类型

if ($stmt->execute()) { // 执行插入操作
    // 删除注册信息
    $deleteSql = "DELETE FROM user_registration WHERE id = ?"; // 删除语句，从 user_registration 表中删除指定 id 的记录
    $deleteStmt = $conn->prepare($deleteSql); // 准备删除语句
    $deleteStmt->bind_param("i", $id); // 绑定参数，"i" 表示整数类型
    if ($deleteStmt->execute()) { // 执行删除操作
        echo json_encode(['success' => true]); // 返回成功信息
    } else {
        echo json_encode(['success' => false, 'error' => $deleteStmt->error]); // 返回删除操作的错误信息
    }
    $deleteStmt->close(); // 关闭删除语句
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]); // 返回插入操作的错误信息
}

$stmt->close(); // 关闭插入语句
$conn->close(); // 关闭数据库连接
?>