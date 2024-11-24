const dropArea = document.getElementById('dropArea');
const fileInput = document.getElementById('fileInput');
const fileInputCamera = document.getElementById('fileInputCamera');
const uploadForm = document.getElementById('uploadForm');

fileInput.addEventListener('change', () => {
    if (fileInput.files.length > 0) {
        uploadForm.submit();
    }
});

fileInputCamera.addEventListener('change', () => {
	const files = fileInputCamera.files;
	
    if (files.length > 0) {
		fileInput.files = files;
        uploadForm.submit();
    }
});

dropArea.addEventListener('dragover', (event) => {
    event.preventDefault();
    dropArea.style.borderColor = 'blue';
});

dropArea.addEventListener('dragleave', () => {
    dropArea.style.borderColor = '#ccc';
});

dropArea.addEventListener('drop', (event) => {  //이미지 첨부
    event.preventDefault();
    dropArea.style.borderColor = '#ccc';
    const files = event.dataTransfer.files;

    if (files.length > 0) {
        fileInput.files = files;
        uploadForm.submit();
    }
});