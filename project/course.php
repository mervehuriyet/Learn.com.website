<?php
require_once "database/connection.php";
session_start();

if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$sql = $vt->prepare("SELECT * FROM course WHERE course_status = 'incomplete'");
$sql->execute();
$courseList = $sql->fetchAll(PDO::FETCH_OBJ);
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
        <div class="row mt-5">
            <div class="col-md-12  mt-5">
                <div class="row">
                    <?php foreach ($courseList as $course) { ?>
                        <div class="col-md-6 col-lg-6 col-xl-4 mt-2">
                            <div class="card shadow">
                                <div class="card-header text-center font-weight-bold">
                                    <?= $course->course_name ?>
                                </div>
                                <?php
                                $participantList = array();
                                $participantList2 = array();
                                $participantList = json_decode($course->course_participant);
                                $doIHave = false;

                                if ($participantList != null) {
                                    foreach ($participantList as $partipicant) {
                                        array_push($participantList2, json_decode(json_encode($partipicant), true));
                                        if ($partipicant == $_SESSION["user_mail"]) {
                                            $doIHave = true;
                                        }
                                    }
                                }
                                $participantCounter = count($participantList2);
                                ?>
                                <div class="card-body">
                                    Subject: <h6 class="font-weight-bold"><?= $course->course_subject ?></h6>
                                    Teacher:<p class="font-weight-bold"> <?= $course->course_instructors ?></p>
                                    Details:<p class="font-weight-bold"> <?= $course->course_details ?></p>
                                    Participant:<p class="font-weight-bold"> <?= $participantCounter ?></p>
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <?php if ($doIHave == true) { ?>
                                                <h6 class="font-weight-bold text-success mb-2 text-center">You have already registered :)</h6>
                                            <?php } else { ?>
                                                <button class="btn btn-success btn-block btn-lkl23 courseRegisterBtn" course_name="<?= $course->course_name ?>" course_id="<?= $course->course_id ?>">Register the Course</button>
                                            <?php } ?>

                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer font-weight-bold text-center">
                                    <?php
                                    if ($course->course_creator == $_SESSION['user_mail'] || $course->course_instructors == $_SESSION['user_mail']) { ?>
                                        Starting Date: <?= $course->course_startingDate ?>
                                        <div class="row">
                                            <div class="col-md-6 mt-3">
                                                <button class="btn btn-danger btn-block btn-lkl23 courseCancelBtn" course_name="<?= $course->course_name ?>" course_id="<?= $course->course_id ?>">Cancel</button>
                                            </div>
                                            <div class="col-md-6  mt-3">
                                                <button class="btn btn-primary btn-block btn-lkl23 courseReScheduleBtn" data-toggle="modal" data-target="#rescheduleModal" course_startingDate="<?= $course->course_startingDate ?>" course_name="<?= $course->course_name ?>" course_id="<?= $course->course_id ?>">Re-Schedule</button>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="row mt-4 mb-4">
                                            <div class="col-md-12 text-center">
                                                Starting Date: <?= $course->course_startingDate ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php   } ?>
                </div>

            </div>
        </div>
    </div>

    <!-- Re-Schedule Modal -->
    <div class="modal fade" id="rescheduleModal" tabindex="-1" role="dialog" aria-labelledby="rescheduleModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rescheduleModalTitle">Re-Schedule Course</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="text-danger" id="courseNameText"></h6>
                    <div class="row">
                        <div class="form-group col-md-12 mt-3">
                            <label for="startingDateInput" class="font-weight-bold">Course Starting Date</label>
                            <input type="datetime-local" id="startingDateInput" name="startingDateInput" class="form-control input-lksop2">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-none" id="secretCourseID"></div>
                    <button type="button" class="btn btn-danger btn-lkl23" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-lkl23" id="rescheduleModalBtn">Re-Schedule this Course</button>
                </div>
            </div>
        </div>
    </div>
    <?php include "util/footer.php"; ?>

    <!-- Register Course Script -->
    <script>
        $(".courseRegisterBtn").click(function() {
            var user = '<?= $_SESSION["user_mail"] ?>';
            var courseID = $(this).attr("course_id");
            var courseName = $(this).attr("course_name");
            Swal.fire({
                title: courseName,
                text: 'Are you sure you want to register in this course?',
                showCancelButton: true,
                cancelButtonText: "Cancel",
                confirmButtonColor: '#00b825',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, I am sure.'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "php/register_course_action.php",
                        type: "POST",
                        data: {
                            user: user,
                            courseID: courseID
                        },
                        cache: false,
                        dataType: "json",
                        success: function(data) {
                            setInterval(reloadPage, 1800)
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: data.info,
                                showConfirmButton: false,
                                timer: 1500
                            })

                            function reloadPage() {
                                location.reload();
                            }
                        }
                    });
                }
            });
        });
    </script>

    <!-- Cancel Course Script -->
    <script>
        $(".courseCancelBtn").click(function() {
            var user = '<?= $_SESSION["user_mail"] ?>';
            var courseID = $(this).attr("course_id");
            var courseName = $(this).attr("course_name");
            Swal.fire({
                title: courseName,
                text: 'Are you sure you want to cancel this course?',
                showCancelButton: true,
                cancelButtonText: "Cancel",
                confirmButtonColor: '#00b825',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, I am sure.'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "php/cancel_course_action.php",
                        type: "POST",
                        data: {
                            user: user,
                            courseID: courseID
                        },
                        cache: false,
                        dataType: "json",
                        success: function(data) {
                            setInterval(reloadPage, 1800)
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: data.info,
                                showConfirmButton: false,
                                timer: 1500
                            })

                            function reloadPage() {
                                location.reload();
                            }
                        }
                    });
                }
            });
        })
    </script>

    <!-- Re-Schedule Course Script -->
    <script>
        $(".courseReScheduleBtn").click(function() {

            var user = '<?= $_SESSION["user_mail"] ?>';
            var courseID = $(this).attr("course_id");
            var courseName = $(this).attr("course_name");
            var courseStartingDate = $(this).attr("course_startingDate");
            document.getElementById('secretCourseID').innerText = courseID;
            document.getElementById('courseNameText').innerText = "Course Name: " + courseName;
            document.getElementById('startingDateInput').value = courseStartingDate;
        });

        $("#rescheduleModalBtn").click(function() {
            var user = '<?= $_SESSION["user_mail"] ?>';
            var courseID = document.getElementById('secretCourseID').innerText;
            var rescheduledDate = document.getElementById('startingDateInput').value;
            $.ajax({
                url: "php/reschedule_course_action.php",
                type: "POST",
                data: {
                    user: user,
                    courseID: courseID,
                    rescheduledDate: rescheduledDate
                },
                cache: false,
                dataType: "json",
                success: function(data) {
                    setInterval(reloadPage, 1800)
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: data.info,
                        showConfirmButton: false,
                        timer: 1500
                    })

                    function reloadPage() {
                        location.reload();
                    }
                }
            });
        });
    </script>
</body>

</html>