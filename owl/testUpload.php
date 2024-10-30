<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // CURL을 사용하여 Python API 호출
        $url = 'http://localhost:5000/predict';
        $postfields = [
            'image' => new CURLFile($target_file),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);

        // JSON 결과 디코딩
        $results = json_decode($output, true);

        // 결과 출력
        echo "<h2>인식 결과:</h2>";
        if (isset($results['detected_ingredients']) && is_array($results['detected_ingredients']) && !empty($results['detected_ingredients'])) {
            echo "<ul>";
            foreach ($results['detected_ingredients'] as $ingredient) {
                echo "<li>" . htmlspecialchars($ingredient) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "결과가 없습니다.";
        }
    } else {
        echo "파일 업로드에 실패했습니다.";
    }
}
?>
