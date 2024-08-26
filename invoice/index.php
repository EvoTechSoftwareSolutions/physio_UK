<?php
session_start();
if (!empty($_SESSION["appt_id"]) && !empty($_GET["session_id"])) {

    $curr_date = new DateTime();
    $curr_date->setTimezone(new DateTimeZone('Europe/London'));
    $formatted_date = $curr_date->format("Y-m-d H:i");

    require '../vendor/autoload.php';
    require '../Backend/connection.php';
    $dotenv = Dotenv\Dotenv::createImmutable("../");
    $dotenv->load();

    $stripe_secret_key = $_ENV["STRIPE_KEY"];

    \Stripe\Stripe::setApiKey($stripe_secret_key);

    $session_id = $_GET['session_id'];

    $appt_rs = Database::search(
        "SELECT `appointment`.*, `treatment` FROM `appointment`
        INNER JOIN `treatment` ON `treatment`.`id` = `appointment`.`treatment_id`
        WHERE `appointment`.`id` = ?",
        "i",
        (int)$_SESSION["appt_id"]
    );

    if ($appt_rs->num_rows == 1) {
        $data = $appt_rs->fetch_assoc();
        try {
            $checkout_session = \Stripe\Checkout\Session::retrieve($session_id);

            if ($checkout_session->payment_status == 'paid') {

                $result = null;

                $rs = Database::search(
                    "SELECT * FROM `payment_reciepts` WHERE `appointment_id` = ?",
                    "i",
                    (int)$_SESSION["appt_id"]
                );

                if ($rs->num_rows == 0) {
                    $result = Database::iud(
                        "INSERT INTO `payment_reciepts` (`date`,`amount`,`appointment_id`) VALUES (?,?,?)",
                        "ssi",
                        $formatted_date,
                        $checkout_session->amount_total,
                        $_SESSION["appt_id"]
                    );
                }

                if ($result) {

?>
                    <!DOCTYPE html>
                    <html lang="en">

                    <head>
                        <meta charset="utf-8">
                        <title>Invoice</title>
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
                        <link rel="stylesheet" href="style.css">
                    </head>

                    <body>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="receipt-main col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
                                    <div class="row">
                                        <div class="receipt-header">
                                            <div class="col-xs-6 col-sm-6 col-md-6">
                                                <div class="receipt-left">
                                                    <img class="img-responsive" alt="iamgurdeeposahan"
                                                        src="../resources/img/Physio_logo_banner.png"
                                                        style="width: 250px; border-radius: 43px;">
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 text-right">
                                                <div class="receipt-right">
                                                    <h5>PHYSiO UNLEASHED</h5>
                                                    <p>(+44) 07880286900<i class="fa fa-phone"></i></p>
                                                    <p><a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                                                            data-cfemail="65060a0815040b1c250208040c094b060a08">info@physiounleashed.co.uk</a>
                                                        <i class="fa fa-envelope-o"></i>
                                                    </p>
                                                    <!-- <p>USA <i class="fa fa-location-arrow"></i></p> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="receipt-header receipt-header-mid">
                                            <div class="col-xs-8 col-sm-8 col-md-8 text-left">
                                                <div class="receipt-right">
                                                    <h5><?php echo $data["fname"] . " " . $data["lname"]; ?></h5>
                                                    <p><b>Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b><br />
                                                        &nbsp;&nbsp;&nbsp;&nbsp;<a class="__cf_email__"><?php echo $data["email"]; ?><br /></a>
                                                    </p>
                                                    <p><b>Address&nbsp;&nbsp;&nbsp;:</b><br />
                                                        &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data["line1"]; ?><br />
                                                        &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data["line1"]; ?><br />
                                                        &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data["city"]; ?><br />
                                                        &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data["pcode"]; ?><br />
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-xs-4 col-sm-4 col-md-4">
                                                <div class="receipt-left">
                                                    <h3 class="invoice">INVOICE # <?php echo $result; ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th class="amount">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- <tr>
								<td class="col-md-9">Payment for August 2016</td>
								<td class="col-md-3"><i class="fa fa-inr"></i> 15,000/-</td>
							</tr> -->
                                                <!-- <tr>
								<td class="col-md-9">Payment for June 2016</td>
								<td class="col-md-3"><i class="fa fa-inr"></i> 6,00/-</td>
							</tr> -->
                                                <tr>
                                                    <td class="col-md-9"><?php echo $data["treatment"]; ?></td>
                                                    <td class="col-md-3 price"><i class="fa fa-inr"></i> <?php echo number_format($checkout_session->amount_total / 100, 2); ?>&nbsp;&pound;
                                                    </td>
                                                </tr>
                                                <!-- <tr>
                                <td class="text-right">
                                    <p>
                                        <strong>Total Amount: </strong>
                                    </p>
                                </td>
                                <td>
                                    <p class="price">
                                        <strong><i class="fa fa-inr"></i> 9500/-</strong>
                                    </p>
                                </td>
                            </tr> -->
                                                <tr>
                                                    <td class="text-right">
                                                        <h4 class=""><strong>Total: </strong></h4>
                                                    </td>
                                                    <td class="text-left">
                                                        <h4 class="price"><strong><i class="fa fa-inr"></i> <?php echo number_format($checkout_session->amount_total / 100, 2); ?>&nbsp;&pound;</strong></h4>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="receipt-header receipt-header-mid receipt-footer">
                                            <div class="col-xs-8 col-sm-8 col-md-8 text-left">
                                                <div class="receipt-right">
                                                    <p><b>Date :</b> <?php echo $formatted_date; ?></p>
                                                    <h5 style="color: rgb(140, 140, 140);">Thank you! 
                                                        <br/>
                                                        You will recieve a confirmation email after your request is approved</h5>
                                                </div>
                                            </div>
                                            <br/>
                                            <button style="padding: 1rem;background-color: black;color: white;border-radius: 1rem;float:right" onclick="window.location='../Booking/booking.php'"> &#11164;Back </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
                        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
                        <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                        <script type="text/javascript">
                        </script>
                    </body>

                    </html>
<?php           } else {
                    echo "Something went wrong while processing your payment. Please contact us through email or phone as soon as possible. Thank you";
                }
            } else {
                echo "Payment not completed.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Something went wrong";
    }
} else {
    echo "Something went wrong";
}
?>