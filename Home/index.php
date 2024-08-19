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
    <!--=============== REMIXICONS ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css" crossorigin="">

    <!--=============== SWIPER CSS ===============-->
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="assets/css/slider_img.css">
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
                                    <input type="text" class="about--name" placeholder="Full Name" id="name" />
                                </div>
                                <div class="about--sec3--div22">
                                    <span class="about--sec3--para3">
                                        Email *
                                    </span>
                                    <br>
                                    <input type="text" class="about--name" placeholder="Email Address" id="email" />
                                </div>
                                <div class="about--sec3--div23">
                                    <span class="about--sec3--para3">
                                        Phone Number
                                    </span>
                                    <input type="text" class="about--name" placeholder="Phone Number" id="phone" />
                                </div>
                            </div>
                            <div class="about--sec3--div24">
                                <div class="about--sec3--div25">
                                    <span class="about--sec3--para3">
                                        The Subject
                                    </span>
                                    <input type="text" class="about--name" placeholder="Subject" id="subject" />
                                </div>
                                <div class="about--sec3--div26">
                                    <span class="about--sec3--para3">
                                        Your Message *
                                    </span>
                                    <textarea class="about--texarea" placeholder="Type your message.." id="message"></textarea>
                                </div>
                                <div class="about--sec3--div27">
                                    <input type="button" value="Send Message" class="about--send" id="btn" onclick="sendmassage();">
                                    <input type="button" value="Clear Form" class="about--cancel" onclick="clearForm()">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home--sec3">
        <div class="home--sec3--div1">
            <div class="home--sec3--div2">
                <span class="home--sec3--header1">Our&nbsp;<b>Advantages</b></span>
            </div>
            <div class="home--sec3--div3">
                <div class="container">
                    <div class="cards">
                        <div class="card">
                            <div class="card--img">
                                <img src="../resources/img/icons/ic1.png" class="container--icon1">
                            </div>
                            <div class="card--txt">
                                <div class="card--txt--heading">
                                    <span>Financial Efficiency</span>
                                </div>
                                <div class="card--txt--para">
                                    <span>We offer the most reasonable and best competitive price in the current market.</span>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card--img">
                                <img src="../resources/img/icons/ic2.png" class="container--icon1">
                            </div>
                            <div class="card--txt">
                                <div class="card--txt--heading">
                                    <span>Expertise</span>
                                </div>
                                <div class="card--txt--para">
                                    <span>Deep knowledge and skill in a specialized area of practice.</span>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card--img">
                                <img src="../resources/img/icons/ic3.png" class="container--icon1">
                            </div>
                            <div class="card--txt">
                                <div class="card--txt--heading">
                                    <span>Management Of Time</span>
                                </div>
                                <div class="card--txt--para">
                                    <span>We fit into your most available time. We are operating all the seven days from 8am-8pm.</span>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card--img">
                                <img src="../resources/img/icons/ic4.png" class="container--icon1">
                            </div>
                            <div class="card--txt">
                                <div class="card--txt--heading">
                                    <span>Availability On Weekend And Bank Holidays</span>
                                </div>
                                <div class="card--txt--para">
                                    <span>Open and accessible during weekends and bank holidays for your convenience.</span>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card--img">
                                <img src="../resources/img/icons/ic5.png" class="container--icon1">
                            </div>
                            <div class="card--txt">
                                <div class="card--txt--heading">
                                    <span>Homely Comfort</span>
                                </div>
                                <div class="card--txt--para">
                                    <span>We visit your home or convenient place as it is the most relaxing and comfort place for you.</span>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card--img">
                                <img src="../resources/img/icons/ic6.png" class="container--icon1">
                            </div>
                            <div class="card--txt">
                                <div class="card--txt--heading">
                                    <span>Individualized Care</span>
                                </div>
                                <div class="card--txt--para">
                                    <span>Tailoring care specifically to meet each person's unique needs and preferences.</span>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card--img">
                                <img src="../resources/img/icons/ic7.png" class="container--icon1">
                            </div>
                            <div class="card--txt">
                                <div class="card--txt--heading">
                                    <span>Expanded Range Of Treatments</span>
                                </div>
                                <div class="card--txt--para">
                                    <span>Offering a diverse variety of treatments to address comprehensive patient needs.</span>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card--img">
                                <img src="../resources/img/icons/ic8.png" class="container--icon1">
                            </div>
                            <div class="card--txt">
                                <div class="card--txt--heading">
                                    <span>Easy Booking And Cancellations Policy</span>
                                </div>
                                <div class="card--txt--para">
                                    <span>Streamlined booking and cancellations for a hassle-free, flexible experience.</span>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card--img">
                                <img src="../resources/img/icons/ic9.png" class="container--icon1">
                            </div>
                            <div class="card--txt">
                                <div class="card--txt--heading">
                                    <span>Video/Audio Conference Remote Assistance</span>
                                </div>
                                <div class="card--txt--para">
                                    <span>Providing support via video and audio conferencing for remote assistance.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home--sec4">
        <div class="home--sec4--div2">
            <!-- <img src="../resources/img/home/break.png" class="home--break--img"> -->
            <div class="home--sec4--div1">
                <span class="home--sec4--span">We are here to assist you, regardless of whether your condition resulted from a sports injury, a workplace accident, or any other cause.</span>
                <button class="home--sec4--btn" onclick="window.location='../Booking/booking.php'">Schedule an Appointment</button>
            </div>
        </div>
    </section>

    <section class="home--sec5">
        <div class="home--sec5--div1">
            <!-- <img src="../resources/img/Physio_map_home.png" class="home--map--img"> -->
        </div>
    </section>

    <section class="home--sec6">
        <div class="home--sec6--div1">
            <div class="home--sec6--div2">
                <div class="home--sec6--div3">
                    <span class="home--sec6--header1">Client&nbsp;<b>Testimonials</b></span>
                </div>
                <div class="home--sec6--div4">
                    <!-- <div class="home--sec6--div5"> -->
                    <button class="prev--feedback" onclick="plusSlides(-1)">
                        <</button>
                            <!-- </div>
                    <div class="home--sec6--div6"> -->
                            <button class="next--feedback" onclick="plusSlides(1)">></button>
                            <!-- </div> -->
                </div>
            </div>
            <div class="home--sec6--div7">
                <div class="slider-container--feedback">
                    <div class="slider--feedback">
                        <div class="slide--feedback">
                            <div class="slide--feedback--paradiv">
                                <div class="slide--feedback--image">
                                    <img src="../resources/img/home/inverted_commas.png">
                                </div>
                                <div class="slide--feedback--para">
                                    <p class="p--feedback">I'm very impressed with the way the problem is analysed and how personalized the exercise program is. The physiotherapist spent time explaining to me the underlying cause of the problem so I fully understand what needs to be corrected and can better manage similar issues in the future. It's empowering. </p>
                                </div>
                            </div>
                            <span class="span--feedback">John Atherton</span>
                            <a href="#" class="a--feedback">Moreton</a>
                        </div>
                        <div class="slide--feedback">
                            <div class="slide--feedback--paradiv">
                                <div class="slide--feedback--image">
                                    <img src="../resources/img/home/inverted_commas.png">
                                </div>
                                <div class="slide--feedback--para">
                                    <p class="p--feedback">This really valuable for money and really effective. Since last few years I have been visiting few clinics in local. But I had to spend number of sessions to recover one single injury. Now I’m really fascinated as I spend less amount and physio comes to my place. No hassle, no time wasting. Really this is wonderful.</p>
                                </div>
                            </div>
                            <span class="span--feedback">Debby Simpson</span>
                            <a href="#" class="a--feedback">New Brighton</a>
                        </div>
                        <div class="slide--feedback">
                            <div class="slide--feedback--paradiv">
                                <div class="slide--feedback--image">
                                    <img src="../resources/img/home/inverted_commas.png">
                                </div>
                                <div class="slide--feedback--para">
                                    <p class="p--feedback">I have used Physio Unleashed a number of times Over the last few months for various different issues, post ACL replacement Surgery, Sciatic pain and Rotator cuff tendinopathy through their excellent care, I have always flown through my recover every time, their knowledge is second to none. They are fantastic so friendly and helpful.</p>
                                </div>
                            </div>
                            <span class="span--feedback">Dave Proctor</span>
                            <a href="#" class="a--feedback">Haswell</a>
                        </div>
                        <div class="slide--feedback">
                            <div class="slide--feedback--paradiv">
                                <div class="slide--feedback--image">
                                    <img src="../resources/img/home/inverted_commas.png">
                                </div>
                                <div class="slide--feedback--para">
                                    <p class="p--feedback">Over the last couple of years Harri has provided simply the best Physio I have ever experienced. I have recommended the team to both friends and family. Everyone has a high level of expertise in their field and they have accelerated my recovery with everything from a major shoulder dislocation to cycling induced injuries.</p>
                                </div>
                            </div>
                            <span class="span--feedback">Sophie Salisbury</span>
                            <a href="#" class="a--feedback">West Kirby</a>
                        </div>
                        <div class="slide--feedback">
                            <div class="slide--feedback--paradiv">
                                <div class="slide--feedback--image">
                                    <img src="../resources/img/home/inverted_commas.png">
                                </div>
                                <div class="slide--feedback--para">
                                    <p class="p--feedback">Harry has amazing hands on skills and analytical skills. I feel he has a spell to heal any injury. He pays attention for every patient and we can talk him directly any day, any time. This is quite amazing dedication, we can’t never expect. Main disadvantage with Physio unleashed is, we addict to them.</p>
                                </div>
                            </div>
                            <span class="span--feedback">Vicky Spinks</span>
                            <a href="#" class="a--feedback">Hoylake</a>
                        </div>
                        <div class="slide--feedback">
                            <div class="slide--feedback--paradiv">
                                <div class="slide--feedback--image">
                                    <img src="../resources/img/home/inverted_commas.png">
                                </div>
                                <div class="slide--feedback--para">
                                    <p class="p--feedback">I’m speechless with my recent experience with lower back pain. I was mis diagnosed and spent thousands of pounds for various treatments and couple of MRIs. But this physio diagnosed simply and recovered within few days. I was in such a miserable suffering and now I really feel like I had a re-birth.</p>
                                </div>
                            </div>
                            <span class="span--feedback">Linda Holmes</span>
                            <a href="#" class="a--feedback">Leasow</a>
                        </div>
                        <div class="slide--feedback">
                            <div class="slide--feedback--paradiv">
                                <div class="slide--feedback--image">
                                    <img src="../resources/img/home/inverted_commas.png">
                                </div>
                                <div class="slide--feedback--para">
                                    <p class="p--feedback">I’m really amazed how Harry managed my ACL reconstruction rehabilitation with video consultation. He prescribed all exercises and constantly checked my techniques and progress weekly. This is really effective and cost effective. I saved more than half the cost for Physiotherapy and rehab my knee. In seven months, I back to play football. I highly recommend this service.</p>
                                </div>
                            </div>
                            <span class="span--feedback">Mohammed Yusuff Ali</span>
                            <a href="#" class="a--feedback">UAE</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include "../Footer/footer.php"; ?>
    <!-- Footer -->

    <script src="home.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!--=============== SWIPER JS ===============-->
    <script src="assets/js/swiper-bundle.min.js"></script>

    <!--=============== MAIN JS ===============-->
    <script src="assets/js/main.js"></script>
    <script src="../Slider/slider.js"></script>
    <!-- <script src="../Image_slider/image_slider.js"></script> -->
    <script src="../Header/header.js"></script>

</body>

</html>