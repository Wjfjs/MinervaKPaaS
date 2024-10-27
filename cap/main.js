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
