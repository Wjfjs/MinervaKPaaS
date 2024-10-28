
const uploadPageUrl = "/upload.html";
const checkPageUrl = "/check.html";
const recipePageUrl = "/recipe.html";


let image = null;
let image_url = null;
let current_items = null;
let recipe_data = null;




const image_submit = function(event){
	if(!image){
		throw new Error("이미지 없습니다.");
	}
	if(!image.type.startsWith('image/')){
		throw new Error("이미지가 아닙니다.")
	}
	//감지된 재료 대기
	setTimeout(function(){
		current_items = ["파스타","당근","감자","마늘","양파","크림","닭가슴살"];
		loadPage("."+checkPageUrl);
	}, 1000);
}

const change_image = function(file){
	if(!file || !file.type.startsWith('image/')){
		return false;
	}
	image = file;
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
	reader.readAsDataURL(image);
	return true;
}

const select_image = function(event){
	const file = event.target.files[0];
	if (file){
		change_image(file);
	}
}

const drop_image = function(event){
	event.preventDefault();
	event.target.classList.remove('dragover');
	
	const file = event.dataTransfer.files[0];
	if (file){
		change_image(file);
	}
}

const drag_image = function(event){
	event.preventDefault()
	event.target.classList.add('dragover');
}

const outdrag_image = function(event){
	event.target.classList.remove('dragover');
}






















const add_item = function(current_items){
	const item_tags = current_items;
	
	const list = document.getElementById("list");
	for(const item_tag of item_tags){
		const item = document.createElement("div");
		item.className = "item";
		item.innerText = item_tag;
		list.appendChild(item);
	}
}

const set_preview = function(url){
	const preview = document.getElementById("preview");
	preview.src = url;
}

const cancel_image = function(event){
	loadPage("."+uploadPageUrl);
}

const confirm_image = function(event){
	if(!image){
		throw new Error("이미지 없습니다.");
	}
	if(!image.type.startsWith('image/')){
		throw new Error("이미지가 아닙니다.")
	}
	
	//레시피 추천 대기
	setTimeout(function(){
		recipe_data = {
			name : "당근 파스타",
			img : "",
			item_tags : ["파스타", "당근", "마늘 ", "양파"],
			optional_tags : ["파르메산 치즈", "허브", "닭가슴살", "크림", "레몬즙"]
		};
		loadPage("."+recipePageUrl);
	}, 1000);
}


















const add_recipe = function(recipe_data){
	const img = recipe_data.img;
	const name = recipe_data.name;
	const item_tags = recipe_data.item_tags;
	const optional_tags = recipe_data.optional_tags;
	
	const recipe_list = document.getElementById("recipe-list");
	const item = document.createElement("div");
	item.className = "item";
	recipe_list.appendChild(item);
	
	const item_image = document.createElement("img");
	item_image.className = "item-image";
	item_image.src = img;
	item_image.alt = "레시피 이미지";
	item.appendChild(item_image);
	const item_content = document.createElement("div");
	item_content.className = "item-content";
	item.appendChild(item_content);
	
	const item_title = document.createElement("h2");
	item_title.className = "item-title";
	item_title.innerText = name;
	item_content.appendChild(item_title);
	const tag_list = document.createElement("div");
	tag_list.className = "tag-list";
	item_content.appendChild(tag_list);
	
	for(const item_tag of item_tags){
		const item = document.createElement("div");
		item.className = "item";
		item.innerText = item_tag;
		tag_list.appendChild(item);
	}
	for(const optional_tag of optional_tags){
		const optional = document.createElement("div");
		optional.className = "optional";
		optional.innerText = optional_tag;
		tag_list.appendChild(optional);
	}
}






const loadPage = function(url) {
	const xhr = new XMLHttpRequest();
	
	xhr.open("GET", url);
	xhr.onload = function() {
		const parser = new DOMParser();
		const doc = parser.parseFromString(xhr.responseText, "text/html");
		history.pushState({ html: doc.body.innerHTML }, "", url);
		document.body.innerHTML = doc.body.innerHTML;
		updateScript();
	};

	xhr.send();
}


const updateScript = function(){
	const path = location.pathname;
	
	if(path.endsWith(uploadPageUrl)){
		change_image(image);
	}else if(path.endsWith(checkPageUrl)){
		set_preview(image_url);
		add_item(current_items);
	}else if(path.endsWith(recipePageUrl)){
		add_recipe(recipe_data);
	}
}


window.onpopstate = function(event){
  if (event.state) {
    document.body.innerHTML = event.state.html;
	updateScript();
  }
};
window.onpushstate = function(event){
  if (event.state) {
    document.body.innerHTML = event.state.html;
	updateScript();
  }
};
window.onload = function(){
	const path = location.pathname;
	history.replaceState({ html: document.body.innerHTML }, "", path);
	updateScript();
}
