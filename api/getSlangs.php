<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'inc/db_connection.php';

// 查询数据
$sql = "SELECT id, imageSrc, slang, explanation, audioSrc FROM slangs";
$result = $conn->query($sql);

$slangs = array();

if ($result->num_rows > 0) {
    // 输出数据
    while($row = $result->fetch_assoc()) {
        $slangs[] = $row;
    }
} else {
    echo json_encode(array("message" => "No slangs found"));
    $conn->close();
    exit();
}

// 输出为JSON格式
echo json_encode($slangs);

$conn->close();
?>