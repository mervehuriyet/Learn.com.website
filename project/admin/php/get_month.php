<?php

include('../../database/connection.php');

$information = $_POST['information'];
$selectedMonth = $_POST['selectedMonth'];


$stm = $vt->prepare("SELECT * FROM course WHERE (course_instructors LIKE '%$information%' OR course_creator LIKE '%$information%') AND course_startingDate LIKE '_____$selectedMonth%'");
$stm->execute();
$courseList = $stm->fetchAll(PDO::FETCH_OBJ);

if (count($courseList) > 0) {
    $response['course'] = $courseList;
    $response['status'] = true;
} else {
    $response['status'] = false;
}

echo json_encode($response);

$vt = null;
