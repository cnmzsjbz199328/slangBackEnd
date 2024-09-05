<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'inc/db_connection.php';

// 获取POST数据
$imageSrc = '';
$audioSrc = '';

$slang = $_POST['slang'];
$explanation = $_POST['explanation'];
$contributor = $_POST['contributor']; // 获取 contributor 字段
$time = $_POST['time']; // 获取 time 字段

// 定义上传目录的绝对路径
$baseDir = __DIR__ . '/../uploads';

// 确保文件夹存在
if (!is_dir($baseDir . '/images')) {
    mkdir($baseDir . '/images', 0777, true);
}
if (!is_dir($baseDir . '/audio')) {
    mkdir($baseDir . '/audio', 0777, true);
}

// 处理图片上传
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $imageExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $imageSrc = $baseDir . '/images/' . $slang . '.' . $imageExtension;
    move_uploaded_file($_FILES['image']['tmp_name'], $imageSrc);
    // 保存相对路径到数据库
    $imageSrc = 'uploads/images/' . $slang . '.' . $imageExtension;
}

// 处理音频上传
if (isset($_FILES['audio']) && $_FILES['audio']['error'] == 0) {
    $audioExtension = pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION);
    $audioSrc = $baseDir . '/audio/' . $slang . '.' . $audioExtension;
    move_uploaded_file($_FILES['audio']['tmp_name'], $audioSrc);
    // 保存相对路径到数据库
    $audioSrc = 'uploads/audio/' . $slang . '.' . $audioExtension;
}

// 插入数据
$sql = "INSERT INTO slangs (imageSrc, slang, explanation, audioSrc, contributor, timestamp) VALUES ('$imageSrc', '$slang', '$explanation', '$audioSrc', '$contributor', '$time')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array("message" => "New record created successfully"));
} else {
    echo json_encode(array("message" => "Error: " . $sql . "<br>" . $conn->error));
}

$conn->close();
?>