<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="UTF-8">
        <title>이미지 업로드</title>
        <link rel="stylesheet" href="main.css">
        <script src="main.js" defer></script> <!-- JavaScript 파일 링크 -->
    </head>
    <body>

    <h2 style="text-align: center;">이미지 업로드</h2>

    <div class="container">
        <div id="dropArea">
            이곳에 이미지를 넣어주세요<br>또는<br>
            <input type="file" id="fileInput" accept="image/*">
            <div>
                <a id="dropBtn" href="#" onclick="document.getElementById('fileInput').click()">이미지를 첨부해 주세요</a>
            </div>
        </div>
    </div>

    </body>
</html>
