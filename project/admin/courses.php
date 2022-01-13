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


    <title>Admin Courses</title>
</head>

<body>
    <?php include "util/header.php"; ?>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="informationInput" class="font-weight-bold">Write a Insturctor's or Student's İnformation</label>
                                <input type="text" class="form-control input-lksop2" id="informationInput" name="informationInput">
                            </div>
                            <div class="col-md-6">
                                <label for="selectTypeInput" class="font-weight-bold">The Type of Date You Want to List</label>
                                <select class="form-control input-lksop2" id="selectTypeInput" name="selectTypeInput" onchange="typeSelect()">
                                    <option value="today">Today</option>
                                    <option value="month">Specific Month</option>
                                    <option value="between">Between XX/XX/XXXX and YY/YY/YYYY</option>
                                </select>
                            </div>
                        </div>
                        <div class="row d-none" id="monthRow">
                            <div class="col-md-6 mt-3">
                                <label for="selectedMonthInput" class="font-weight-bold">Select Month</label>
                                <select class="form-control input-lksop2" id="selectedMonthInput" name="selectedMonthInput">
                                    <option value='' class="d-none">--Select Month--</option>
                                    <option selected value='01'>Janaury</option>
                                    <option value='02'>February</option>
                                    <option value='03'>March</option>
                                    <option value='04'>April</option>
                                    <option value='05'>May</option>
                                    <option value='06'>June</option>
                                    <option value='07'>July</option>
                                    <option value='08'>August</option>
                                    <option value='09'>September</option>
                                    <option value='10'>October</option>
                                    <option value='11'>November</option>
                                    <option value='12'>December</option>
                                </select>
                            </div>
                        </div>
                        <div class="row d-none" id="betweenRow">
                            <div class="col-md-6 mt-3">
                                <label for="startDateInput" class="font-weight-bold">Select the start date</label>
                                <input type="date" class="form-control input-lksop2" id="startDateInput" name="startDateInput">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="endDateInput" class="font-weight-bold">Select the end date</label>
                                <input type="date" class="form-control input-lksop2" id="endDateInput" name="endDateInput">
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-block btn-lkl23" id="filterBtn">FILTER</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5 d-none" id="infoTableBox">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <table class="table table-bordered" id="courseTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Course Name</th>
                                    <th scope="col">Course Subject</th>
                                    <th scope="col">Course Starting Date</th>
                                    <th scope="col">Course Status</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5 d-none" id="infoBox">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="font-weight-bold text-danger text-center">No results were found for your search.</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "util/footer.php"; ?>

    <script>
        function typeSelect() {
            if (document.getElementById('selectTypeInput').value == "month") {
                $('#monthRow').removeClass('d-none');
                $('#betweenRow').addClass('d-none');

            } else if (document.getElementById('selectTypeInput').value == "between") {
                $('#betweenRow').removeClass('d-none');
                $('#monthRow').addClass('d-none');

            } else {
                $('#monthRow').addClass('d-none');
                $('#betweenRow').addClass('d-none');
            }

        }
    </script>
    <script>
        $('#filterBtn').click(function() {
            $('#tbody').empty();
            var information = document.getElementById('informationInput').value;
            var typeOfDate = document.getElementById('selectTypeInput').value;
            if (typeOfDate == "month") {
                var selectedMonth = document.getElementById('selectedMonthInput').value;
                $.ajax({
                    url: "php/get_month.php",
                    type: "POST",
                    cache: false,
                    data: {
                        information: information,
                        selectedMonth: selectedMonth,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == false) {
                            $('#infoBox').removeClass('d-none');
                            $('#infoTableBox').addClass('d-none');

                        } else {
                            var data = [];
                            $('#infoTableBox').removeClass('d-none');
                            $('#infoBox').addClass('d-none');
                            console.log(response.course)
                            data = response.course;
                            for (let i = 0; i < data.length; i++) {
                                $('#courseTable').find('tbody').append("<tr><td>" + data[i]["course_name"] + "</td><td>" + data[i]["course_subject"] + "</td><td>" + data[i]["course_startingDate"] + "</td><td>" + data[i]["course_status"] + "</td></tr>");
                            }

                        }
                    }
                });
            } else if (typeOfDate == "between") {
                var firstDate = document.getElementById('startDateInput').value;
                var lastDate = document.getElementById('endDateInput').value;
                $.ajax({
                    url: "php/get_between_days.php",
                    type: "POST",
                    cache: false,
                    data: {
                        information: information,
                        firstDate: firstDate,
                        lastDate: lastDate,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == false) {
                            $('#infoBox').removeClass('d-none');
                            $('#infoTableBox').addClass('d-none');

                        } else {
                            var data = [];
                            $('#infoTableBox').removeClass('d-none');
                            $('#infoBox').addClass('d-none');
                            console.log(response.course)
                            data = response.course;
                            for (let i = 0; i < data.length; i++) {
                                $('#courseTable').find('tbody').append("<tr><td>" + data[i]["course_name"] + "</td><td>" + data[i]["course_subject"] + "</td><td>" + data[i]["course_startingDate"] + "</td><td>" + data[i]["course_status"] + "</td></tr>");
                            }

                        }
                    }
                });
            } else {
                $.ajax({
                    url: "php/get_today.php",
                    type: "POST",
                    cache: false,
                    data: {
                        information: information,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == false) {
                            $('#infoBox').removeClass('d-none');
                            $('#infoTableBox').addClass('d-none');

                        } else {
                            var data = [];
                            $('#infoTableBox').removeClass('d-none');
                            $('#infoBox').addClass('d-none');
                            console.log(response.course)
                            data = response.course;
                            for (let i = 0; i < data.length; i++) {
                                $('#courseTable').find('tbody').append("<tr><td>" + data[i]["course_name"] + "</td><td>" + data[i]["course_subject"] + "</td><td>" + data[i]["course_startingDate"] + "</td><td>" + data[i]["course_status"] + "</td></tr>");
                            }

                        }
                    }
                });
            }
        });
    </script>
</body>

</html>