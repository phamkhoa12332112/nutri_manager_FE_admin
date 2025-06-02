<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Bắt đầu xử lý<br>";

    $mealData = [
        'name' => trim($_POST['name'] ?? ''),
    ];

    if (empty($mealData['name'])) {
        echo "Tên món ăn không được để trống!";
        exit;
    }

    echo "Dữ liệu nhận: ";
    var_dump($mealData);

    $ch = curl_init('http://localhost:3003/meals');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($mealData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    } else {
        echo 'Response: ' . $response;
    }

    curl_close($ch);

    if ($response) {
        $data = json_decode($response, true);
        if (isset($data['stateCode']) && $data['stateCode'] === 200) {
            header('Location: /testphp/admin/index.php?action=meals');
        } else {
            echo "Lỗi khi thêm món ăn: " . ($data['msg'] ?? 'Không rõ');
        }
    } else {
        echo "Không nhận được phản hồi từ máy chủ.";
    }
}
?>