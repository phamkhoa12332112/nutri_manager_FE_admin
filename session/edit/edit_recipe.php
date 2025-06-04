<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipeId = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';

    // Lấy danh sách nguyên liệu
    $ingredients = [];
    if (isset($_POST['ingredients']) && is_array($_POST['ingredients'])) {
        foreach ($_POST['ingredients'] as $item) {
            if (!empty($item['id']) && isset($item['quantity'])) {
                $ingredients[] = [
                    'id' => intval($item['id']),
                    'quantity' => floatval($item['quantity'])
                ];
            }
        }
    }

    // Lấy danh sách bữa ăn
    $meals = [];
    if (isset($_POST['meals']) && is_array($_POST['meals'])) {
        foreach ($_POST['meals'] as $item) {
            if (!empty($item['id'])) {
                $meals[] = [
                    'id' => intval($item['id'])
                ];
            }
        }
    }
    
    // Xử lý ảnh nếu có upload
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

    // Chuẩn bị dữ liệu gửi API
    $recipeData = [
        'name' => $name,
        'description' => $description,
        'ingredients' => $ingredients,
        'meals' => $meals
    ];
    if ($imageUrl) $recipeData['imageUrl'] = $imageUrl;

    // Loại bỏ các trường rỗng/null/array rỗng
    $recipeData = array_filter(
        $recipeData,
        function ($v) {
            if (is_array($v)) return count($v) > 0;
            return $v !== null && $v !== '';
        }
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://localhost:3003/recipes/" . $recipeId);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $payload = json_encode($recipeData);
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
} else {
    echo "<script>alert('Yêu cầu không hợp lệ.'); window.history.back();</script>";
}
?>