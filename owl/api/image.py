from flask import Flask, request, jsonify
import torch
from PIL import Image
import os

# Flask 앱 초기화을 초기화합니다.
app = Flask(__name__)

# YOLO
model = torch.hub.load('ultralytics/yolov8', 'custom', path='model.pt')

# 업로드 경로
UPLOAD_FOLDER = 'analysis'
if not os.path.exists(UPLOAD_FOLDER):
    os.makedirs(UPLOAD_FOLDER)

@app.route('/analysis', methods=['POST'])
def upload_image():
    if 'file' not in request.files:
        return jsonify({'error': 'No file part'}), 400
    
    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No selected file'}), 400
    
    # 파일 저장
    file_path = os.path.join(UPLOAD_FOLDER, file.filename)
    file.save(file_path)

    # 이미지 분석
    results = model(file_path)

    # 결과를 JSON 형식으로 변환
    detections = results.pandas().xyxy[0].to_dict(orient="records")
    
    # 분석 결과 반환
    return jsonify(detections)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
