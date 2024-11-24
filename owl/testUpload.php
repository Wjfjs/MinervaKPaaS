<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Python 스크립트 실행
        $command = escapeshellcmd("python3 ./test.py " . escapeshellarg($target_file) . " 2>&1");
        $output = shell_exec($command); // 표준 출력 및 오류 출력 모두 캡처

        // 디버깅: Python 출력 확인
        echo "<pre>$output</pre>"; // Python 출력 결과 표시

        // 결과 파일 읽기
        $result_file = "results/output.json";
        if (file_exists($result_file)) {
            $results = json_decode(file_get_contents($result_file), true);

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
            echo "결과 파일이 존재하지 않습니다.";
        }
    } else {
        echo "파일 업로드에 실패했습니다.";
    }
}
?>
