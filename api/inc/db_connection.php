<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// 数据库连接设置
$servername = "localhost";
$username = "root";
$password = "";  // XAMPP/WAMP默认密码为空
$dbname = "slangdb";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die(json_encode(array("message" => "Connection failed: " . $conn->connect_error)));
}
?>