<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>PHYSiO UNLEASHED</title>
    <link rel="icon" href="../resources/img/title_logo.png" />
    <link rel="stylesheet" href="booking.css" />
    <link rel="stylesheet" href="../Header/header.css" />
    <link rel="stylesheet" href="../Footer/footer.css" />
</head>

<body>

    <!-- Header -->
    <?php
    include "../Header/header.php";
    require "../Backend/connection.php";
    ?>

    <!-- Header -->

    <section class="booking--sec1">

    <div class="booking--sec1--div1">
        <div class="booking--sec1--div2">
            <span class="booking--sec1--header1">Booking Appointment</span>
        </div>
    </div>

        <div class="booking--sec1--div3">
            <div class="booking--sec1--div4">
                <span class="booking--sec1--header2">We're Here to Help You</span>
            </div>
            <div class="booking--sec1--div5">
                <span class="booking--sec1--para1">
                    We unleashed our maximum potential to improve your wellbeing and speedy recovery in your homely environment with high standard of skills, morals and professionalism. We provide not just physiotherapy but a deal with your inner soul with extreme humanism and empathy. Our net profit is your blessings and your happiness.
                </span>
            </div>
        </div>

        <div class="booking--sec1--divlg1">
            <div class="booking--sec1--divlg2">
                <!-- DEMO HTML -->
                <div class="c-compare" style="--value:50%;">
                    <img class="c-compare__left" src="../resources/img/comparison_1.png" alt="Color" />
                    <img class="c-compare__right" src="../resources/img/comparison_2.png" alt="B/W" />
                    <input type="range" class="c-rng c-compare__range" min="0" max="100" value="50" oninput="this.parentNode.style.setProperty('--value', `${this.value}%`)" />
                </div>
                <!-- END DEMO HTML -->
                </main>
                <div class="booking--sec1--divlg3">
                    <div class="booking--sec1--divlg4">
                        <span class="booking--headinglg1">Operating</span>
                        &nbsp;
                        <span class="booking--headinglg2">Hours</span>
                    </div>
                    <div class="booking--sec1--divlg5">
                        <table class="booking--table--lg">
                            <tr>
                                <td>Monday</td>
                                <td></td>
                                <td>8:00 - 20:00</td>
                            </tr>
                            <tr>
                                <td>
                                    <hr class="booking--hours--table--hr">
                                </td>
                                <td></td>
                                <td>
                                    <hr class="booking--hours--table--hr">
                                </td>
                            </tr>
                            <tr>
                                <td>Tuesday</td>
                                <td></td>
                                <td>8:00 - 20:00</td>
                            </tr>
                            <tr>
                                <td>
                                    <hr class="booking--hours--table--hr">
                                </td>
                                <td></td>
                                <td>
                                    <hr class="booking--hours--table--hr">
                                </td>
                            </tr>
                            <tr>
                                <td>Wednesday&nbsp;&nbsp;&nbsp;</td>
                                <td></td>
                                <td>8:00 - 20:00</td>
                            </tr>
                            <tr>
                                <td>
                                    <hr class="booking--hours--table--hr">
                                </td>
                                <td></td>
                                <td>
                                    <hr class="booking--hours--table--hr">
                                </td>
                            </tr>
                            <tr>
                                <td>Thursday</td>
                                <td></td>
                                <td>8:00 - 20:00</td>
                            </tr>
                            <tr>
                                <td>
                                    <hr class="booking--hours--table--hr">
                                </td>
                                <td></td>
                                <td>
                                    <hr class="booking--hours--table--hr">
                                </td>
                            </tr>
                            <tr>
                                <td>Friday</td>
                                <td></td>
                                <td>8:00 - 20:00</td>
                            </tr>
                            <tr>
                                <td>
                                    <hr class="booking--hours--table--hr">
                                </td>
                                <td></td>
                                <td>
                                    <hr class="booking--hours--table--hr">
                                </td>
                            </tr>
                            <tr>
                                <td>Saturday</td>
                                <td></td>
                                <td>8:00 - 20:00</td>
                            </tr>
                            <tr>
                                <td>
                                    <hr class="booking--hours--table--hr">
                                </td>
                                <td></td>
                                <td>
                                    <hr class="booking--hours--table--hr">
                                </td>
                            </tr>
                            <tr>
                                <td>Sunday</td>
                                <td></td>
                                <td>8:00 - 20:00</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="booking--sec1--div6">
                <div class="booking--sec1--div7">
                    <div class="booking--sec1--div8">
                        <div class="booking--sec1--div9">
                            <span class="booking--sec1--heading3">
                                REQUEST AN APPOINTMENT
                            </span>
                        </div>
                        <div class="booking--sec1--div10">
                            <span class="booking--sec1--para2">
                                Please confirm that you would like to request the following appointment:
                            </span>
                            <input type="date" class="booking--date" id="apptDate" />
                        </div>
                        <div class="booking--sec1--div11">
                            <span class="booking--sec1--para3">
                                Your Informations:
                                <span class="booking--star">*</span>
                            </span>
                        </div>
                        <div class="booking--sec1--div12">
                            <span class="booking--sec1--para2">Please enter your first name, last name and email address:</span>
                            <br />
                            <input type="text" class="booking--name" placeholder="First Name..." id="fname" />
                            <input type="text" class="booking--name" placeholder="Last Name..." id="lname" />
                            <input type="text" class="booking--email" placeholder="Email Address..." id="email" />
                            <input type="text" class="booking--email" placeholder="Address Line 1" id="line1" />
                            <input type="text" class="booking--email" placeholder="Address Line 2" id="line2" />
                            <input type="text" class="booking--email" placeholder="City" id="city" />
                            <input type="text" class="booking--email" placeholder="Postal Code" id="pcode" />
                        </div>
                        <div class="booking--sec1--div13">
                            <span class="booking--sec1--para3">
                                Treatment:
                            </span>
                            <select class="booking--chooser" id="apptTrtmnt">
                                <option value="0" selected>Choose..</option>
                                <?php
                                $rs = Database::search(
                                    "SELECT * FROM `treatment`",
                                    "",
                                    ""
                                );
                                if ($rs) {
                                    while ($row = $rs->fetch_assoc()) {
                                ?>
                                        <option value="<?php echo $row["id"];?>"><?php echo $row["treatment"];?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="booking--sec1--div14">
                            <span class="booking--sec1--para3">
                                Your Message:
                            </span>
                            <textarea class="booking--texarea" id="apptMsg"></textarea>
                        </div>
                        <div class="booking--payment">
                            <div class="booking--payOptRow">
                                <input type="radio" class="booking--payMethod" name="bookingPay" id="bookingPay1"/>
                                <label class="booking--payMethodLabel" for="bookingPay1">Pay now</label>
                            </div>
                            <div class="booking--payOptRow">
                                <input type="radio" class="booking--payMethod" name="bookingPay" id="bookingPay2"/>
                                <label class="booking--payMethodLabel" for="bookingPay2">Pay on visit</label>
                            </div>
                        </div>
                        <input type="button" value="Request Appintment" class="booking--send" onclick="bookAppt();">
                        <input type="button" value="Cancel" class="booking--cancel" onclick="clearForm()">
                        <div class="booking--sec1--div15">

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>

    <!-- Footer -->
    <?php include "../Footer/footer.php"; ?>
    <!-- Footer -->

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../Header/header.js"></script>

</body>

</html>