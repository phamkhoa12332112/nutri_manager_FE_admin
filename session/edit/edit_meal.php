<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mealId = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';

    if (!$mealId) {
        echo "<script>alert('Thiếu ID để cập nhật.'); window.history.back();</script>";
        exit;
    }

    $mealData = [
        'name' => $name,
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://localhost:3003/meals/" . $mealId);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH"); // PATCH method
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $payload = json_encode($mealData);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload),
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "<script>alert('Lỗi cURL: " . curl_error($ch) . "'); window.history.back();</script>";
        exit;
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='/testphp/admin/index.php?action=meals';</script>";
        exit;
    } else {
        echo "<script>alert('Cập nhật thất bại: " . ($response ? $response : 'Không rõ lỗi') . "'); window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>alert('Yêu cầu không hợp lệ.'); window.history.back();</script>";
}
?>