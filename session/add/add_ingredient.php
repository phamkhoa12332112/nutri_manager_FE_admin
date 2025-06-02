<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 // Kiểm tra dữ liệu đầu vào
     if (empty($_POST['name'])) {
    echo "<script>alert('Tên nguyên liệu không được để trống!'); window.history.back();</script>";
    exit;
    }

    if (empty($_POST['unit'])) {
    echo "<script>alert('Đơn vị không được để trống!'); window.history.back();</script>";
    exit;
    }

    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] === UPLOAD_ERR_OK) {
        $filePath = $_FILES['imageFile']['tmp_name'];
        $fileName = $_FILES['imageFile']['name'];
        $fileType = $_FILES['imageFile']['type'];

        // Tạo dữ liệu form-data
        $data = [
            'file' => new CURLFile($filePath, $fileType, $fileName),
        ];

        // Gửi yêu cầu đến API NestJS
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

        $calories = ($_POST['protein'] * 4) + ($_POST['carbs'] * 4) + ($_POST['fat'] * 9);


        // Xử lý phản hồi từ API
        if ($response) {
            $responseData = json_decode($response, true);
            
            if (isset($responseData['data']) && $responseData['statusCode'] === 200) {
                $ingredientData = [
                    'name' => trim($_POST['name'] ?? ''),
                    'calories' => (float)$calories,
                    'category' => trim($_POST['category'] ?? ''),
                    'protein' => (float)($_POST['protein'] ?? null),
                    'fat' => (float)($_POST['fat'] ?? null),
                    'carbs' => (float)($_POST['carbs'] ?? null),
                    'fiber' => (float)($_POST['fiber'] ?? null),
                    'unit' => trim($_POST['unit'] ?? ''),
                    'imageUrl' => $responseData['data']['url'] ?? '',
                ];
            
                // Gửi dữ liệu qua API
                $ch = curl_init('http://localhost:3003/ingredients');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ingredientData));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            
                $response = curl_exec($ch);
            
                if (curl_errno($ch)) {
                    echo "<script>alert('Lỗi cURL: " . curl_error($ch) . "'); window.history.back();</script>";
                    curl_close($ch);
                    exit;
                }
            
                curl_close($ch);
            
                if ($response) {
                    $data = json_decode($response, true);
                    if (isset($data['data']) && $data['statusCode'] === 200) {
                        echo "<script>alert('Thêm nguyên liệu thành công!'); window.location.href='/testphp/admin/index.php?action=ingredients';</script>";
                        exit;
                    } else {
                        echo "<script>alert('Lỗi khi thêm nguyên liệu: " . ($data['msg'] ?? 'Không rõ lỗi') . "'); window.history.back();</script>";
                        exit;
                    }
                } else {
                    echo "<script>alert('Không nhận được phản hồi từ máy chủ.'); window.history.back();</script>";
                    exit;
                }


            } else {
                echo "<script>alert('Lỗi khi upload: " . ($responseData['message'] ?? 'Không rõ lỗi') . "'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Không nhận được phản hồi từ máy chủ.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('File không hợp lệ hoặc không được chọn!'); window.history.back();</script>";
    }

    
}
?>