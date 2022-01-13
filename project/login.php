<?php
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

    <title>Login</title>
</head>

<body>

    <?php include "util/header.php"; ?>

    <div class="container">
        <div class="row mt-5 mb-5">
            <div class="col-xl-6 col-lg-8 col-md-10 mx-auto mt-5">
                <h3 class="h2 mb-5 font-weight-bold text-center mt-2">Learn.com</h3>
                <div class="card login-card">
                    <div class="card-body p-4">
                        <form name="loginForm" id="loginForm" method="POST">
                            <h3 class="h2 mb-4 font-weight-bold text-center">Login</h3>

                            <div class="form-group col-md-12 mt-4">
                                <label for="emailInput" class="font-weight-bold">Email address</label>
                                <input type="email" id="emailInput" name="emailInput" class="form-control input-lksop2">
                            </div>

                            <div class="form-group col-md-12 mt-4">
                                <label for="passwordInput" class="font-weight-bold">Password</label>
                                <input type="password" id="passwordInput" name="passwordInput" class="form-control input-lksop2">
                            </div>

                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-lg btn-primary btn-block btn-lkl23">Login</button>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php include "util/footer.php"; ?>

    <script>
        $("#loginForm").submit(function(event) {
            event.preventDefault();
            var $data = new FormData(this);
            $.ajax({
                url: "php/login_action.php",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: $data,
                dataType: "json",
                success: function(data) {
                    if (data.status == false) {
                        console.log("Error")
                        Swal.fire({
                            position: 'top-start',
                            icon: 'warning',
                            title: data.errors.error,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    } else {
                        setInterval(redirectHome, 1800)
                        Swal.fire({
                            position: 'top-start',
                            icon: 'success',
                            title: data.successful,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        function redirectHome() {
                            window.location = 'index.php';
                        }
                    }

                }
            });
        });
    </script>

</body>

</html>