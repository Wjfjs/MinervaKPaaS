<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="main.css">
        <script src="recipeResult.js" defer></script>
    </head>
    <body>

    <?php   //db연결
        $host = '127.0.0.1';
        $username = 'root';
        $password = 'admin';
        $database = 'kpaas';
    
        $conn = mysqli_connect($host, $username, $password, $database);
    
        if ($conn->connect_error) {
            die("연결 실패: " . $conn->connect_error);
        }
        else {
            echo "db연결 완료 <br>";
        }
    ?>

    <?php
        $targetDir = "uploads/";

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
                    // 업로드된 이미지 표시
                    echo "<h3>'$fileName' 이미지가 선택되었습니다.</h3>";
                    echo "<img src='" . $targetFilePath . "' alt='Uploaded Image' style='max-width: 300px; display: block; margin-bottom: 20px;'>";
                } else {
                    echo "파일 업로드 중 오류가 발생했습니다.";
                }
            } else {
                echo "허용된 형식의 파일이 아닙니다. JPG, JPEG, PNG, GIF 파일만 업로드 가능합니다.";
            }
        } else {
            echo "이미지가 선택되지 않았습니다.";
        }

        $query = "SELECT * FROM test";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // 결과 처리
            while ($row = mysqli_fetch_assoc($result)) {
                echo "레시피: " . $row['name'] . "<br>";
            }
        } else {
            echo "쿼리 실행 실패: " . mysqli_error($conn);
        }

        mysqli_free_result($result);
        $conn->close();
    ?>

    <div>
        <a href="main.php">메인으로 이동하기</a>
    </div>

    <!-- 이미지 변경을 위한 버튼 -->
    <div style="margin-top: 20px;">
        <button onclick="document.getElementById('fileInput').click()">이미지 변경하기</button>
    </div>

    <!-- 이미지 파일 선택을 위한 숨겨진 input -->
    <form id="uploadForm" action="recipeResult.php" method="POST" enctype="multipart/form-data" style="display: none;">
        <input type="file" id="fileInput" name="image" accept="image/*" onchange="this.form.submit();">
    </form>

    </body>
</html>
