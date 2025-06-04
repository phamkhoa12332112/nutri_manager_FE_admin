<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra tên và mô tả
    if (empty($_POST['name'])) {
        echo "<script>alert('Tên món ăn không được để trống!'); window.history.back();</script>";
        exit;
    }
    if (empty($_POST['description'])) {
        echo "<script>alert('Mô tả món ăn không được để trống!'); window.history.back();</script>";
        exit;
    }

    // Kiểm tra hình ảnh
    if (!isset($_FILES['imageUrl']) || $_FILES['imageUrl']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Hình ảnh không được để trống hoặc bị lỗi!'); window.history.back();</script>";
        exit;
    }

    // Kiểm tra nguyên liệu
    if (empty($_POST['ingredients']) || !is_array($_POST['ingredients'])) {
        echo "<script>alert('Phải chọn ít nhất một nguyên liệu!'); window.history.back();</script>";
        exit;
    }
    // Kiểm tra từng nguyên liệu có id và quantity
    foreach ($_POST['ingredients'] as $ingredient) {
        if (empty($ingredient['id']) || $ingredient['quantity'] === '' || $ingredient['quantity'] <= 0) {
            echo "<script>alert('Vui lòng nhập đầy đủ nguyên liệu và số lượng hợp lệ!'); window.history.back();</script>";
            exit;
        }
    }

    // Kiểm tra bữa ăn
    if (empty($_POST['meals']) || !is_array($_POST['meals'])) {
        echo "<script>alert('Phải chọn ít nhất một bữa ăn!'); window.history.back();</script>";
        exit;
    }
    foreach ($_POST['meals'] as $meal) {
        if (empty($meal['id'])) {
            echo "<script>alert('Vui lòng chọn bữa ăn hợp lệ!'); window.history.back();</script>";
            exit;
        }
    }

    // Tính tổng calo từ nguyên liệu
    $totalCalories = 0;
    foreach ($_POST['ingredients'] as $ingredient) {
        $ingredientId = $ingredient['id']; // Lấy ID nguyên liệu
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost:3003/ingredients/$ingredientId");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "<script>alert('Lỗi cURL: " . curl_error($ch) . "'); window.history.back();</script>";
            curl_close($ch);
            exit;
        }

        $data = json_decode($response, true);

        $ingredientData = $data['data'];
        $quantity = $ingredient['quantity'] ?? 1; // Lấy số lượng nguyên liệu
        $calories = $ingredientData['calories']; 
        $totalCalories += $calories * $quantity;
    }

    if (isset($_FILES['imageUrl']) && $_FILES['imageUrl']['error'] === UPLOAD_ERR_OK) {
        $filePath = $_FILES['imageUrl']['tmp_name'];
        $fileName = $_FILES['imageUrl']['name'];
        $fileType = $_FILES['imageUrl']['type'];

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

        // Xử lý phản hồi từ API
        if ($response) {
            $responseData = json_decode($response, true);
            if (isset($responseData['data']) && $responseData['statusCode'] === 200) {
                
                // Chuẩn bị dữ liệu gửi qua API
                $apiData = [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'totalCalories' => $totalCalories,
                    // Giả sử bạn có URL sau khi upload ảnh
                    'imageUrl' => $responseData['data']['url'] ?? '',
                    'ingredients' => array_map(function ($ingredient) {
                        return [
                            'id' => (int)$ingredient['id'],
                            'quantity' => (float)($ingredient['quantity'] ?? 1),
                        ];
                    }, $_POST['ingredients']),
                    'meals' => array_map(function ($meal) {
                        return [
                            'id' => (int)$meal['id'],
                        ];
                    }, $_POST['meals']),
                ];

                // Gửi dữ liệu qua API
                $ch = curl_init('http://localhost:3003/recipes');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiData));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Accept: application/json',
                ]);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                echo "HTTP code: " . $httpCode . "<br>";
                echo "Response: " . $response . "<br>";

                if (curl_errno($ch)) {
                    echo "<script>alert('Lỗi cURL: " . curl_error($ch) . "'); window.history.back();</script>";
                    curl_close($ch);
                    exit;
                }

                curl_close($ch);

                // Xử lý phản hồi từ API
                if ($response) {
                    $data = json_decode($response, true);
                    if (isset($data['data']) && $data['statusCode'] === 200) {
                        echo "<script>alert('Thêm món ăn thành công!'); window.location.href='/testphp/admin/index.php?action=recipes';</script>";
                        exit;
                    } else {
                        echo "<script>alert('Lỗi khi thêm món ăn: " . ($data['msg'] ?? 'Không rõ lỗi') . "'); window.history.back();</script>";
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