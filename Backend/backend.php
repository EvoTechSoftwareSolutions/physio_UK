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

  require_once("./Backend/vendor/autoload.php");

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
  $payNow = $_POST["payNow"] ?? '';

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

  if (empty($payNow)) {
    $errors[] = "Please select a payment option";
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
          $apptrs = Database::search(
            "SELECT * From `appointment`
                    WHERE `appointment`.`id` = ?",
            "i",
            $id

          );

          if ($apptrs->num_rows > 0) {
            $row = $apptrs->fetch_assoc();

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
          } else {
            $errors[] = "Invalid";
          }




          // Update the appointment status

        }
      }

      if (!empty($errors)) {
        echo $errors[0];  // Output the first error message
      } else {
        $apptrs2 = Database::search(
          "SELECT `appointment`.*, `time` From `appointment`
                  INNER JOIN `schedule` ON `appointment`.`id` = `schedule`.`appointment_id`
                WHERE `appointment`.`id` = ?",
          "i",
          $id

        );
        email($apptrs2->fetch_assoc());
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

      $data = ["success", array_reverse($labels), array_reverse($values), [$accepted->num_rows, $declined->num_rows]];
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

    case "changePassword":
      $un = $_SESSION["admin"]["username"];
      $opw = $_POST["opw"];
      $npw = $_POST["npw"];
      $cpw = $_POST["cpw"];

      if (empty($un)) {
        echo "Invalid username";
        break;
      }

      if (empty($opw) || empty($npw) || empty($cpw)) {
        echo "All fields are required.";
        break;
      }

      if (strlen($npw) < 8 || strlen($npw) > 20) {
        echo "Password must be between 8 and 20 characters long.".$npw;
        break;
      }

      $op_rs = Database::search(
        "SELECT * FROM `admin` WHERE `username` = ? AND `password` = ?",
        "ss",
        $un,$opw
      );
      if ($op_rs->num_rows == 1) {
        $update = Database::iud(
          "UPDATE `admin` SET `password` = ? WHERE `username` = ?",
          "ss",
          $npw,
          $_SESSION["admin"]["username"]
        );
        echo "success";
        break;
      } else {
        echo "Invalid password.";
        break;
      }
      break;
    default:
      echo "Invalid Request";
      break;
  }
}

function email($data)
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
  $mail->addAddress($data["email"]);
  $mail->isHTML(true);
  $mail->Subject = 'Appointment Confirmation';
  $bodyContent = '<!DOCTYPE HTML
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
  xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
  <!--[if gte mso 9]>
<xml>
  <o:OfficeDocumentSettings>
    <o:AllowPNG/>
    <o:PixelsPerInch>96</o:PixelsPerInch>
  </o:OfficeDocumentSettings>
</xml>
<![endif]-->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="x-apple-disable-message-reformatting">
  <!--[if !mso]><!-->
  <meta http-equiv="X-UA-Compatible" content="IE=edge"><!--<![endif]-->
  <title></title>

  <style type="text/css">
    @media only screen and (min-width: 570px) {
      .u-row {
        width: 550px !important;
      }

      .u-row .u-col {
        vertical-align: top;
      }

      .u-row .u-col-100 {
        width: 550px !important;
      }

    }

    @media (max-width: 570px) {
      .u-row-container {
        max-width: 100% !important;
        padding-left: 0px !important;
        padding-right: 0px !important;
      }

      .u-row .u-col {
        min-width: 320px !important;
        max-width: 100% !important;
        display: block !important;
      }

      .u-row {
        width: 100% !important;
      }

      .u-col {
        width: 100% !important;
      }

      .u-col>div {
        margin: 0 auto;
      }
    }

    body {
      margin: 0;
      padding: 0;
    }

    table,
    tr,
    td {
      vertical-align: top;
      border-collapse: collapse;
    }

    p {
      margin: 0;
    }

    .ie-container table,
    .mso-container table {
      table-layout: fixed;
    }

    * {
      line-height: inherit;
    }

    a[x-apple-data-detectors="true"] {
      color: inherit !important;
      text-decoration: none !important;
    }

    table,
    td {
      color: #000000;
    }

    #u_body a {
      color: #0000ee;
      text-decoration: underline;
    }
  </style>



  <!--[if !mso]><!-->
  <link href="https://fonts.googleapis.com/css?family=Lato:400,700&display=swap" rel="stylesheet" type="text/css">
  <!--<![endif]-->

