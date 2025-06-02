<?php
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $recipeId = [$id];

    $payload = json_encode(['ids' => $recipeId]);
    if ($payload === false) {
        echo "<script>alert('Lỗi khi mã hoá dữ liệu JSON: " . json_last_error_msg() . "'); window.history.back();</script>";
        exit;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:3003/recipes");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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

    if ($response) {
        $data = json_decode($response, true);
        if ($httpCode === 200 || ($data['stateCode'] ?? null) === 200) {
            echo "<script>alert('Xoá món ăn thành công!'); window.location.href='/testphp/admin/index.php?action=recipes';</script>";
            exit;
        } else {
            echo "<script>alert('Lỗi khi xoá món ăn: " . ($data['msg'] ?? 'Không rõ') . " (Mã lỗi: $httpCode)'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Không nhận được phản hồi từ máy chủ.'); window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>alert('Thiếu ID để xoá.'); window.history.back();</script>";
    exit;
}
?>