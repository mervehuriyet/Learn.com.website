<?php

include("../database/connection.php");

$usermail = trim(filter_input(INPUT_POST, 'emailInput', FILTER_SANITIZE_STRING));
$password = trim(filter_input(INPUT_POST, 'passwordInput', FILTER_SANITIZE_STRING));



if (empty($usermail) && empty($password)) {
    $errors['error'] = "Email and password cannot be left blank.";
} else {
    if (empty($usermail)) {
        $errors['error'] = "Please enter your e-mail address.";
    }
    if (empty($password)) {
        $errors['error'] = "Please enter your password.";
    }
}

if (!empty($errors)) {
    $form_data['status'] = false;
    $form_data['errors'] = $errors;
} else {
    $sql = "SELECT * FROM users WHERE user_mail = :userMail";
    if ($stmt = $vt->prepare($sql)) {

        $stmt->bindParam(":userMail", $param_usermail, PDO::PARAM_STR);


        $param_usermail = trim($usermail);

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                if ($row = $stmt->fetch()) {
                    $userID = $row["user_id"];
                    $usermail = $row["user_mail"];
                    $user_approvalStatus = $row["user_approvalStatus"];
                    $hashed_password = $row["user_pwd"];
                    if (password_verify($password, $hashed_password)) {
                        if ($user_approvalStatus == 'approved') {
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $userID;
                            $_SESSION["user_mail"] = $usermail;

                            $form_data['status'] = true;
                            $form_data['successful'] = "You have successfully logged in.";
                        } else {
                            $errors['error'] = "Your account has not been approved. Please wait for approve.";
                            $form_data['status'] = false;
                            $form_data['errors'] = $errors;
                        }
                    } else {
                        $errors['error'] = "Your password is incorrect.";
                        $form_data['status'] = false;
                        $form_data['errors'] = $errors;
                    }
                }
            } else {
                // Display an error message if usermail doesn't exist
                $errors['error'] = "You are not registered with this e-mail address.";
                $form_data['status'] = false;
                $form_data['errors'] = $errors;
            }
        } else {
            $errors['error'] = "Oops! Something's wrong. Please try again later.";
            $form_data['status'] = false;
            $form_data['errors'] = $errors;
        }

        // Close statement
        unset($stmt);
    }
}
echo json_encode($form_data);

unset($vt);
