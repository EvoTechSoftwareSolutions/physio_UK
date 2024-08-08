<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>PHYSiO UNLEASHED</title>
    <link rel="icon" href="../resources/img/title_logo.png" />
    <link rel="stylesheet" href="../Header/header.css" />
    <link rel="stylesheet" href="../Slider/slider.css" />
    <link rel="stylesheet" href="home.css" />
    <link rel="stylesheet" href="../Image_slider/image_slider.css" />
    <link rel="stylesheet" href="../Footer/footer.css" />
</head>

<body>

    <!-- Header -->
    <?php include "../Header/header.php"; ?>
    <!-- Header -->

    <!-- Slider -->
    <?php include "../Slider/slider.php"; ?>
    <!-- Slider -->

    <section class="home--sec1">
        <div class="home--sec1--div1">
            <div class="home--sec1--div2">
                <span class="home--sec1--span1">
                    We<b>&nbsp;offer various Physio Disciplines</b>
                </span>
                <div class="home--sec1--div3">
                    <span class="home--sec1--span2">
                        We offer a range of physiotherapy disciplines tailored to meet diverse needs, including musculoskeletal therapy for joint and muscle issues, neurological physiotherapy to improve nervous system function, sports injury management, post-surgery rehabilitation for recovery, and specialized clinical Pilates for strength and flexibility, ensuring comprehensive care and recovery.
                    </span>
                </div>
                <!-- <br/> -->
                <button class="home--sec1--button1" onclick="window.location='../Services/services.php'">
                    See all Services
                </button>
            </div>
            <div class="home--sec1--div4">
                <!-- Image Slider -->
                <?php include "../Image_slider/image_slider.php"; ?>
                <!-- Image Slider -->
            </div>
        </div>
    </section>

    <section class="home--sec2">
        <div class="home--sec2--div1">
            <div class="home--sec2--div2">
                <div class="home--sec2--div3">
                    <span class="home--sec2--header1">About&nbsp;<b>Us</b></span>
                </div>
                <div class="home--sec2--div4">
                    <div class="home--sec2--div5">
                        <div class="home--sec2--div6">
                            <img src="../resources/img/home/ha1.png" class="home--about--img1">
                        </div>
                        <div class="home--sec2--div7">
                            <img src="../resources/img/home/ha2.png" class="home--about--img2">
                        </div>
                    </div>
                    <div class="home--sec2--div8">
                        Physio Unleashed offers innovative physiotherapy services that empower clients to achieve optimal health and performance. Our expert team provides personalized treatment plans, incorporating techniques such as manual therapy, exercise programs, and sports rehabilitation. We focus on holistic care, ensuring recovery, pain relief, and enhanced mobility for a healthier lifestyle.
                    </div>
                </div>
            </div>
            <div class="home--sec2--div9">
                <div class="home--sec2--div10">
                    <span class="home--sec2--header2">Ask&nbsp;<b>a Question</b></span>
                </div>
                <div class="home--sec2--div11">
                    <div class="about--sec3--div19">
                        <div class="about--sec3--div28">
                            <div class="about--sec3--div20">
                                <div class="about--sec3--div21">
                                    <span class="about--sec3--para3">
                                        Name *
                                    </span>
                                    <br>
                                    <input type="text" class="about--name" placeholder="Full Name" />
                                </div>
                                <div class="about--sec3--div22">
                                    <span class="about--sec3--para3">
                                        Email *
                                    </span>
                                    <br>
                                    <input type="text" class="about--name" placeholder="Email Address" />
                                </div>
                                <div class="about--sec3--div23">
                                    <span class="about--sec3--para3">
                                        Phone Number *
                                    </span>
                                    <input type="text" class="about--name" placeholder="Phone Number" />
                                </div>
                            </div>
                            <div class="about--sec3--div24">
                                <div class="about--sec3--div25">
                                    <span class="about--sec3--para3">
                                        The Subject
                                    </span>
                                    <input type="text" class="about--name" placeholder="Subject" />
                                </div>
                                <div class="about--sec3--div26">
                                    <span class="about--sec3--para3">
                                        Your Message *
                                    </span>
                                    <textarea class="about--texarea" placeholder="Type your message.."></textarea>
                                </div>
                                <div class="about--sec3--div27">
                                    <input type="button" value="Send Message" class="about--send">
                                    <input type="button" value="Clear Form" class="about--cancel">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include "../Footer/footer.php"; ?>
    <!-- Footer -->

    <script src="../Slider/slider.js"></script>
    <script src="../Image_slider/image_slider.js"></script>
    <script src="../Header/header.js"></script>

</body>

</html>