<?php
include('../database/connection.php');

$participantList = array();

$user = $_POST['user'];
$courseID = $_POST['courseID'];


$sql1 = $vt->prepare("SELECT course_participant FROM course WHERE course_id ='$courseID'");
$sql1->execute();
$course = $sql1->fetch();
$participantList = json_decode($course[0]);

array_push($participantList, $user);


$participantList2 = json_encode($participantList);

$sql2 = $vt->prepare("UPDATE course SET course_participant = '$participantList2' WHERE course_id ='$courseID'");
$result = $sql2->execute();

if ($result) {
    $form_data['success'] = true;
    $form_data['info'] = 'You have successfully registered.';
}

echo json_encode($form_data);
$vt = null;
die();
