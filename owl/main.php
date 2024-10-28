<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="main.css">
        <script src="main.js" defer></script>
    </head>
    <body>

    <h2 style="text-align: center;">이미지 업로드</h2>

    <div class="container">
        <form id="uploadForm" action="recipeResult.php" method="POST" enctype="multipart/form-data">
            <div id="dropArea">
                이곳에 이미지를 넣어주세요<br>또는<br>
                <input type="file" id="fileInput" name="image" accept="image/*">
                <a id="dropBtn" href="#" onclick="document.getElementById('fileInput').click()">이미지를 첨부해 주세요</a>
            </div>
        </form>
    </div>

    </body>
</html>
