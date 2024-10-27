<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <h2 style="text-align: center;">이미지 업로드</h2>
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }
        #dropArea {
            width: 100%;
            max-width: 400px;
            padding: 50px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            text-align: center;
            font-size: 18px;
            color: #999;
        }
        #dropArea img {
            max-width: 100%;
            margin-top: 20px;
        }
        #fileInput {
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div id="dropArea">
        파일을 이곳에 드래그 앤 드롭 하세요<br>
        <input type="file" id="fileInput" accept="image/*">
    </div>
    <div id="dropBut">
        <button onclick="document.getElementById('fileInput').click()">이미지 선택</button>
    </div>
</div>

<script>
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');

    fileInput.addEventListener('change', () => {
        const files = fileInput.files;
        if (files.length > 0) {
            uploadImage(files[0]);
        }
    });

    dropArea.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropArea.style.borderColor = 'blue';
    });

    dropArea.addEventListener('dragleave', () => {
        dropArea.style.borderColor = '#ccc';
    });

    dropArea.addEventListener('drop', (event) => {
        event.preventDefault();
        dropArea.style.borderColor = '#ccc';
        const files = event.dataTransfer.files;

        if (files.length > 0) {
            uploadImage(files[0]);
        }
    });

    function uploadImage(file) {
        const formData = new FormData();
        formData.append('image', file);

        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            dropArea.innerHTML = data;
        })
        .catch(error => {
            console.error('오류:', error);
            dropArea.innerHTML = '이미지 업로드에 실패했습니다.';
        });
    }
</script>

</body>
</html>
