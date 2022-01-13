<?php
include('../database/connection.php');
session_start();
if (!isset($_SESSION["adminLoggedin"]) || $_SESSION["adminLoggedin"] !== true) {
    header("location: admin-login.php");
    exit;
}

$sql = $vt->prepare("SELECT * FROM users WHERE user_approvalStatus = 'unapproved'");
$sql->execute();
$userList = $sql->fetchAll(PDO::FETCH_OBJ);
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
    <!-- Boostrap Datatable Styles -->
    <link href="../assets/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">


    <title>Admin Homepage</title>
</head>

<body>
    <?php include "util/header.php"; ?>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <table class="table table-bordered admin-table" id="dataTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Surname</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Approve</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($userList as $user) { ?>
                                    <tr>
                                        <th scope="row"><?= $user->user_id ?></th>
                                        <td><?= $user->user_name ?></td>
                                        <td><?= $user->user_surname ?></td>
                                        <td><?= $user->user_mail ?></td>
                                        <td class="text-center"><button class="btn btn-success changeStatus" id="<?= $user->user_id ?>" user_fullName='<?= $user->user_name ?> <?= $user->user_surname ?>'>Approve</button></td>
                                    </tr>
                                <?php   } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "util/footer.php"; ?>

    <!-- Approve Script-->
    <script>
        $(document).on('click', '.changeStatus', function() {
            var userID = $(this).attr("id");
            var user_fullName = $(this).attr("user_fullName");
            Swal.fire({
                title: 'User: ' + user_fullName,
                text: 'Are you sure you want to approve the user?',
                showCancelButton: true,
                confirmButtonColor: '#00b825',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, I approve',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "php/approve_action.php",
                        type: "POST",
                        data: {
                            userID: userID
                        },
                        cache: false,
                        dataType: "json",
                        success: function(data) {
                            setInterval(reloadPage, 1800);
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: user_fullName + data.closed,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    });
                }
            })

        });

        function reloadPage() {
            location.reload();
        }
    </script>
</body>

</html>