function check_value() {
    switch (document.test.field.value) {
        case "one":
            document.getElementById("imagetest").innerHTML = "<img src='../resources/img/services/service-neck.png'>";
            break;
        case "two":
            document.getElementById("imagetest").innerHTML = "<img src='../resources/img/services/service-shoulder.png'>";
            break;
        case "three":
            document.getElementById("imagetest").innerHTML = "<img src='../resources/img/services/service-knee.png'>";
            break;
        case "four":
            document.getElementById("imagetest").innerHTML = "<img src='../resources/img/services/service-hand.png'>";
            break;
        case "five":
            document.getElementById("imagetest").innerHTML = "<img src='../resources/img/services/service-foot.png'>";
    }
}