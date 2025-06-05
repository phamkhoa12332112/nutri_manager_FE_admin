<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu JSON từ input hidden
    $json = $_POST['mealMoodJson'] ?? null;
    if (!$json) {
        echo "<script>alert('Không nhận được dữ liệu biến thể.'); window.history.back();</script>";
        exit;
    }

    // Giải mã JSON thành mảng PHP
    $data = json_decode($json, true);
    if (!$data || !isset($data['recipeId']) || !isset($data['data'])) {
        echo "<script>alert('Dữ liệu không hợp lệ.'); window.history.back();</script>";
        exit;
    }

    $recipeId = $data['recipeId'];
    $mealMoodList = $data['data']; 
    $result = [
    'data' => $mealMoodList,
];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://localhost:3003/moods/recipe/" . $recipeId);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $payload = json_encode($result);
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
        echo "<script>alert('Cập nhật thành công!'); window.location.href='/testphp/admin/index.php?action=recipes';</script>";
        exit;
    } else {
        echo "<script>alert('Cập nhật thất bại: " . ($response ? $response : 'Không rõ lỗi') . "'); window.history.back();</script>";
        exit;
    }
}
?>