<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $userId = $_POST['userId'] ?? null;
    $name = $_POST['name'] ?? '';
    $age = isset($_POST['age']) ? (int)$_POST['age'] : null;
    $gender = isset($_POST['gender']) ? (bool)$_POST['gender'] : null; // true: male, false: female
    $weight = isset($_POST['weight']) ? (float)$_POST['weight'] : null; // kg
    $weightGoal = isset($_POST['weightGoal']) ? (float)$_POST['weightGoal'] : null; // kg
    $height = isset($_POST['height']) ? (float)$_POST['height'] : null; // cm
    $levelExercise = isset($_POST['levelExercise']) ? (float)$_POST['levelExercise'] : null; // activity multiplier

    if (!$userId || !$age || !$weight || !$height || !$levelExercise || $weightGoal === null) {
        echo "<script>alert('Thiếu thông tin để tính toán hoặc cập nhật.'); window.history.back();</script>";
        exit;
    }

    if ($weightGoal <= 0 || $weight <= 0 || $height <= 0 || $age <= 0 || $levelExercise <= 0) {
        echo "<script>alert('Thông tin không hợp lệ. Vui lòng kiểm tra lại.'); window.history.back();</script>";
        exit;
    }

    // Tính BMR (Basal Metabolic Rate) dựa trên công thức Harris-Benedict
    if ($gender) {
        // Male
        $bmr = 10 * $weight + 6.25 * $height - 5 * $age + 5;
    } else {
        // Female
        $bmr = 10 * $weight + 6.25 * $height - 5 * $age - 161;
    }

    // TDEE (Total Daily Energy Expenditure) = BMR * levelExercise
    $dailyCaloriesGoal = $bmr * $levelExercise;

    if ($dailyCaloriesGoal <= 0) {
        echo "<script>alert('Lượng calo tính toán không hợp lệ. Vui lòng kiểm tra lại thông tin.'); window.history.back();</script>";
        exit;
    }

    $caloriesForWeightGoal = null;
    if ($weightGoal < $weight) {
        // Giảm cân
        $caloriesForWeightGoal = $dailyCaloriesGoal - 500; // Giảm 500 calo mỗi ngày
        if ($caloriesForWeightGoal <= 1200) {
            echo "<script>alert('Lượng calo mục tiêu quá thấp. Vui lòng kiểm tra lại thông tin.'); window.history.back();</script>";
            exit;
        }
    } elseif ($weightGoal > $weight) {
        // Tăng cân
        $caloriesForWeightGoal = $dailyCaloriesGoal + 500; // Thêm 500 calo mỗi ngày
    } else {
        // Duy trì cân nặng
        $caloriesForWeightGoal = $dailyCaloriesGoal;
    }

    $userData = [
        'userId' => $userId,
        'name' => $name,
        'age' => $age,
        'gender' => $gender,
        'weight' => $weight,
        'height' => $height,
        'weightGoal' => $weightGoal,
        'dailyCaloriesGoal' => round($caloriesForWeightGoal, 2), // Làm tròn đến 2 chữ số thập phân
        'levelExercise' => $levelExercise
    ];

    $payload = json_encode($userData);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:3003/users/admin/" . $id);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
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

    if ($httpCode === 200 || $httpCode === 204) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='/testphp/admin/index.php?action=users';</script>";
        exit;
    } else {
        echo "<script>alert('Cập nhật thất bại. Mã HTTP: $httpCode'); window.history.back();</script>";
        exit;
    }
} else {
    echo "<script>alert('Yêu cầu không hợp lệ.'); window.history.back();</script>";
}