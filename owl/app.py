# app.py
from flask import Flask, request, jsonify
from ultralytics import YOLO
import os

app = Flask(__name__)

# 업로드 디렉토리 설정
UPLOAD_FOLDER = 'uploads'
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

# 모델 불러오기
model = YOLO('best.pt')

@app.route('/predict', methods=['POST'])
def predict():
    if 'image' not in request.files:
        return jsonify({'error': 'No image file provided'}), 400

    file = request.files['image']
    file_path = os.path.join(UPLOAD_FOLDER, file.filename)
    file.save(file_path)

    results = model.predict(source=[file_path])
    detected_ingredients = []
    for result in results:
        if result.boxes is not None:
            for box in result.boxes:
                ingredient = model.names[int(box.cls)]
                detected_ingredients.append(ingredient)

    return jsonify({'detected_ingredients': detected_ingredients})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
