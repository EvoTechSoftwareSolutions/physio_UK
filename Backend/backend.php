<?php
session_start();
require_once 'connection.php';
if (!isset($_POST["act"])) {
    echo "Invalid request body";
} else if ($_POST["act"] == "login") {
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
    $params = [$un, $pw];
    $result = Database::search($query, $types, ...$params);

    if ($result->num_rows > 0) {
        $_SESSION["admin"] = $result->fetch_assoc();
        echo "success";
    } else {
        echo "Invalid username or password.";
    }
} else if (!isset($_SESSION["admin"]["username"])) {
    echo "You do not have permission to perform this action. Please log in as an admin";
} else {

    $switch = $_POST["act"];

    switch ($switch) {

        case "addAppt":
            $date = $_POST['date'] ?? '';
            $fname = $_POST['fname'] ?? '';
            $lname = $_POST['lname'] ?? '';
            $email = $_POST['email'] ?? '';
            $line1 = $_POST['line1'] ?? '';
            $line2 = $_POST['line2'] ?? '';
            $city = $_POST['city'] ?? '';
            $pcode = $_POST['pcode'] ?? '';
            $msg = $_POST['msg'] ?? '';
            $treatment = $_POST['tr'] ?? '';

            $errors = [];

            // Validate appointment date
            if (empty($date)) {
                $errors[] = "Appointment date is required.";
            } else {
                $apptDate = DateTime::createFromFormat('Y-m-d', $date);
                $today = new DateTime('today');

                if (!$apptDate || $apptDate < $today) {
                    $errors[] = "Appointment date cannot be before today.";
                }
            }

            // Validate first and last names
            if (empty($fname) || empty($lname)) {
                $errors[] = "First and last names are required.";
            }

            // Validate email
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "A valid email is required.";
            }

            // Validate address fields
            if (empty($line1) || empty($city) || empty($pcode)) {
                $errors[] = "Address line 1, city, and postal code are required.";
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
            $query = "INSERT INTO `appointment` (`date`, `fname`, `lname`, `email`, `line1`, `line2`, `city`, `pcode`, `msg`, `treatment_id`,`status_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,``)";
            $types = 'sssssssssi';
            $params = [$date, $fname, $lname, $email, $line1, $line2, $city, $pcode, $msg, (int)$treatment];

            $result = Database::iud($query, $types, ...$params);

            if ($result) {
                echo "success";
            } else {
                echo "Failed to book appointment.";
            }
            break;
        case "acceptAppt":
            $errors = [];
            if (!isset($_POST["id"])) {
                $errors[] = "Invalid appointment id";
            } else {
                $id = $_POST["id"];

                $rs = Database::search(
                    "SELECT * FROM `appointment` WHERE `id` = ?",
                    "s",
                    $id
                );

                if ($rs) {
                    $row = $rs->fetch_assoc();
                    if ($row["status_id"] != 1) {
                        $errors[] = "Somehing went wrong. Please refresh the page";
                    } else {
                        $result = Database::iud(
                            "UPDATE `appointment` SET `status_id` = 2 WHERE `id` = ?",
                            "i",
                            $id
                        );
                    }
                } else {
                    $errors[] = "Invalid request parameters";
                }
            }

            if (!empty($errors)) {
                echo $errors[0];
            } else {
                echo "Success";
            }
            break;
        case "declineAppt":
            $errors = [];
            if (!isset($_POST["id"])) {
                $errors[] = "Invalid appointment id";
            } else {
                $id = $_POST["id"];

                $rs = Database::search(
                    "SELECT * FROM `appointment` WHERE `id` = ?",
                    "s",
                    $id
                );

                if ($rs) {
                    $row = $rs->fetch_assoc();
                    if ($row["status_id"] != 1) {
                        $errors[] = "Somehing went wrong. Please refresh the page";
                    } else {
                        $result = Database::iud(
                            "UPDATE `appointment` SET `status_id` = 3 WHERE `id` = ?",
                            "i",
                            $id
                        );
                    }
                } else {
                    $errors[] = "Invalid request parameters";
                }
            }

            if (!empty($errors)) {
                echo $errors[0];
            } else {
                echo "Success";
            }

            break;

        case "loadChart":
            $date = date("Y-m-d");
            $currentMonthNumber = date("m", strtotime($date));
            $currentYear = date("Y", strtotime($date));
            $mNum = $currentMonthNumber;
            $labels = [];
            $values = [];

            for ($i = 0; $i < 6; $i++) {

                if ($mNum > 1) {
                    $mNum -= 1;
                } else {
                    $mNum = 12;
                    $currentYear -= 1;
                }

                $count = Database::search(
                    "SELECT * FROM `appointment` WHERE YEAR(`appt_date`) = ? AND MONTH(`appt_date`) = ? AND `status_id` = ?",
                    "iii",
                    $currentYear,
                    $mNum,
                    2
                );

                $monthName = date('F', mktime(0, 0, 0, $mNum, 10));

                $labels[] = $monthName;
                $values[] = $count->num_rows;
            }

            $data = ["success", array_reverse($labels), array_reverse($values)];
            echo json_encode($data);
            break;

        case "signout":
            $_SESSION["admin"] = 0;
            session_destroy();
            echo "success";
            break;
        default:
            echo "Invalid Request";
            break;
    }
}
