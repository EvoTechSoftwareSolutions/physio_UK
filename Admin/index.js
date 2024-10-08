(function ($) {
    var dataLabels1;
    var dataValues1;
    function getChartData() {
      var f = new FormData();
      f.append("act", "loadChart");
  
      var r = new XMLHttpRequest();
      r.onreadystatechange = function () {
        if (r.readyState == 4) {
          try {
            // Attempt to parse the response text as JSON
            const jsonResponse = JSON.parse(r.responseText);
  
            if (jsonResponse[0] == "success") {
              dataLabels1 = jsonResponse[1];
              dataValues1 = jsonResponse[2];
  
              // Load the bar chart only after data is fetched
              loadBarChart();
            } else {
              Swal.fire({
                title: "Failed",
                text: "Something went wrong",
                icon: "error",
              });
            }
          } catch (e) {
            Swal.fire({
              title: "Failed",
              text: r.responseText,
              icon: "error",
            });
          }
        }
      };
  
      r.open("POST", "../Backend/backend.php", true);
      r.send(f);
    }
  
    function loadBarChart() {
      // Single Bar Chart
      var ctx4 = $("#bar-chart").get(0).getContext("2d");
      var myChart4 = new Chart(ctx4, {
        type: "bar",
        data: {
          labels: dataLabels1,
          datasets: [
            {
              backgroundColor: [
                "rgba(235, 22, 22, .8)",
                "rgba(235, 22, 22, .7)",
                "rgba(235, 22, 22, .6)",
                "rgba(235, 22, 22, .5)",
                "rgba(235, 22, 22, .4)",
                "rgba(235, 22, 22, .3)",
              ],
              data: dataValues1,
            },
          ],
        },
        options: {
          responsive: true,
        },
      });
    }
  
    ("use strict");
  
    // Spinner
    var spinner = function () {
      setTimeout(function () {
        if ($("#spinner").length > 0) {
          $("#spinner").removeClass("show");
        }
      }, 1);
    };
    spinner();
  
    // Back to top button
    $(window).scroll(function () {
      if ($(this).scrollTop() > 300) {
        $(".back-to-top").fadeIn("slow");
      } else {
        $(".back-to-top").fadeOut("slow");
      }
    });
    $(".back-to-top").click(function () {
      $("html, body").animate({ scrollTop: 0 }, 1500, "easeInOutExpo");
      return false;
    });
  
    // Sidebar Toggler
    $(".sidebar-toggler").click(function () {
      $(".sidebar, .content").toggleClass("open");
      return false;
    });
  
    // Chart Global Color
    Chart.defaults.color = "#6C7293";
    Chart.defaults.borderColor = "#000000";
  
    // Salse & Revenue Chart
    var ctx2 = $("#salse-revenue").get(0).getContext("2d");
    var myChart2 = new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["2016", "2017", "2018", "2019", "2020", "2021", "2022"],
        datasets: [
          {
            label: "Sales",
            data: [15, 30, 55, 45, 70, 65, 85],
            backgroundColor: "rgba(235, 22, 22, .7)",
            fill: true,
          },
          {
            label: "Revenue",
            data: [99, 135, 170, 130, 190, 180, 270],
            backgroundColor: "rgba(235, 22, 22, .5)",
            fill: true,
          },
        ],
      },
      options: {
        responsive: true,
      },
    });
  
    // Fetch chart data when the document is ready
    $(document).ready(function () {
      getChartData();
    });
  
  })(jQuery);
  