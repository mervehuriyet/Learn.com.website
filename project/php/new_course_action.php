<?php

include('../database/connection.php');

$errors = array();
$form_data = array();

$instructor = trim(filter_input(INPUT_POST, 'selectInstructorInput', FILTER_SANITIZE_STRING));
$courseName = trim(filter_input(INPUT_POST, 'courseNameInput', FILTER_SANITIZE_STRING));
$courseSubject = trim(filter_input(INPUT_POST, 'subjectInput', FILTER_SANITIZE_STRING));
$courseDetails = trim(filter_input(INPUT_POST, 'detailsInput', FILTER_SANITIZE_STRING));
$courseStartingDate = trim(filter_input(INPUT_POST, 'startingDateInput', FILTER_SANITIZE_STRING));
$courseEndDate = trim(filter_input(INPUT_POST, 'endDateInput', FILTER_SANITIZE_STRING));
$courseCreator = trim(filter_input(INPUT_POST, 'creatorInput', FILTER_SANITIZE_STRING));

if (
    empty($instructor) || empty($courseName) || empty($courseSubject) || empty($courseDetails) || empty($courseStartingDate)
) {
    $errors['error'] = 'Please fill in the blanks.';
}

if (!empty($errors)) {
    $form_data['status'] = false;
    $form_data['errors'] = $errors;
} else {

    $sorgu = $vt->prepare('INSERT INTO course (course_name, course_subject, course_details, course_instructors, course_startingDate, course_endDate, course_creator, course_status, course_participant) 
    VALUES (:c_name, :c_subject, :c_details, :c_instructor, :c_startingDate, :c_endDate, :c_creator, :c_status, :c_participant)');
    if ($sorgu) {
        $result = $sorgu->execute([
            ':c_name' => $courseName,
            ':c_subject' => $courseSubject,
            ':c_details' => $courseDetails,
            ':c_instructor' => $instructor,
            ':c_startingDate' => $courseStartingDate,
            ':c_endDate' => $courseEndDate,
            ':c_creator' => $courseCreator,
            ':c_status' => 'incomplete',
            ':c_participant' => '[]',
        ]);
        if ($result) {
            $form_data['status'] = true;
            $form_data['info'] = 'The course was successfully created.';
        }
    }
}

echo json_encode($form_data);
die();
$vt = null;
