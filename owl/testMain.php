<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>이미지 인식</title>
</head>
<body>
    <h1>이미지 인식 시스템</h1>
    <form action="testUpload.php" method="post" enctype="multipart/form-data">
        <label for="image">이미지 업로드:</label>
        <input type="file" name="image" id="image" required>
        <input type="submit" value="업로드">
    </form>
</body>
</html>
