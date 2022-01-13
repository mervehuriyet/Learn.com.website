<?php
require_once "../database/connection.php";
session_start();


if (isset($_SESSION["adminLoggedin"]) && $_SESSION["adminLoggedin"] === true) {
    header("location: admin-index.php");
    exit;
}

$adminMail = $adminPassword = "";
$adminMail_err = $adminPassword_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["adminMail"])) && empty(trim($_POST["adminPwd"]))) {
        $adminMail_err = "Email and password cannot be left blank.";
    } else {
        if (empty(trim($_POST["adminMail"]))) {
            $adminMail_err  = "Please enter your e-mail address.";
        }
        if (empty(trim($_POST["adminPwd"]))) {
            $adminPassword_err = "Please enter your password.";
        }
    }

    if (empty($adminMail_err) && empty($adminPassword_err)) {
        $adminMail = trim($_POST["adminMail"]);
        $adminPassword = trim($_POST["adminPwd"]);

        $sql = "SELECT * FROM users WHERE user_mail = :adminMail and user_role = 'admin'";

        if ($stmt = $vt->prepare($sql)) {

            $stmt->bindParam(":adminMail", $param_adminMail, PDO::PARAM_STR);
            $param_adminMail = trim($_POST["adminMail"]);

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["user_id"];
                        $adminMail = $row["user_mail"];
                        $hashed_password = $row["user_pwd"];

                        if (password_verify($adminPassword, $hashed_password)) {
                            session_start();
                            $_SESSION["adminLoggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["adminMail"] = $adminMail;

                            header("location: admin-index.php");
                        } else {
                            $adminPassword_err = "Your password is incorrect.";
                        }
                    }
                } else {
                    $adminMail_err = "You are not registered with this e-mail address.";
                }
            } else {
                echo "Oops! Something's wrong. Please try again later.";
            }
            unset($stmt);
        }
    }
    unset($vt);
} ?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Admin Login</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-8 mx-auto mt-5">
                <div class="mb-5 mt-5">
                    <div class="col-xl-12 text-center">
                        <h1> Learn.com<sup>ADMIN</sup></h1>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group d-flex align-items-center justify-content-center text-danger <?php echo (!empty($adminMail_err)) ? 'has-error' : ''; ?>  <?php echo (!empty($adminPassword_err)) ? 'has-error' : ''; ?>">
                                <span class="help-block">
                                    <?php echo $adminMail_err; ?>
                                    </br>
                                    <?php echo $adminPassword_err; ?>
                                </span>
                            </div>

                            <div class="form-group mb-4">
                                <label for="adminMail" class="text-gray-800 font-weight-bold loginTitle">E-mail</label>
                                <input type="email" name="adminMail" class="form-control" id="adminMail">

                            </div>
                            <div class="form-group mb-4">
                                <label for="adminPwd" class="text-gray-800 font-weight-bold loginTitle">Password</label>
                                <input type="password" name="adminPwd" id="adminPwd" class="form-control">


                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary btn-block font-weight-bold" id="adminLoginButton">Login</button>
                            </div>

                            <div class="col-md-12">
                                <div class="login-or">
                                    <hr class="hr-or">
                                    <span class="span-or"></span>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>