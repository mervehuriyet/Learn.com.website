<?php
require_once "database/connection.php";
session_start();

if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$sql = $vt->prepare("SELECT * FROM users WHERE user_role = 'teacher'");
$sql->execute();
$teacherList = $sql->fetchAll(PDO::FETCH_OBJ);

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
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Course</title>
</head>

<body>
    <?php include "util/header.php"; ?>

    <div class="container">
        <div class="row mt-5 mb-5">
            <div class="col-xl-8 col-lg-10 col-md-12 mx-auto mt-5">
                <div class="card login-card">
                    <div class="card-body p-4">
                        <form name="createCourseForm" id="createCourseForm" method="POST">
                            <h3 class="h2 mb-4 font-weight-bold text-center">Create New Course</h3>
                            <div class="row mt-2">
                                <div class="form-group col-md-6 mt-3">
                                    <label for="selectInstructorInput" class="font-weight-bold">Select Instructor <span style="color: red;">*</span></label>
                                    <select class="form-control input-lksop2" id="selectInstructorInput" name="selectInstructorInput">
                                        <?php foreach ($teacherList as $teacher) { ?>
                                            <option value="<?= $teacher->user_name ?> <?= $teacher->user_surname ?>"><?= $teacher->user_name ?> <?= $teacher->user_surname ?></option>
                                        <?php    } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 mt-3">
                                    <label for="courseNameInput" class="font-weight-bold">Course Name <span style="color: red;">*</span></label>
                                    <input type="text" id="courseNameInput" name="courseNameInput" class="form-control input-lksop2">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="form-group col-md-6 mt-3">
                                    <label for="subjectInput" class="font-weight-bold">Course Subject <span style="color: red;">*</span></label>
                                    <input type="text" id="subjectInput" name="subjectInput" class="form-control input-lksop2">
                                </div>

                                <div class="form-group col-md-6 mt-3">
                                    <label for="detailsInput" class="font-weight-bold">Course Details <span style="color: red;">*</span></label>
                                    <input type="text" id="detailsInput" name="detailsInput" class="form-control input-lksop2">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="form-group col-md-6 mt-3">
                                    <label for="startingDateInput" class="font-weight-bold">Course Starting Date <span style="color: red;">*</span></label>
                                    <input type="datetime-local" id="startingDateInput" name="startingDateInput" class="form-control input-lksop2">
                                </div>

                                <div class="form-group col-md-6 mt-3">
                                    <label for="endDateInput" class="font-weight-bold">Course End Date</label>
                                    <input type="datetime-local" id="endDateInput" name="endDateInput" class="form-control input-lksop2">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <input type="text" class="d-none" name="creatorInput" id="creatorInput" value="<?= $_SESSION["user_mail"] ?>">
                                    <button type="submit" class="btn btn-lg btn-success btn-block btn-lkl23">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "util/footer.php"; ?>

    <!-- Create Course Script -->
    <script>
        $("#createCourseForm").submit(function(event) {
            event.preventDefault();
            var $data = new FormData(this);

            $.ajax({
                url: "php/new_course_action.php",
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
                        setInterval(redirectCourses, 1800)
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: response.info,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        function redirectCourses() {
                            window.location = 'course.php';
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>