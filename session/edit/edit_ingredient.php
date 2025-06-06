<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ingredientId = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $unit = $_POST['unit'] ?? '';
    $protein = $_POST['protein'] !== '' ? floatval($_POST['protein']) : null;
    $fat = $_POST['fat'] !== '' ? floatval($_POST['fat']) : null;
    $carbs = $_POST['carbs'] !== '' ? floatval($_POST['carbs']) : null;
    $fiber = $_POST['fiber'] !== '' ? floatval($_POST['fiber']) : null;

    if (!$ingredientId) {
        echo "<script>alert('Thiếu ID nguyên liệu để cập nhật.'); window.history.back();</script>";
        exit;
    }

    $imageUrl = null;
    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] === UPLOAD_ERR_OK) {
        $filePath = $_FILES['imageFile']['tmp_name'];
        $fileName = $_FILES['imageFile']['name'];
        $fileType = $_FILES['imageFile']['type'];

        $data = [
            'file' => new CURLFile($filePath, $fileType, $fileName),
        ];

        $ch = curl_init('http://localhost:3003/images');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "<script>alert('Lỗi cURL: " . curl_error($ch) . "'); window.history.back();</script>";
            curl_close($ch);
            exit;
        }

        curl_close($ch);

        if ($response) {
            $responseData = json_decode($response, true);
            if (isset($responseData['data']) && $responseData['statusCode'] === 200) {
                $imageUrl = $responseData['data']['url'] ?? '';
            } else {
                echo "<script>alert('Lỗi tải ảnh: " . ($responseData['message'] ?? 'Không rõ lỗi') . "'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Không nhận được phản hồi từ máy chủ.'); window.history.back();</script>";
            exit;
        }
    }

    // Tính calories nếu có đủ dữ liệu
    $calories = 0;
    if ($protein !== null) $calories += $protein * 4;
    if ($carbs !== null) $calories += $carbs * 4;
    if ($fat !== null) $calories += $fat * 9;

    // Xây dựng mảng dữ liệu động
    $ingredientData = [
        'name' => $name,
        'unit' => $unit,
        'calories' => $calories !== 0 ? $calories : null,
        'protein' => $protein,
        'fat' => $fat,
        'carbs' => $carbs,
        'fiber' => $fiber,
        'imageUrl' => !empty($imageUrl) ? $imageUrl : null
    ];

    // Lọc bỏ các trường null/rỗng
    $ingredientData = array_filter(
        $ingredientData,
        function ($v) {
            if (is_array($v)) return count($v) > 0;
            return $v !== null && $v !== '';
        }
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://localhost:3003/ingredients/" . $ingredientId);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $payload = json_encode($ingredientData);
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
        echo "<script>alert('Cập nhật thành công!'); window.location.href='/testphp/admin/index.php?action=ingredients';</script>";
        exit;
    } else {
        echo "<script>alert('Cập nhật thất bại: " . ($response ? $response : 'Không rõ lỗi') . "'); window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>alert('Yêu cầu không hợp lệ.'); window.history.back();</script>";
}
?>