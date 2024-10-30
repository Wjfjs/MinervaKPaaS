<?php
    $targetDir = "/var/www/html/test2/uploads/";
    //$targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        if (mkdir($targetDir, 0777, true) === false) {
            $error = error_get_last();
            echo '디렉토리 생성 실패: ' . $error['message'];
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
        $file = $_FILES['image'];
        echo $file . "<br>";
        $fileName = pathinfo($file['name'], PATHINFO_FILENAME); // 파일 이름만 추출
        echo $fileName . "<br>";
        $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)); // 확장자 추출
        echo $fileType . "<br>";

        // 현재 날짜와 시간을 파일 이름에 추가 (2019-03-23-13-12-33 형식)
        $newFileName = $fileName . "_" . date("Y-m-d-H-i-s") . "." . $fileType;
        echo $newFileName . "<br>";
        $targetFilePath = $targetDir . $newFileName;
        echo $targetFilePath . "<br>";

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        // 업로드 중 오류 확인
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo "파일 업로드 중 오류가 발생했습니다. 오류 코드: " . $file['error'];
        } else if (!in_array($fileType, $allowedTypes)) {
            echo "허용된 형식의 파일이 아닙니다. JPG, JPEG, PNG, GIF 파일만 업로드 가능합니다.";
        } else {
            if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                echo "<h3>이미지 업로드 성공!</h3>";
                echo "<img src='/uploads/" . $newFileName . "' alt='Uploaded Image' style='max-width: 300px;'>";
            } else {
                $error = error_get_last(); // 마지막 오류 메시지 가져오기
                echo "파일을 저장하는 중 문제가 발생했습니다: " . $error['message'];
            }
        }
    } else {
        echo "이미지가 선택되지 않았습니다.";
    }
?>
