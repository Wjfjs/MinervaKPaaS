<?php
// DB 연결
$host = '127.0.0.1';
$username = 'root';
$password = 'admin';
$database = 'kpaas';

$conn = mysqli_connect($host, $username, $password, $database);

if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
}

$targetDir = "uploads/";
$filePath = '';

// 이미지 업로드 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $fileName = pathinfo($file['name'], PATHINFO_FILENAME);
    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // 현재 날짜와 시간을 파일 이름에 추가
    $newFileName = $fileName . "_" . date("Y-m-d-H-i-s") . "." . $fileType;
    $targetFilePath = $targetDir . $newFileName;

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($fileType, $allowedTypes)) {
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            $filePath = $targetFilePath; // 업로드된 이미지 경로 저장
        } else {
            echo "파일 업로드 중 오류가 발생했습니다.";
        }
    } else {
        echo "허용된 형식의 파일이 아닙니다. JPG, JPEG, PNG, GIF 파일만 업로드 가능합니다.";
    }
}

// 레시피 데이터 가져오기
$query = "SELECT * FROM test";
$result = mysqli_query($conn, $query);
$recipeName = ''; // 레시피 이름 초기화

if ($result) {
    if ($row = mysqli_fetch_assoc($result)) {
        $recipeName = $row['name']; // 레시피 이름 저장
    }
} else {
    echo "쿼리 실행 실패: " . mysqli_error($conn);
}

// DB 연결 해제 및 결과 해제
mysqli_free_result($result);
$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>부엉이의 레시피</title>
    <link rel="stylesheet" href="result.css">
</head>

<body>
    <div class="container">
        <!-- 헤더 -->
        <header>
            <div class="logo">
                <img src="./img/owl.png" alt="부엉이 로고">
                <h3><a href="recipe.php" style="text-decoration: none; color: black;">부엉이의 레시피</a></h3>
            </div>
            <div class="made">made by minerva</div>
        </header>

        <!-- 콘텐츠 영역 -->
        <div class="content">
            <!-- 왼쪽 섹션 -->
            <div class="left-section">
                <div class="upload-area">
                    <?php if ($filePath): ?>
                        <img src="<?php echo $filePath; ?>" alt="Uploaded Image" id="uploadImg" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <h3>사진을 삽입해주세요</h3>
                    <?php endif; ?>
                </div>
                <div class="log-text">
                    log 현재 삽입된 재료는 <span>___ / ___ / ___</span> 입니다
                </div>
                <div class="spices-list">
                    <p>많이 쓰는 조미료 list</p>
                    소금 &nbsp; 설탕 &nbsp; 후추 &nbsp; 고춧가루 &nbsp; 미원(다시다)<br>
                    간장 &nbsp; 굴소스 &nbsp; 고추장 &nbsp; 된장 &nbsp; 참(들)기름
                </div>
            </div>

            <!-- 오른쪽 섹션 -->
            <div class="right-section">
                <div class="recipe-item">
                    <img src="./img/sample-dish.jpg" alt="Recipe Image" class="recipe-image">
                    <div class="recipe-content">
                        <h3><?php echo htmlspecialchars($recipeName); ?></h3>
                        <p><strong>재료:</strong> 양파, 당근, 손질된 닭, 감자</p>
                        <p><strong>요리 방법:</strong></p>
                        <ul class="recipe-steps">
                            <li>닭을 삶는다</li>
                            <li>양념을 만든다</li>
                            <li>양념을 넣는다</li>
                            <li>끓인다</li>
                        </ul>
                    </div>
                </div>

                <!-- 이미지 변경을 위한 버튼 -->
                <div style="margin-top: 20px;">
                    <button onclick="document.getElementById('fileInput').click()">이미지 변경하기</button>
                </div>

                <!-- 이미지 파일 선택을 위한 숨겨진 input -->
                <form id="uploadForm" action="recipe_result.php" method="POST" enctype="multipart/form-data" style="display: none;">
                    <input type="file" id="fileInput" name="image" accept="image/*" onchange="this.form.submit();">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
