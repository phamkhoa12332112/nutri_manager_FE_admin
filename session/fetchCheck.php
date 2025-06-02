<?php
session_start();

$host = 'localhost';
$dbname = 'shop';
$username = 'root';
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$id = intval($_GET['id']);

if (isset($id)) {

    try {
        $stmt = $pdo->prepare("
            SELECT order_details.isDone FROM `order_details` WHERE order_details.order_id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        if (intval($row['isDone']) === 0) {
            $stmt = $pdo->prepare("
            UPDATE `order_details` SET `isDone`= 1 WHERE order_details.order_id = ?
        ");
        } else {
            $stmt = $pdo->prepare("
            UPDATE `order_details` SET `isDone`= 0 WHERE order_details.order_id = ?
        ");
        }
        
        $stmt->execute([$id]);
    
    } catch (Exception $e) {
        die("Lỗi khi truy vấn dữ liệu: " . $e->getMessage());
    }

    header(header: 'Location: ../order.php');
    exit;
}