<?php
    // DB 연결
    $host = '211.188.54.226';
    $username = 'owl';
    $password = 'minerva';
    $database = 'contest95';
    $port = '13306';

    $conn = mysqli_connect($host, $username, $password, $database, $port);

    if ($conn->connect_error) {
        die("연결 실패: " . $conn->connect_error);
    }

    // 레시피 데이터 가져오기
    $query = 'SELECT * FROM ingredient';
    $result = mysqli_query($conn, $query);

    // 결과가 있는지 확인
    if (mysqli_num_rows($result) > 0) {
        // 첫 번째 행 가져오기
        $row = mysqli_fetch_assoc($result);   

        // 결과 출력
        echo "ID: " . $row['id'] . "<br>";
        echo "Name: " . $row['name'] . "<br>";
    } else {
        echo "결과가 없습니다.";
    }

    // DB 연결 해제 및 결과 해제
    mysqli_free_result($result);
    mysqli_close($conn);
?>