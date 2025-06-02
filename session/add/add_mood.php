<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $moodData = [
        'moodName' => $_POST['moodName'] ?? '',
        'description' => $_POST['description'] ?? '',
        'recordedAt' => date('c'), // giờ hiện tại
    ];

    $payload = json_encode($moodData);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:3003/moods');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload)
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "<script>alert('Lỗi cURL: " . curl_error($ch) . "'); window.history.back();</script>";
        exit;
    }

    curl_close($ch);

    $data = json_decode($response, true);
    if (($data['stateCode'] ?? null) === 201 || ($data['stateCode'] ?? null) === 200) {
        echo "<script>alert('Tạo tâm trạng thành công!'); window.location.href='/testphp/admin/index.php?action=moods';</script>";
        exit;
    } else {
        echo "<script>alert('Tạo tâm trạng thất bại: " . ($data['msg'] ?? 'Không rõ lỗi') . "'); window.history.back();</script>";
        exit;
    }
}
?>