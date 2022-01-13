<?php
include('../../database/connection.php');
$data = $_POST;

$userID = $data['userID'];

$sorgu = $vt->prepare("UPDATE users SET user_approvalStatus = 'approved' WHERE user_id='$userID'");
$result = $sorgu->execute();

if ($result) {
    $form_data['success'] = true;
    $form_data['closed'] = ' has been successfully approved.';
}

echo json_encode($form_data);

$vt = null;
die();
