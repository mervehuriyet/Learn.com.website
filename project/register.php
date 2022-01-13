<?php
require_once "database/connection.php";

session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}

?>

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
    <link rel="stylesheet" href="assets/css/style.css">

    <title>Register</title>
</head>

<body>


    <?php include "util/header.php"; ?>

    <div class="container">
        <div class="row mt-5 mb-5">
            <div class="col-xl-8 col-lg-10 col-md-12 mx-auto mt-5">
                <div class="card login-card">
                    <div class="card-body p-4">
                        <form name="registerForm" id="registerForm" method="POST">
                            <h3 class="h2 mb-4 font-weight-bold text-center">Register</h3>

                            <div class="row mt-2">
                                <div class="form-group col-md-6 mt-3">
                                    <label for="emailInput" class="font-weight-bold">Student/Teacher</label>
                                    <select class="form-control input-lksop2" id="selectJobInput" name="selectJobInput">
                                        <option value="student">Student</option>
                                        <option value="teacher">Teacher</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 mt-3">
                                    <label for="emailInput" class="font-weight-bold">E-mail</label>
                                    <input type="email" id="emailInput" name="emailInput" class="form-control input-lksop2">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="form-group col-md-6 mt-3">
                                    <label for="nameInput" class="font-weight-bold">Name</label>
                                    <input type="text" id="nameInput" name="nameInput" class="form-control input-lksop2">
                                </div>

                                <div class="form-group col-md-6 mt-3">
                                    <label for="surnameInput" class="font-weight-bold">Surname</label>
                                    <input type="text" id="surnameInput" name="surnameInput" class="form-control input-lksop2">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="form-group col-md-6 mt-3">
                                    <label for="passwordInput" class="font-weight-bold">Password</label>
                                    <input type="password" id="passwordInput" name="passwordInput" class="form-control input-lksop2">
                                </div>

                                <div class="form-group col-md-6 mt-3">
                                    <label for="confirmPasswordInput" class="font-weight-bold">Confirm Password</label>
                                    <input type="password" id="confirmPasswordInput" name="confirmPasswordInput" class="form-control input-lksop2">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-lg btn-primary btn-block btn-lkl23">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "util/footer.php"; ?>



    <!-- Register Script -->
    <script>
        $("#registerForm").submit(function(event) {
            event.preventDefault();
            var $data = new FormData(this);

            $.ajax({
                url: "php/register_action.php",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: $data,
                dataType: "json",
                success: function(response) {
                    if (response.status == false) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            title: response.errors.error,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        setInterval(redirectHome, 1800)
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: response.info,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        function redirectHome() {
                            window.location = 'login.php';
                        }
                    }
                }
            });

        });
    </script>


</body>

</html>