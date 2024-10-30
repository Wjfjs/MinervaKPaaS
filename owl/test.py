import json
import sys
import os
from ultralytics import YOLO

# 첫 번째 인자로 받은 이미지 경로 확인
if len(sys.argv) < 2:
    print("Error: Please provide an image path as an argument.")
    sys.exit(1)

image_path = sys.argv[1]

try:
    # 모델 불러오기
    model = YOLO('best.pt')

    # 예측 수행
    results = model.predict(source=[image_path])

    # 예측된 결과에서 식재료 이름을 추출하여 배열로 저장
    detected_ingredients = []
    for result in results:
        if result.boxes is not None:  # 예측 결과가 있을 때
            for box in result.boxes:
                ingredient = model.names[int(box.cls)]  # 클래스 ID를 통해 식재료 이름 추출
                detected_ingredients.append(ingredient)

    # 결과를 JSON으로 반환
    output_data = {'detected_ingredients': detected_ingredients}
    print(json.dumps(output_data))  # JSON 출력

except Exception as e:
    print(json.dumps({'error': str(e)}))  # 에러 메시지를 JSON 형식으로 출력
