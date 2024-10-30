from flask import Flask, request, jsonify
from PIL import Image
from ultralytics import YOLO
import os

app = Flask(__name__)

# 업로드 디렉토리 설정
UPLOAD_FOLDER = 'uploads'
os.makedirs(UPLOAD_FOLDER, exist_ok=True)  # 업로드 폴더가 없으면 생성

# 모델 불러오기
model = YOLO('best.pt')  # 최적 모델 파일 불러오기

@app.route('/predict', methods=['POST'])
def predict():
    # 요청에서 이미지 파일 추출
    if 'image' not in request.files:
        return jsonify({'error': 'No image file provided'}), 400

    file = request.files['image']

    # 파일 이름 안전하게 처리
    file_path = os.path.join(UPLOAD_FOLDER, file.filename)
    file.save(file_path)

    try:
        # 예측 수행
        results = model.predict(source=[file_path])

        # 예측된 결과에서 식재료 이름을 추출하여 배열로 저장
        detected_ingredients = []
        for result in results:
            if result.boxes is not None:  # 예측 결과가 있을 때
                for box in result.boxes:
                    ingredient = model.names[int(box.cls)]  # 클래스 ID를 통해 식재료 이름 추출
                    detected_ingredients.append(ingredient)

        return jsonify({'detected_ingredients': detected_ingredients})  # JSON 형식으로 응답 반환

    except Exception as e:
        return jsonify({'error': str(e)}), 500  # 예외 처리 및 에러 메시지 반환

if __name__ == '__main__':
    app.run(debug=True)
