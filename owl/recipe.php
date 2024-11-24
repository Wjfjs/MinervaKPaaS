<?php
	exec("python test.py");
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>부엉이의 레시피</title>
    <link rel="stylesheet" href="recipe.css">
    <script src="recipe.js" defer></script>
</head>
<body>
    <div class="container">
        <header>
			<div class="logo-left">
			</div>
            <div class="logo">
                <img src="./img/owl.png" alt="부엉이 로고">
                <h3>부엉이의 레시피</h3>
            </div>
			<div class="logo-right">
				<div class="made">made by minerva</div>
			</div>
        </header>

        <div class="content">
            <!-- Left Section -->
            <div class="left-section">
                <form id="uploadForm" action="recipe_result.php" method="POST" enctype="multipart/form-data">
                    <div id="dropArea" class="upload-area">
                        <p>이미지를 드래그하거나 버튼을 클릭하여 업로드하세요.</p>
						<div class="upload-area-buttons">
							<label>
								<input type="file" id="fileInput" name="image" style="display: none;">
								<div class="button">이미지를 첨부하기</div>
							</label>
							<label>
								<input type="file" id="fileInputCamera" style="display: none;">
								<div class="button">카메라로 촬영하기(모바일)</div>
							</label>
							<div id="text-eof" class="button" style="padding: 5px 100px; display: none;" onclick="
								main = document.querySelector('.upload-area-buttons');
								eof = document.getElementById('text-eof');
								input = document.createElement('input');
								input.type = 'text';
								input.name = 'text[]';
								main.insertBefore(input, eof);
								input.focus();
							">+</div>
							<div id="text-submit" class="button" style="padding: 7px 20px; display: none;" onclick="
								const uploadForm = document.getElementById('uploadForm');
								uploadForm.submit();
							">이 재료로 추천받기</div>
							<div id="text-start" class="button" style="background-color: red;" onclick="
								main = document.querySelector('.upload-area-buttons');
								labels = main.querySelectorAll('label');
								eof = document.getElementById('text-eof');
								submit = document.getElementById('text-submit');
								start = document.getElementById('text-start');
								labels[0].style.display = 'none';
								labels[1].style.display = 'none';
								eof.style.display = '';
								submit.style.display = '';
								start.style.display = 'none';
								input = document.createElement('input');
								input.type = 'text';
								input.name = 'text[]';
								main.insertBefore(input, eof);
								input.focus();
							">재료 입력하기</div>
							
						</div>
                    </div>
                </form>
                <div class="log-text">
                    <p><span>log </span> 현재 삽입된 재료는 없습니다.</p>
                </div>
                <div class="spices-list">
                    많이 쓰는 조미료 list<br>
                    <span>소금</span> <span>설탕</span> <span>후추</span> <span>고춧가루</span> <br>
                    <span>미원(다시다)</span> <span>간장</span> <span>굴소스</span> <span>고추장</span> <br>
                    <span>된장</span> <span>참(들)기름</span>
                </div>
            </div>

            <!-- Right Section -->
            <div class="right-section">
                <div class="recipe-instructions">
                    <p>부엉이의 레시피 안내문</p>
                    <ol>
                        <li>저희는 음식 재료가 담겨있는 사진을 넣어주시면 레시피를 알려드려요!</li>
                        <li>음식 카테고리: 한식!</li>
                        <li>밑에 있는 log는 사진을 넣으시면 사진에 담겨있는 음식의 이름을 알려드릴거에요!</li>
                        <li>저희는 재료에 관련된 레시피를 알려드리기에, 조미료 유무에 따른 레시피는 알려드리지 못해요 ㅠㅠ</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</body>
</html>