</head>

<body class="clean-body u_body"
  style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #f7f7f7;color: #000000">
  <!--[if IE]><div class="ie-container"><![endif]-->
  <!--[if mso]><div class="mso-container"><![endif]-->
  <table id="u_body"
    style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #f7f7f7;width:100%"
    cellpadding="0" cellspacing="0">
    <tbody>
      <tr style="vertical-align: top">
        <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top">
          <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #f7f7f7;"><![endif]-->



          <div class="u-row-container" style="padding: 0px;background-color: transparent">
            <div class="u-row"
              style="margin: 0 auto;min-width: 320px;max-width: 550px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #111111;">
              <div
                style="border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;">
                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:550px;"><tr style="background-color: #111111;"><![endif]-->

                <!--[if (mso)|(IE)]><td align="center" width="550" style="background-color: #a49e9e;width: 550px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                <div class="u-col u-col-100"
                  style="max-width: 320px;min-width: 550px;display: table-cell;vertical-align: top;">
                  <div style="background-color: #a49e9e;height: 100%;width: 100% !important;">
                    <!--[if (!mso)&(!IE)]><!-->
                    <div
                      style="box-sizing: border-box; height: 100%; padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
                      <!--<![endif]-->

                      <table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0"
                        cellspacing="0" width="100%" border="0">
                        <tbody>
                          <tr>
                            <td
                              style="overflow-wrap:break-word;word-break:break-word;padding:20px 10px;font-family:arial,helvetica,sans-serif;"
                              align="left">

                              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                  <td style="padding-right: 0px;padding-left: 0px;" align="center">

                                    <img align="center" border="0" src="https://evotechsoftwaresolutions.com/resources/email_images/image-5.png" alt="Logo" title="Logo"
                                      style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 530px;"
                                      width="530" />

                                  </td>
                                </tr>
                              </table>

                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <!--[if (!mso)&(!IE)]><!-->
                    </div><!--<![endif]-->
                  </div>
                </div>
                <!--[if (mso)|(IE)]></td><![endif]-->
                <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
              </div>
            </div>
          </div>





          <div class="u-row-container" style="padding: 0px;background-color: transparent">
            <div class="u-row"
              style="margin: 0 auto;min-width: 320px;max-width: 550px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;">
              <div
                style="border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;">
                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:550px;"><tr style="background-color: #ffffff;"><![endif]-->

                <!--[if (mso)|(IE)]><td align="center" width="550" style="width: 550px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                <div class="u-col u-col-100"
                  style="max-width: 320px;min-width: 550px;display: table-cell;vertical-align: top;">
                  <div style="height: 100%;width: 100% !important;">
                    <!--[if (!mso)&(!IE)]><!-->
                    <div
                      style="box-sizing: border-box; height: 100%; padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
                      <!--<![endif]-->

                      <table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0"
                        cellspacing="0" width="100%" border="0">
                        <tbody>
                          <tr>
                            <td
                              style="overflow-wrap:break-word;word-break:break-word;padding:30px 10px 10px;font-family:arial,helvetica,sans-serif;"
                              align="left">

                              <!--[if mso]><table width="100%"><tr><td><![endif]-->
                              <h1
                                style="margin: 0px; line-height: 140%; text-align: center; word-wrap: break-word; font-family: arial,helvetica,sans-serif; font-size: 26px; font-weight: 400;">
                                <span><span><span><span><span><span><span><span><span><span><span><span><span>Your
                                                          Appointment has been Confirmed
                                                          !</span></span></span></span></span></span></span></span></span></span></span></span></span>
                              </h1>
                              <!--[if mso]></td></tr></table><![endif]-->

                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0"
                        cellspacing="0" width="100%" border="0">
                        <tbody>
                          <tr>
                            <td
                              style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;"
                              align="left">

                              <table height="0px" align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #f1f1f1;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                <tbody>
                                  <tr style="vertical-align: top">
                                    <td
                                      style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                      <span>&#160;</span>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>

                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <!--[if (!mso)&(!IE)]><!-->
                    </div><!--<![endif]-->
                  </div>
                </div>
                <!--[if (mso)|(IE)]></td><![endif]-->
                <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
              </div>
            </div>
          </div>





          <div class="u-row-container" style="padding: 0px;background-color: transparent">
            <div class="u-row"
              style="margin: 0 auto;min-width: 320px;max-width: 550px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;">
              <div
                style="border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;">
                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:550px;"><tr style="background-color: #ffffff;"><![endif]-->

                <!--[if (mso)|(IE)]><td align="center" width="550" style="width: 550px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                <div class="u-col u-col-100"
                  style="max-width: 320px;min-width: 550px;display: table-cell;vertical-align: top;">
                  <div style="height: 100%;width: 100% !important;">
                    <!--[if (!mso)&(!IE)]><!-->
                    <div
                      style="box-sizing: border-box; height: 100%; padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
                      <!--<![endif]-->

                      <table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0"
                        cellspacing="0" width="100%" border="0">
                        <tbody>
                          <tr>
                            <td
                              style="overflow-wrap:break-word;word-break:break-word;padding:10px 15px;font-family:arial,helvetica,sans-serif;"
                              align="left">

                              <div style="font-size: 14px; line-height: 140%; text-align: left; word-wrap: break-word;">
                                <p style="font-size: 14px; line-height: 140%;"><span
                                    style="font-size: 16px; line-height: 22.4px;"><strong>Hi ' . $data["fname"] . ' ' . $data["lname"] . ',</strong></span></p>
                                <p style="font-size: 14px; line-height: 140%;"><br /><span
                                    style="font-size: 16px; line-height: 22.4px;">Your appointment has been
                                    confirmed!</span></p>
                                <p style="font-size: 14px; line-height: 140%;">
                                  <br /><strong>Date: ' . $data["appt_date"] . '</strong> <br /><strong>Time: ' . $data["time"] . '</strong> </p>
                                <p style="font-size: 14px; line-height: 140%;"><br />Thank you for scheduling your
                                  appointment with <strong>PHYSiO Unleashed</strong>. If you have any questions, please
                                  contact our office at <span
                                    style="text-decoration: underline; line-height: 19.6px;"><span
                                      style="color: #ff3000; line-height: 19.6px; text-decoration: underline;">+ (44)
                                      795 - 060 - 0297</span></span>. Please take a moment to view the following links
                                  for important information and fill out the necessary forms before your visit. </p>
                                <p style="font-size: 14px; line-height: 140%;"> </p>
                                <p style="font-size: 14px; line-height: 140%;"><strong>Patient Registration Form:</strong> <a
                                    href="https://docs.google.com/forms/d/e/1FAIpQLScKiOb8bp0NZLSeLSoyC8gneRPLuBTtt6og74v0nOCgy-RGOA/viewform?vc=0&c=0&w=1&flr=0">https://docs.google.com/forms/d/e/1FAIpQLScKiOb8bp0NZLSeLSoyC8gneRPLuBTtt6og74v0nOCgy-RGOA/viewform?vc=0&amp;c=0&amp;w=1&amp;flr=0</a>
                                </p>
                                <p style="font-size: 14px; line-height: 140%;"><br /><strong>Patients consent to Treatments:</strong> <a
                                    href="https://docs.google.com/forms/d/e/1FAIpQLSd5gNTfrm50oQzdf1ZMcMSHXCtuFFa2tq5IUpnIjKy7f718Qw/viewform?vc=0&amp;c=0&amp;w=1&amp;flr=0">https://docs.google.com/forms/d/e/1FAIpQLSd5gNTfrm50oQzdf1ZMcMSHXCtuFFa2tq5IUpnIjKy7f718Qw/viewform?vc=0&amp;c=0&amp;w=1&amp;flr=0</a>
                                </p>
                                <p style="font-size: 14px; line-height: 140%;"> </p>
                                <p style="font-size: 14px; line-height: 140%;">Have questions or feedback? You can
                                  contact us anytime at<span style="color: #ff0000; line-height: 19.6px;"> </span><span
                                    style="text-decoration: underline; line-height: 19.6px;"><span
                                      style="color: #ff3000; line-height: 19.6px; text-decoration: underline;">+ (44)
                                      795 - 060 - 0297</span></span></p>
                                <p style="font-size: 14px; line-height: 140%;">, or <span
                                    style="text-decoration: underline; line-height: 19.6px;"><span
                                      style="color: #ff0000; font-size: 14px; line-height: 19.6px; text-decoration: underline;">info@physiounleashed.co.uk </span></span>
                                </p>
                                <p style="font-size: 14px; line-height: 140%;"><br /><span
                                    style="font-size: 16px; line-height: 22.4px;">
                                    tharaka</span><br /><strong>PHYSIO UNLEASHED</strong>                </p>
                              </div>

                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0"
                        cellspacing="0" width="100%" border="0">
                        <tbody>
                          <tr>
                            <td
                              style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;"
                              align="left">

                              <!--[if mso]><style>.v-button {background: transparent !important;}</style><![endif]-->
                              <div align="center">
                                <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="tel:+447880286900" style="height:42px; v-text-anchor:middle; width:165px;" arcsize="9.5%"  stroke="f" fillcolor="#fe6902"><w:anchorlock/><center style="color:#FFFFFF;"><![endif]-->
                                <a href="tel:+447880286900" target="_blank" class="v-button"
                                  style="box-sizing: border-box;display: inline-block;text-decoration: none;-webkit-text-size-adjust: none;text-align: center;color: #FFFFFF; background-color: #fe6902; border-radius: 4px;-webkit-border-radius: 4px; -moz-border-radius: 4px; width:auto; max-width:100%; overflow-wrap: break-word; word-break: break-word; word-wrap:break-word; mso-border-alt: none;font-size: 14px;">
                                  <span style="display:block;padding:12px 40px;line-height:120%;"><strong><span
                                        style="font-size: 14px; line-height: 16.8px; font-family: Lato, sans-serif;">Give
                                        us a Call </span></strong></span>
                                </a>
                                <!--[if mso]></center></v:roundrect><![endif]-->
                              </div>

                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <!--[if (!mso)&(!IE)]><!-->
                    </div><!--<![endif]-->
                  </div>
                </div>
                <!--[if (mso)|(IE)]></td><![endif]-->
                <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
              </div>
            </div>
          </div>





          <div class="u-row-container" style="padding: 0px;background-color: transparent">
            <div class="u-row"
              style="margin: 0 auto;min-width: 320px;max-width: 550px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;">
              <div
                style="border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;">
                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:550px;"><tr style="background-color: #ffffff;"><![endif]-->

                <!--[if (mso)|(IE)]><td align="center" width="550" style="width: 550px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                <div class="u-col u-col-100"
                  style="max-width: 320px;min-width: 550px;display: table-cell;vertical-align: top;">
                  <div style="height: 100%;width: 100% !important;">
                    <!--[if (!mso)&(!IE)]><!-->
                    <div
                      style="box-sizing: border-box; height: 100%; padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
                      <!--<![endif]-->

                      <table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0"
                        cellspacing="0" width="100%" border="0">
                        <tbody>
                          <tr>
                            <td
                              style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;"
                              align="left">

                              <table height="0px" align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #f1f1f1;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                <tbody>
                                  <tr style="vertical-align: top">
                                    <td
                                      style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                      <span>&#160;</span>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>

                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0"
                        cellspacing="0" width="100%" border="0">
                        <tbody>
                          <tr>
                            <td
                              style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;"
                              align="left">

                              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                  <td style="padding-right: 0px;padding-left: 0px;" align="center">

                                    <img align="center" border="0" src="https://evotechsoftwaresolutions.com/resources/email_images/image-4.png" alt="" title=""
                                      style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 500px;"
                                      width="500" />

                                  </td>
                                </tr>
                              </table>

                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <!--[if (!mso)&(!IE)]><!-->
                    </div><!--<![endif]-->
                  </div>
                </div>
                <!--[if (mso)|(IE)]></td><![endif]-->
                <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
              </div>
            </div>
          </div>





          <div class="u-row-container" style="padding: 0px;background-color: transparent">
            <div class="u-row"
              style="margin: 0 auto;min-width: 320px;max-width: 550px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;">
              <div
                style="border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;">
                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:550px;"><tr style="background-color: #ffffff;"><![endif]-->

                <!--[if (mso)|(IE)]><td align="center" width="550" style="width: 550px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                <div class="u-col u-col-100"
                  style="max-width: 320px;min-width: 550px;display: table-cell;vertical-align: top;">
                  <div style="height: 100%;width: 100% !important;">
                    <!--[if (!mso)&(!IE)]><!-->
                    <div
                      style="box-sizing: border-box; height: 100%; padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
                      <!--<![endif]-->

                      <table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0"
                        cellspacing="0" width="100%" border="0">
                        <tbody>
                          <tr>
                            <td
                              style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;"
                              align="left">

                              <table height="0px" align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #f1f1f1;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                <tbody>
                                  <tr style="vertical-align: top">
                                    <td
                                      style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                      <span>&#160;</span>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>

                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0"
                        cellspacing="0" width="100%" border="0">
                        <tbody>
                          <tr>
                            <td
                              style="overflow-wrap:break-word;word-break:break-word;padding:10px 10px 30px;font-family:arial,helvetica,sans-serif;"
                              align="left">

                              <div style="font-size: 14px; line-height: 140%; text-align: left; word-wrap: break-word;">
                                <p style="font-size: 14px; line-height: 140%;"><strong>Questions? </strong>Contact us
                                  now <span style="text-decoration: underline; line-height: 19.6px;"><span
                                      style="color: #ff3000; line-height: 19.6px; text-decoration: underline;">+ (44)
                                      795 - 060 - 0297</span></span> or <span
                                    style="color: #ff3000; line-height: 19.6px;"><span
                                      style="text-decoration: underline; line-height: 19.6px;">info@physiounleashed.co.uk</span> </span>
                                </p>
                              </div>

                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <!--[if (!mso)&(!IE)]><!-->
                    </div><!--<![endif]-->
                  </div>
                </div>
                <!--[if (mso)|(IE)]></td><![endif]-->
                <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
              </div>
            </div>
          </div>





          <div class="u-row-container" style="padding: 0px;background-color: transparent">
            <div class="u-row"
              style="margin: 0 auto;min-width: 320px;max-width: 550px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;">
              <div
                style="border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;">
                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:550px;"><tr style="background-color: transparent;"><![endif]-->

                <!--[if (mso)|(IE)]><td align="center" width="550" style="width: 550px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                <div class="u-col u-col-100"
                  style="max-width: 320px;min-width: 550px;display: table-cell;vertical-align: top;">
                  <div style="height: 100%;width: 100% !important;">
                    <!--[if (!mso)&(!IE)]><!-->
                    <div
                      style="box-sizing: border-box; height: 100%; padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
                      <!--<![endif]-->

                      <table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0"
                        cellspacing="0" width="100%" border="0">
                        <tbody>
                          <tr>
                            <td
                              style="overflow-wrap:break-word;word-break:break-word;padding:20px 10px 10px;font-family:arial,helvetica,sans-serif;"
                              align="left">

                              <div align="center">
                                <div style="display: table; max-width:140px;">
                                  <!--[if (mso)|(IE)]><table width="140" cellpadding="0" cellspacing="0" border="0"><tr><td style="border-collapse:collapse;" align="center"><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; mso-table-lspace: 0pt;mso-table-rspace: 0pt; width:140px;"><tr><![endif]-->


                                  <!--[if (mso)|(IE)]><td width="32" style="width:32px; padding-right: 15px;" valign="top"><![endif]-->
                                  <table align="center" border="0" cellspacing="0" cellpadding="0" width="32"
                                    height="32"
                                    style="width: 32px !important;height: 32px !important;display: inline-block;border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;margin-right: 15px">
                                    <tbody>
                                      <tr style="vertical-align: top">
                                        <td align="center" valign="middle"
                                          style="word-break: break-word;border-collapse: collapse !important;vertical-align: top">
                                          <a href="https://www.facebook.com/physio.unleashed" title="Facebook"
                                            target="_blank">
                                            <img src="https://evotechsoftwaresolutions.com/resources/email_images/image-2.png" alt="Facebook" title="Facebook" width="32"
                                              style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: none;height: auto;float: none;max-width: 32px !important">
                                          </a>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <!--[if (mso)|(IE)]></td><![endif]-->

                                  <!--[if (mso)|(IE)]><td width="32" style="width:32px; padding-right: 15px;" valign="top"><![endif]-->
                                  <table align="center" border="0" cellspacing="0" cellpadding="0" width="32"
                                    height="32"
                                    style="width: 32px !important;height: 32px !important;display: inline-block;border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;margin-right: 15px">
                                    <tbody>
                                      <tr style="vertical-align: top">
                                        <td align="center" valign="middle"
                                          style="word-break: break-word;border-collapse: collapse !important;vertical-align: top">
                                          <a href="https://www.instagram.com/physio.unleashed?utm_source=qr&igsh=eHIyMzY5dzJ4cGpv"
                                            title="Instagram" target="_blank">
                                            <img src="https://evotechsoftwaresolutions.com/resources/email_images/image-1.png" alt="Instagram" title="Instagram" width="32"
                                              style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: none;height: auto;float: none;max-width: 32px !important">
                                          </a>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <!--[if (mso)|(IE)]></td><![endif]-->

                                  <!--[if (mso)|(IE)]><td width="32" style="width:32px; padding-right: 0px;" valign="top"><![endif]-->
                                  <table align="center" border="0" cellspacing="0" cellpadding="0" width="32"
                                    height="32"
                                    style="width: 32px !important;height: 32px !important;display: inline-block;border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;margin-right: 0px">
                                    <tbody>
                                      <tr style="vertical-align: top">
                                        <td align="center" valign="middle"
                                          style="word-break: break-word;border-collapse: collapse !important;vertical-align: top">
                                          <a href="wa.me/+447950600297" title="WhatsApp" target="_blank">
                                            <img src="https://evotechsoftwaresolutions.com/resources/email_images/image-3.png" alt="WhatsApp" title="WhatsApp" width="32"
                                              style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: none;height: auto;float: none;max-width: 32px !important">
                                          </a>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <!--[if (mso)|(IE)]></td><![endif]-->


                                  <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                                </div>
                              </div>

                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0"
                        cellspacing="0" width="100%" border="0">
                        <tbody>
                          <tr>
                            <td
                              style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;"
                              align="left">

                              <div
                                style="font-size: 14px; color: #888888; line-height: 140%; text-align: center; word-wrap: break-word;">
                                <p style="font-size: 14px; line-height: 140%;">&nbsp;Haying trouble viewing this email?
                                  <span
                                    style="text-decoration: underline; font-size: 14px; line-height: 19.6px; color: #ff0000;">Click
                                    here</span></p>
                              </div>

                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0"
                        cellspacing="0" width="100%" border="0">
                        <tbody>
                          <tr>
                            <td
                              style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;"
                              align="left">

                              <div
                                style="font-size: 14px; color: #888888; line-height: 140%; text-align: center; word-wrap: break-word;">
                                <p style="font-size: 14px; line-height: 140%;">Thank You for choosing us  !</p>
                              </div>

                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0"
                        cellspacing="0" width="100%" border="0">
                        <tbody>
                          <tr>
                            <td
                              style="overflow-wrap:break-word;word-break:break-word;padding:10px 10px 15px;font-family:arial,helvetica,sans-serif;"
                              align="left">

                              <div
                                style="font-size: 14px; color: #888888; line-height: 180%; text-align: center; word-wrap: break-word;">
                                <p style="font-size: 14px; line-height: 180%;">© 2024 All Rights Reserved  PHYSiO
                                  UNSHEALED .</p>
                              </div>

                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <!--[if (!mso)&(!IE)]><!-->
                    </div><!--<![endif]-->
                  </div>
                </div>
                <!--[if (mso)|(IE)]></td><![endif]-->
                <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
              </div>
            </div>
          </div>



          <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
        </td>
      </tr>
    </tbody>
  </table>
  <!--[if mso]></div><![endif]-->
  <!--[if IE]></div><![endif]-->
</body>

</html>';
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
