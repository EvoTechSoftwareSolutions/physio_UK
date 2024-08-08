<?php
session_start();

require_once 'connection.php';
require "../mail/SMTP.php";
require "../mail/PHPMailer.php";
require "../mail/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

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
} else if ($_POST["act"] == "addAppt") {
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
    $query = "INSERT INTO `appointment` (`appt_date`, `fname`, `lname`, `email`, `line1`, `line2`, `city`, `pcode`, `msg`, `treatment_id`,`status_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,'1')";
    $types = 'sssssssssi';
    $params = [$date, $fname, $lname, $email, $line1, $line2, $city, $pcode, $msg, (int)$treatment];

    $result = Database::iud($query, $types, ...$params);

    if ($result) {
        echo "success";
    } else {
        echo "Failed to book appointment.";
    }
} else if (!isset($_SESSION["admin"]["username"])) {
    echo "You do not have permission to perform this action. Please log in as an admin";
} else {

    $switch = $_POST["act"];

    switch ($switch) {

        case "acceptAppt":


            $row = "";

            $errors = [];

            if (!isset($_POST["id"]) || !isset($_POST["timeslot"])) {
                $errors[] = "Invalid parameters";
            } else {
                $id = $_POST["id"];
                $timeslot = $_POST["timeslot"];

                // Validate timeslot format (HH:MM)
                if (!preg_match('/^([01][0-9]|2[0-3]):([0-5][0-9])$/', $timeslot)) {
                    $errors[] = "Invalid timeslot format";
                } else {
                    $apptrs = Database::search("SELECT * From `appointment` WHERE `id` = ?", "i", $id);

                    if ($apptrs) {
                        $row = $apptrs->fetch_assoc();
                    }


                    // Update the appointment status
                    $resultUpdate = Database::iud(
                        "UPDATE `appointment` SET `status_id` = 2 WHERE `id` = ?",
                        "i",
                        $id
                    );

                    if ($resultUpdate === null) {
                        $errors[] = "Failed to update the appointment status: " . Database::$connection->error;
                    } else {
                        // Insert the timeslot into the schedule table
                        $resultInsert = Database::iud(
                            "INSERT INTO `schedule` (`time`, `appointment_id`) VALUES (?, ?)",
                            "si",
                            $timeslot,
                            $id
                        );

                        if ($resultInsert === null) {
                            $errors[] = "Failed to schedule the appointment: " . Database::$connection->error;
                        }
                    }
                }
            }

            if (!empty($errors)) {
                echo $errors[0];  // Output the first error message
            } else {
                email($row["email"]);
                echo "Success";  // Output success message if no errors
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

            $accepted = Database::search(
                "SELECT * FROM `appointment` WHERE `status_id` = '2'",
                "",
                ""
            );
            $declined = Database::search(
                "SELECT * FROM `appointment` WHERE `status_id` = '3'",
                "",
                "",
            );

            $data = ["success", array_reverse($labels), array_reverse($values),[$accepted->num_rows, $declined->num_rows]];
            echo json_encode($data);
            break;
        case "getCalendar":
            $rs = Database::search(
                "SELECT DISTINCT `appt_date` FROM `appointment`
                INNER JOIN `schedule` ON `appointment`.`id` = `schedule`.`appointment_id`
                WHERE `appt_date` >= CURDATE() AND `status_id` = 2;",
                "",
                ""
            );
            if ($rs->num_rows > 0) {
                $data = [];
                for ($i = 0; $i < $rs->num_rows; $i++) {
                    $dataSet = $rs->fetch_assoc();
                    $itemArray = explode("-", $dataSet["appt_date"]);
                    $data[] = [
                        "year" => $itemArray[0],
                        "month" => $itemArray[1] - 1,
                        "day" => $itemArray[2]
                    ];
                }
                echo json_encode($data);
            } else {
                echo "[]";
            }
            break;
        case "getSchedule":
            // Retrieve date from POST request
            $dateStr = isset($_POST["date"]) ? $_POST["date"] : '';

            // Define the expected format
            $format = 'd-m-Y'; // Format: '10-08-2024'

            // Create a DateTime object from the date string
            $dateTime = DateTime::createFromFormat($format, $dateStr);

            // Check if the date string matches the expected format
            if ($dateTime && $dateTime->format($format) === $dateStr) {
                $formattedDate = $dateTime->format("Y-m-d");
                $result = Database::search(
                    "SELECT `appointment`.* , `time` FROM `appointment`
        INNER JOIN `schedule` ON `appointment`.`id` = `schedule`.`appointment_id`
        WHERE `appt_date` = ? AND `status_id` = 2 ORDER BY `time` ASC",
                    "s",
                    $formattedDate
                );
                if ($result->num_rows > 0) {
                    $data = [
                        "status" => "success",
                        "date" => $formattedDate,
                        "records" => []
                    ];
                    for ($i = 0; $i < $result->num_rows; $i++) {
                        $dataSet = $result->fetch_assoc();
                        $data["records"][] = [
                            "id" => $dataSet["id"],
                            "name" => $dataSet["fname"] . " " . $dataSet["lname"],
                            "time" => $dataSet["time"],
                            "date" => $dataSet["appt_date"]
                        ];
                    }
                    echo json_encode($data);
                } else {
                    echo json_encode(["status" => "error", "date" => $formattedDate, "message" => "No appointments found for the given date."]);
                }
            } else {
                // The date is invalid or does not match the format
                echo json_encode(["status" => "error", "message" => "Invalid date: " . $dateStr]);
            }
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

function email($email)
{


    // Function to sanitize and validate input
    // function sanitizeInput($data) {
    //     $data = trim($data);
    //     $data = stripslashes($data);
    //     $data = htmlspecialchars($data);
    //     return $data;
    // }

    // Define variables
    // $name = $email = $mobile = $message = '';

    // Check if the form is submitted
    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //     // Sanitize and validate each input
    //     $fname = sanitizeInput($_POST['fname']);
    //     $lname = sanitizeInput($_POST['lname']);
    //     $email = sanitizeInput($_POST['email']);
    //     $mobile = sanitizeInput($_POST['mobile']);
    //     $message = sanitizeInput($_POST['message']);


    // Validate Name
    // if (empty($fname)) {
    //     $errors[] = "Frist Name is required";
    // }

    // Validate Name
    // if (empty($lname)) {
    //     $errors[] = "Last Name is required";
    // }


    // Validate Email
    // if (empty($email)) {
    //     $errors[] = "Email is required";
    // } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //     $errors[] = "Invalid email format";
    // }

    // Validate Mobile
    // if (empty($mobile)) {
    //     $errors[] = "Mobile is required";
    // } elseif (!preg_match('/^\d{10}$/', $mobile)) {
    //     $errors[] = "Mobile should be a 10-digit number";
    // }


    // Validate Message
    // if (empty($message)) {
    //     $errors[] = "Message is required";
    // }

    // If no errors, you can proceed with further actions
    // if (empty($errors)) {

    $mail = new PHPMailer;
    $mail->IsSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'et.website.message@gmail.com';
    $mail->Password = 'glalywegifqhgjhf';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->setFrom('et.website.message@gmail.com', 'Client Message');
    $mail->addReplyTo('et.website.message@gmail.com', 'Client Message');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Client message';
    $bodyContent = '<h1>Customer Message</h1>
        <h3>uk</h3>
        <h3>uk</h3>
        <h3>uk</h3>
        <h3>uk</h3>';
    $mail->Body    = $bodyContent;

    if (!$mail->send()) {
        echo 'Service Unavailable. Please try again later';
    }
    // else {
    //     echo 'Message Sent successfully';
    // }
    // } else {
    //     echo $errors[0];
    // }
    // } 
}
