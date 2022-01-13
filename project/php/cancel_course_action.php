<?php
include('../database/connection.php');

$user = $_POST['user'];
$courseID = $_POST['courseID'];



$sorgu = $vt->prepare("UPDATE course SET course_status = 'canceled' WHERE course_id ='$courseID' and course_creator ='$user' or course_instructors = '$user'");
$result = $sorgu->execute();

if ($result) {
    $form_data['success'] = true;
    $form_data['info'] = 'The course has been cancelled.';
}

echo json_encode($form_data);
die();

$vt = null;
