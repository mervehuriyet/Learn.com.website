<?php

include('../database/connection.php');

$errors = array(); //To store errors
$form_data = array();

$mail = trim(filter_input(INPUT_POST, 'emailInput', FILTER_SANITIZE_STRING));
$name = trim(filter_input(INPUT_POST, 'nameInput', FILTER_SANITIZE_STRING));
$surName = trim(filter_input(INPUT_POST, 'surnameInput', FILTER_SANITIZE_STRING));
$job = trim(filter_input(INPUT_POST, 'selectJobInput', FILTER_SANITIZE_STRING));
$password = trim(filter_input(INPUT_POST, 'passwordInput', FILTER_SANITIZE_STRING));
$passwordConfirm = trim(filter_input(INPUT_POST, 'confirmPasswordInput', FILTER_SANITIZE_STRING));

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$hashed_passwordConfirm = password_hash($passwordConfirm, PASSWORD_DEFAULT);


if (
    empty($mail) || empty($name) || empty($surName) || empty($password) || empty($passwordConfirm)
) {
    $errors['error'] = 'Please fill in the blanks.';
} else if (!empty($mail)) {

    $sql = $vt->prepare("SELECT * FROM users WHERE user_mail='$mail'");
    $sql->execute();
    $mailResult = $sql->fetchAll(PDO::FETCH_OBJ);

    if (count($mailResult) > 0) {
        $errors['error'] = 'Already registered with this e-mail address.';
    }
}

if ($password != $passwordConfirm) {
    $errors['error'] = "Your passwords don't match.";
}

$len = strlen($password);
if ($len < 8) {
    $errors['error'] = "Your password cannot be shorter than 8 characters.";
}


if (!empty($errors)) {
    $form_data['status'] = false;
    $form_data['errors'] = $errors;
} else {

    $sorgu = $vt->prepare('INSERT INTO users (user_name, user_surname, user_mail, user_pwd, user_role, user_approvalStatus) 
    VALUES (:u_name, :u_surname, :u_mail, :u_pwd, :u_role, :u_approvalStatus)');
    if ($sorgu) {
        $result = $sorgu->execute([
            ':u_name' => $name,
            ':u_surname' => $surName,
            ':u_mail' => $mail,
            ':u_pwd' => $hashed_password,
            ':u_role' => $job,
            ':u_approvalStatus' => 'unapproved',
        ]);
        if ($result) {
            $form_data['status'] = true;
            $form_data['info'] = 'Your account has been successfully created.';
        }
    }
}

echo json_encode($form_data);
die();
$vt = null;
