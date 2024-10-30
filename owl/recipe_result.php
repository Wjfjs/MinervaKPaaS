<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>부엉이의 레시피</title>
    <link rel="stylesheet" href="result.css">
    <script src="recipe_result.js" defer></script>
</head>

<body>
    <div class="container">
        <!-- 헤더 -->
        <header>
			<div class="logo-left">
			</div>
            <div class="logo">
                <img src="./img/owl.png" alt="부엉이 로고">
                <h3><a href="recipe.php" style="text-decoration: none; color: black;">부엉이의 레시피</a></h3>
            </div>
			<div class="logo-right">
				<div class="made">made by minerva</div>
			</div>
        </header>

        <!-- 콘텐츠 영역 -->
        <div class="content">
            <!-- 왼쪽 섹션 -->
            <?php
                if (isset($_POST['text'])){
                    $text_list = $_POST['text'];
                }else{
                    $text_list = ["재료가 입력되지 않았습니다."];
                }

                $text_count = count($text_list);
                $question = implode('|', array_map('trim', $text_list));

                //$targetDir = "/var/www/html/uploads/";
                $targetDir = "uploads/";
                //$webDir = "/uploads/";
                $webDir = "uploads/";
                $filePath = '';

                // 이미지 업로드 처리
                if (empty($text_list) || $text_list[0] == "재료가 입력되지 않았습니다.") {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
                        if (isset($_FILES['image'])) {
                            $file = $_FILES['image'];
                            $fileName = pathinfo($file['name'], PATHINFO_FILENAME);
                            $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                            // 현재 날짜와 시간을 파일 이름에 추가
                            $newFileName = $fileName . "_" . date("Y-m-d-H-i-s") . "." . $fileType;
                            $targetFilePath = $targetDir . $newFileName;
                            $webFilePath = $webDir . $newFileName; // 웹 경로
                
                            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                            if (in_array($fileType, $allowedTypes)) {
                                if (!is_dir($targetDir)) {
                                    mkdir($targetDir, 0777, true);
                                }
                
                                if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                                    $filePath = $webFilePath; // 업로드된 이미지 경로 저장
                
                                    // CURL을 사용하여 Python API 호출
                                    $url = 'http://localhost:5000/predict';
                                    $postfields = [
                                        'image' => new CURLFile($targetFilePath),
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
                
                                    // 식재료 목록 저장
                                    if (isset($results['detected_ingredients']) && is_array($results['detected_ingredients']) && !empty($results['detected_ingredients'])) {
                                        foreach ($results['detected_ingredients'] as $ingredient) {
                                            $detectedIngredients[] = htmlspecialchars($ingredient);
                                        }
                                    }
                                } else {
                                    echo "파일 업로드 중 오류가 발생했습니다.";
                                }
                            } else {
                                echo "허용된 형식의 파일이 아닙니다. JPG, JPEG, PNG, GIF 파일만 업로드 가능합니다.";
                            }
                        }
                    }
                }
            ?>

            <div class="left-section">
                <div id="dropArea" class="upload-area">
                    <?php
                        if (isset($_POST['text'])) { $text_list = $_POST['text']; }
                        else{ $text_list = ["재료가 입력되지 않았습니다."]; }
                    ?>
                    <?php if ($filePath): ?>
                        <img src="<?php echo $filePath; ?>" alt="Uploaded Image" id="uploadImg" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php elseif (!empty($text_list)):?>
                        <h3>재료 입력하기를 선택하셨습니다.</h3>
                    <?php else: ?>
                        <h3>사진을 삽입해주세요</h3>
                    <?php endif; ?>
                </div>

                <!-- 이미지 변경을 위한 버튼 -->
                <form id="uploadForm" action="recipe_result.php" method="POST" enctype="multipart/form-data">
                    <div class="upload-change-buttons">
                        <!-- 이미지 변경 버튼 -->
                        <label>
                            <input type="file" id="fileInput" name="image" style="display: none;">
                            <div class="button">이미지 변경하기</div>
                        </label>

                        <!-- 재료 재입력하기 버튼 -->
                        <input type="button" onclick="location.href='recipe.php'" value="재료 재입력하기" class="button">

                        <!-- 다시 촬영하기 버튼 -->
                        <label>
                            <input type="file" id="fileInputCamera" style="display: none;">
                            <div class="button">다시 촬영하기(모바일)</div>
                        </label>
                    </div>
                </form>

                <div class="log-text">
                    log 현재 삽입된 재료는 <span><?= implode(" / ", $text_list) ?></span> 입니다
                </div>

                <!-- 이미지학습 결과 -->
                <?php if (!empty($detectedIngredients)): ?>
                    <div class="log-text">
                        <h2>인식된 재료</h2>
                        <?php foreach ($detectedIngredients as $ingredient): ?>
                            <span><?php echo $ingredient; ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="log-text">
                        <h2>인식된 재료가 없습니다.</h2>
                    </div>
                <?php endif; ?>

                <?php
                    // DB 연결
                    $host = '211.188.54.226';
                    $username = 'owl';
                    $password = 'minerva';
                    $database = 'contest95';
                    $port = '13306';

                    $conn = mysqli_connect($host, $username, $password, $database, $port);

                    if ($conn->connect_error) {
                        die("연결 실패: " . $conn->connect_error);
                    }

                    // 재료 탐색
                    if ($text_count == 1) {
                        $query = "SELECT id FROM ingredient WHERE name LIKE '%$question%'";
                    } else {
                        $query = "SELECT id FROM ingredient WHERE name REGEXP '$question'";
                    }
                    
                    $result = mysqli_query($conn, $query);
                    $ingredient_id = [];

                    while ($row = mysqli_fetch_assoc($result)) {
                        $ingredient_id[] = $row['id'];
                    }
                    // 재료를 토대로 total 검색 (레시피)
                    $recipe = [];
                    $total_count = [];
                    if (!empty($ingredient_id)) {
                        $ingredient_ids = implode(',', $ingredient_id);

                        $query = "SELECT re_id, COUNT(in_id) AS cot FROM total WHERE re_id IN (SELECT re_id FROM total WHERE in_id IN ($ingredient_ids)) GROUP BY re_id";
                        $result = mysqli_query($conn, $query);

                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $recipe[$i] = $row['re_id'];
                            $total_count[$i] = $row['cot'];
                            $i++;
                        }
                    }
                ?>

                <div class="spices-list">
                    많이 쓰는 조미료 list<br><br>
                    <?php

                    ?>
                    <span>소금</span> <span>설탕</span> <span>후추</span> <span>고춧가루</span> <br>
                    <span>미원(다시다)</span> <span>간장</span> <span>굴소스</span> <span>고추장</span> <br>
                    <span>된장</span> <span>참(들)기름</span>
                </div>
            </div>

            <!-- 오른쪽 섹션 -->
            <div class="right-section">
                <div class="recipe-item">
                    <div class="recipe-content">
                        <?php
                            //레시피 검색
                            $recipes = implode(',', $recipe);
                            if (count($recipe) == 1) {
                                $query = "SELECT * FROM recipe WHERE id = $recipe";
                            } else {
                                $query = "SELECT * FROM recipe WHERE id IN ($recipes)";
                            }
                            $result = mysqli_query($conn, $query);

                            $i = 0;
                            $recipe_id = [];
                            $recipe_name = [];
                            $recipe_description = [];

                            while ($row = mysqli_fetch_assoc($result)) {
                                $recipe_id[$i] = $row['id'];
                                $recipe_name[$i] = $row['name'];
                                $recipe_description[$i] = $row['description'];
                                $i++;
                            }
                    
                        $temp = 0;
                        while($temp < count($recipe_name)) { ?>
                        <h3><?php echo $recipe_name[$temp] ?></h3>
                        <p>
                            <strong>재료:</strong> 
                            <?php 
                                $new_query = "SELECT name FROM ingredient WHERE id IN (SELECT in_id FROM total WHERE re_id = $recipe_id[$temp]);";
                                $new_result = mysqli_query($conn, $new_query);
                                
                                $g = 0;
                                $ingredient_name = [];

                                while ($row = mysqli_fetch_assoc($new_result)) {
                                    $ingredient_name[$g] = $row['name'];
                                    echo $ingredient_name[$g];
                                    $g++;
                                }
                            ?>
                        </p>
                        <p><strong>요리 방법:</strong></p>
                        <ul class="recipe-steps">
                            <?php
                                $line = explode("[", $recipe_description[$temp]);

                                $l = 0;
                                if (isset($line[0])) {
                                    $lines = $line[0];

                                    $lines = preg_replace('/^\d+\]/', '', $lines);
                                    $lines = trim($lines);

                                    echo htmlspecialchars($lines);

                                    $l++;
                                }

                                while ($l < count($line)) {
                                    $lines = $line[$l];

                                    $lines = preg_replace('/^\d+\]/', '', $lines);
                                    $lines = trim($lines);

                                    echo "<li>" . htmlspecialchars($lines). "</li>";
                                    $l++;
                                }
                                ?> <br><br> <?php
                                $temp++;
                            ?>
                        </ul>
                    <?php }
                        // DB 연결 해제 및 결과 해제
                        mysqli_free_result($result);
                        mysqli_close( $conn);
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>