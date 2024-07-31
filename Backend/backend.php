<?php
session_start();
require_once 'connection.php';
$switch = $_POST["act"];

switch ($switch) {

    case "addAppt":
        $date = $_POST['date'] ?? '';
        $fname = $_POST['fname'] ?? '';
        $lname = $_POST['lname'] ?? '';
        $email = $_POST['email'] ?? '';
        $msg = $_POST['msg'] ?? '';
        $treatment = $_POST['tr'] ?? '';

        $errors = [];

        if (empty($date)) {
            $errors[] = "Appointment date is required.";
        } else {
            $apptDate = DateTime::createFromFormat('Y-m-d', $date);
            $today = new DateTime('today');

            if (!$apptDate || $apptDate < $today) {
                $errors[] = "Appointment date cannot be before today.";
            }
        }

        if (empty($fname) || empty($lname)) {
            $errors[] = "First and last names are required.";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "A valid email is required.";
        }

        if (empty($treatment) || !ctype_digit($treatment) || $treatment == '0') {
            $errors[] = "A valid treatment must be selected.";
        }

        if (!empty($errors)) {
            echo $errors[0];
            exit();
        }

        $query = "INSERT INTO `appointment` (`date`, `fname`, `lname`, `email`, `msg`, `treatment_id`) VALUES (?, ?, ?, ?, ?, ?)";
        $types = 'sssssi';
        $params = [$date, $fname, $lname, $email, $msg, (int)$treatment];
        $result = Database::iud($query, $types, ...$params);

        if ($row = $result->fetch_assoc()) {
            echo "success";
        } else {
            echo "Failed to book appointment.";
        }
        break;
    case "login":
        $un = $_POST['un'] ?? '';
        $pw = $_POST['pw'] ?? '';

        $errors = [];

        if (empty($un)) {
            $errors[] = "Username is required.";
        }

        if (empty($pw)) {
            $errors[] = "Password is required.";
        }

        if (!empty($errors)) {
            echo $errors[0];
            exit();
        }

        $query = "SELECT * FROM `admin` WHERE `username` = ? AND `password` = ?";
        $types = 'ss';
        $params = [$un,$pw];
        $result = Database::search($query, $types, ...$params);

        if ($result->num_rows > 0) {
            $_SESSION["admin"] = $result->fetch_assoc();
            echo "success";
        } else {
            echo "Invalid username or password.";
        }
        break;
    default:
        echo "Invalid Request";
        break;
}
