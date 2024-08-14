function login() {
  var un = document.getElementById("un").value;
  var pw = document.getElementById("pw").value;

  var f = new FormData();
  f.append("un", un);
  f.append("pw", pw);
  f.append("act", "login");

  var r = new XMLHttpRequest();
  r.onreadystatechange = function () {
    if (r.readyState == 4) {
      if (r.responseText == "success") {
        // Swal.fire({
        //   title: "Success",
        //   text: "Sign In successful",
        //   icon: "success",
        // });
        window.location.href = "./dashboard.php";
      } else {
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
          loadChart(jsonResponse[1], jsonResponse[2]);
          loadPie(jsonResponse[3]);
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

  r.open("POST", "./Backend/backend.php", true);
  r.send(f);
}

function loadChart(labelArray, valueArray) {
  var chartOne = document.getElementById("chartOne");
  var myChart = new Chart(chartOne, {
    type: "bar",
    data: {
      labels: labelArray,
      datasets: [
        {
          label: "Number of appointments",
          data: valueArray,
          backgroundColor: [
            "rgba(255, 99, 132, 0.4)",
            "rgba(54, 162, 235, 0.4)",
            "rgba(255, 206, 86, 0.4)",
            "rgba(75, 192, 192, 0.4)",
            "rgba(153, 102, 255, 0.4)",
            "rgba(255, 159, 64, 0.4)",
          ],
          borderColor: [
            "rgba(255, 99, 132, 1)",
            "rgba(54, 162, 235, 1)",
            "rgba(255, 206, 86, 1)",
            "rgba(75, 192, 192, 1)",
            "rgba(153, 102, 255, 1)",
            "rgba(255, 159, 64, 1)",
          ],
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        yAxes: [
          {
            ticks: {
              beginAtZero: true,
            },
          },
        ],
      },
    },
  });
}

function loadPie(valueArray) {
  console.log("Done");
  var chartOne = document.getElementById("chartTwo");
  var myChart = new Chart(chartOne, {
    type: "pie",
    data: {
      labels: ["Accepted", "Declined"],
      datasets: [
        {
          label: "Number of appointments",
          data: valueArray,
          backgroundColor: [
            "rgba(54, 162, 235, 0.4)",
            "rgba(255, 99, 132, 0.4)",
            "rgba(255, 206, 86, 0.4)",
            "rgba(75, 192, 192, 0.4)",
            "rgba(153, 102, 255, 0.4)",
            "rgba(255, 159, 64, 0.4)",
          ],
          borderColor: [
            "rgba(54, 162, 235, 1)",
            "rgba(255, 99, 132, 1)",
            "rgba(255, 206, 86, 1)",
            "rgba(75, 192, 192, 1)",
            "rgba(153, 102, 255, 1)",
            "rgba(255, 159, 64, 1)",
          ],
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        // yAxes: [
        //   {
        //     ticks: {
        //       beginAtZero: false,
        //     },
        //   },
        // ],
      },
    },
  });
}

function signOut() {
  
  console.log("sign out");

  var f = new FormData();
  f.append("act", "signout");

  var r = new XMLHttpRequest();
  r.onreadystatechange = function () {
    if (r.readyState == 4) {
      if (r.responseText == "success") {
        window.location.href = "../Home/";
      } else {
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

// var chartTwo = document.getElementById("chartTwo");
// var myLineChart = new Chart(chartTwo, {
//   type: "line",
//   data: {
//     labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
//     datasets: [
//       {
//         label: "# of Votes",
//         data: [300, 500, 60, 120, 15, 90],
//         backgroundColor: [
//           "rgba(255, 99, 132, 0.2)",
//           "rgba(54, 162, 235, 0.2)",
//           "rgba(255, 206, 86, 0.2)",
//           "rgba(75, 192, 192, 0.2)",
//           "rgba(153, 102, 255, 0.2)",
//           "rgba(255, 159, 64, 0.2)",
//         ],
//         borderColor: [
//           "rgba(255, 99, 132, 1)",
//           "rgba(54, 162, 235, 1)",
//           "rgba(255, 206, 86, 1)",
//           "rgba(75, 192, 192, 1)",
//           "rgba(153, 102, 255, 1)",
//           "rgba(255, 159, 64, 1)",
//         ],
//         borderWidth: 1,
//       },
//     ],
//   },
//   options: {
//     scales: {
//       yAxes: [
//         {
//           ticks: {
//             beginAtZero: true,
//           },
//         },
//       ],
//     },
//   },
// });

function singleview(id) {
  window.location = "single.php?id=" + id;
}

function changePassword(){
  var op = document.getElementById("currPw");
  var np = document.getElementById("nPw");
  var cnp = document.getElementById("cnPw");

  var f = new FormData();
  f.append("act", "changePassword");
  f.append("opw", op);
  f.append("npw", np);
  f.append("cpw", cnp);

  var r = new XMLHttpRequest();
  r.onreadystatechange = function () {
    if (r.readyState == 4) {
      if (r.responseText == "success") {
        window.location.href = "../Home/";
      } else {
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
