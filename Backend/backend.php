<?php
require_once 'connection.php';

$date = $_POST['date'] ?? '';
$fname = $_POST['fname'] ?? '';
$lname = $_POST['lname'] ?? '';
$email = $_POST['email'] ?? '';
$msg = $_POST['msg'] ?? '';
$treatment = $_POST['tr'] ?? '';

$errors = [];

// Check if the date is empty or in the past
if (empty($date)) {
    $errors[] = "Appointment date is required.";
} else {
    $apptDate = DateTime::createFromFormat('Y-m-d', $date);
    $today = new DateTime('today');

    if (!$apptDate || $apptDate < $today) {
        $errors[] = "Appointment date cannot be before today.";
    }
}

// Check if first and last names are provided
if (empty($fname) || empty($lname)) {
    $errors[] = "First and last names are required.";
}

// Validate email
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "A valid email is required.";
}

// Validate treatment selection
if (empty($treatment) || !ctype_digit($treatment) || $treatment == '0') {
    $errors[] = "A valid treatment must be selected.";
}

// If there are validation errors, return the first error
if (!empty($errors)) {
    echo $errors[0];
    exit();
}

// Insert data into the database
$query = "INSERT INTO `appointment` (`date`, `first_name`, `last_name`, `email`, `message`, `treatment_id`) VALUES (?, ?, ?, ?, ?, ?)";
$types = 'sssssi';
$params = [$date, $fname, $lname, $email, $msg, (int)$treatment];

$result = Database::iud($query, $types, ...$params);

if ($result) {
    echo "Appointment booked successfully.";
} else {
    echo "Failed to book appointment.";
}
?>
