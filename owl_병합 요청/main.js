


const image_submit = function(event){
	if(!image || !image[0]){
		throw new Error("이미지 없습니다.");
	}
	if(!image[0].type.startsWith('image/')){
		throw new Error("이미지가 아닙니다.")
	}
	//감지된 재료 대기
	
    const formData = document.getElementById("form");
    formData.image.files = image;
	formData.submit();
}



const change_image = function(files){
	if(!files || !files[0] || !files[0].type.startsWith('image/')){
		return false;
	}
	image = files;
	image_url = null;
	current_items = null;
	recipe_data = null;
	const button = document.querySelector("#form>.upload>.button");
	button.classList.remove("disabled");
	const dropZone = document.querySelector("#drop-zone");
	dropZone.style.display = "none";
	const reader = new FileReader();
	reader.onload = function(e) {
		image_url = e.target.result;
		const preview = document.getElementById("preview");
		preview.src = image_url;
		preview.style.display = 'block';
	};
	reader.readAsDataURL(image[0]);
	return true;
}

const select_image = function(event){
	const files = event.target.files;
	if (files){
		change_image(files);
	}
}

const drop_image = function(event){
	event.preventDefault();
	event.target.classList.remove('dragover');
	
	const files = event.dataTransfer.files;
	if (files){
		change_image(files);
	}
}

const drag_image = function(event){
	event.preventDefault()
	event.target.classList.add('dragover');
}

const outdrag_image = function(event){
	event.target.classList.remove('dragover');
}

