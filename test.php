<?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://localhost:3003/meals/user/details/3");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// Kiểm tra nếu dữ liệu hợp lệ
if (!isset($data['data'])) {
    die('Không có dữ liệu trả về từ API');
}

$meals = $data['data'];

echo "<h2>Danh sách bữa ăn</h2>";
foreach ($meals as $meal) {
    $mealTime = $meal['mealTime'];
    $mealItem = $meal['mealItem'];
    $mealName = $mealItem['meal']['name'];
    $recipe = $mealItem['recipe'];
    $recipeName = $recipe['name'];
    $calories = $recipe['CaloriesPerServing'];
    $image = $recipe['imageUrl'];

    echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:10px'>";
    echo "<strong>Bữa ăn:</strong> $mealName<br>";
    echo "<strong>Thời gian:</strong> $mealTime<br>";
    echo "<strong>Món ăn:</strong> $recipeName<br>";
    echo "<strong>Calories / khẩu phần:</strong> $calories<br>";
    echo "<img src='$image' alt='Ảnh món ăn' style='max-width:150px'><br>";
    echo "</div>";
}
?>
