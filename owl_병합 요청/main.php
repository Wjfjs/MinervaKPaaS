<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="main.css">
        <script src="main.js" defer></script>
    </head>
    <body>

		<div id="base">
			<div id="main">
				<div id="drop-zone" ondrop="drop_image(event)" ondragover="drag_image(event)" ondragleave="outdrag_image(event)" >
					이미지 드롭하기
				</div>
				<img id="preview" ondrop="drop_image(event)" ondragover="drag_image(event)" ondragleave="outdrag_image(event)" alt="Image Preview" style="display:none;">
			</div>
			<div id="footer">
				<div id="menu">
					<div class="select">
						<label>
							<input onchange="select_image(event)" type="file" accept="image/*" style="display: none;">
							<div class="button">이미지 선택</div>
						</label>
					</div>
					<div class="create">
						<label>
							<input onchange="select_image(event)" type="file" accept="image/*" capture="camera" style="display: none;">
							<div class="button">사진 촬영</div>
						</label>
					</div>
				</div>
				<form id="form" action="upload.php" method="POST" enctype="multipart/form-data">
					<div class="upload">
						<div class="button disabled" onclick="image_submit(event)">레시피 추천받기</div>
						<input type="file" id="fileInput" name="image" style="display: none;">
					</div>
				</form>
			</div>
		</div>

    </body>
</html>
