<?php
$targetDir = "uploads/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true); // 폴더가 없으면 생성
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $fileName = basename($file['name']);
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            echo "<h3>이미지 업로드 성공!</h3>";
            echo "<img src='" . $targetFilePath . "' alt='Uploaded Image' style='max-width: 300px;'>";
        } else {
            echo "파일 업로드 중 오류가 발생했습니다.";
        }
    } else {
        echo "허용된 형식의 파일이 아닙니다. JPG, JPEG, PNG, GIF 파일만 업로드 가능합니다.";
    }
} else {
    echo "이미지가 선택되지 않았습니다.";
}
?>
