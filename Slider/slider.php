<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="slider.css">
</head>
<body>

    <!-- carousel -->
    <div class="carousel">
        <!-- list item -->
        <div class="list">
            <div class="item">
                <img src="../resources/img/slider/sl5.png" class="slider--img">
            </div>
            <div class="item">
                <img src="../resources/img/slider/sl1.png" class="slider--img">
                <div class="content">
                    <div class="author">PHYSiO UNLEASHED</div>
                    <div class="title">A PRACTICE BUILT ON</div>
                    <div class="topic">HONESTY AND INTEGRITY</div>
                    <div class="des">
                    A practice built on honesty and integrity prioritizes transparency, trust, and ethical conduct in all interactions. It fosters an environment where truthful communication and responsible actions are the norms, ensuring that all decisions are made with fairness and respect for all parties involved. This foundation enhances credibility and long-term success.
                    </div>
                    <div class="buttons">
                        <button onclick="window.location='../About/about.php#contact';">Get&nbsp;In&nbsp;Touch</button>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="../resources/img/slider/sl2.png" class="slider--img">
                <div class="content">
                    <div class="author">PHYSiO UNLEASHED</div>
                    <div class="title">OUR EARNING IS</div>
                    <div class="topic">HUMANITY AND CLIENTS’ SATISFACTION</div>
                    <div class="des">
                    "Our earning is humanity and clients' satisfaction" emphasizes prioritizing compassionate service and customer happiness over monetary gains. It focuses on building meaningful relationships through empathy and exceptional service. This approach ensures that business success is measured by positive client experiences and the lasting impact on the community rather than financial profits alone.
                    </div>
                    <div class="buttons">
                        <button onclick="window.location='../About/about.php#contact';">Get&nbsp;In&nbsp;Touch</button>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="../resources/img/slider/sl3.png" class="slider--img">
                <div class="content">
                    <div class="author">PHYSiO UNLEASHED</div>
                    <div class="title">WE HANDLE HUMAN BEINGS,</div>
                    <div class="topic">NOT JUST A CLINICAL CASE</div>
                    <div class="des">
                    "We handle human beings, not just a clinical case" underscores a holistic approach to care that prioritizes the individual's overall well-being. It emphasizes empathy, understanding, and personalized treatment, recognizing that each person has unique needs and emotions, which are as important as addressing their medical conditions.
                    </div>
                    <div class="buttons">
                        <button onclick="window.location='../About/about.php#contact';">Get&nbsp;In&nbsp;Touch</button>
                    </div>
                </div>
            </div>
            <div class="item">
                <img src="../resources/img/slider/sl4.png" class="slider--img">
                <div class="content">
                    <div class="author">PHYSiO UNLEASHED</div>
                    <div class="title">OUR SAVING IS CLIENT’S</div>
                    <div class="topic">HAPPINESS AND SMILEY FACES</div>
                    <div class="des">
                    "Our saving is clients' happiness and smiley faces" highlights a commitment to ensuring customer satisfaction as the ultimate reward. It focuses on delivering exceptional service and creating positive experiences, where the true value lies in the joy and smiles of satisfied clients, reflecting a dedication to meaningful and impactful service.
                    </div>
                    <div class="buttons">
                        <button onclick="window.location='../About/about.php#contact';">Get&nbsp;In&nbsp;Touch</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- list thumnail -->
        <div class="thumbnail">
            <div class="item">
                <img src="../resources/img/slider/sl5.png">
            </div>
            <div class="item">
                <img src="../resources/img/slider/sl1.png">
            </div>
            <div class="item">
                <img src="../resources/img/slider/sl2.png">
            </div>
            <div class="item">
                <img src="../resources/img/slider/sl3.png">
            </div>
            <div class="item">
                <img src="../resources/img/slider/sl4.png">
            </div>
        </div>
       
        <!-- time running -->
        <div class="time"></div>
    </div>

     <!-- next prev -->

        <div class="arrows">
            <button id="prev"><</button>
            <button id="next">></button>
        </div>

    <script src="slider.js"></script>
</body>
</html>