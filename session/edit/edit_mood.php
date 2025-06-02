<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $moodId = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!$moodId) {
        echo "<script>alert('Thiếu ID tâm trạng để cập nhật.'); window.history.back();</script>";
        exit;
    }

    $moodData = [
        'moodName' => $name,
        'description' => $description,
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://localhost:3003/moods/" . $moodId);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH"); // PATCH method
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $payload = json_encode($moodData);
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
        echo "<script>alert('Cập nhật thành công!'); window.location.href='/testphp/admin/index.php?action=moods';</script>";
        exit;
    } else {
        echo "<script>alert('Cập nhật thất bại: " . ($response ? $response : 'Không rõ lỗi') . "'); window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>alert('Yêu cầu không hợp lệ.'); window.history.back();</script>";
}
?>