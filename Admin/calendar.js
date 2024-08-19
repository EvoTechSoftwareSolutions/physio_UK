(function ($) {
  "use strict";

  // Spinner
  var spinner = function () {
    setTimeout(function () {
      if ($("#spinner").length > 0) {
        $("#spinner").removeClass("show");
      }
    }, 1);
  };
  spinner();

  // Sidebar Toggler
  $(".sidebar-toggler").click(function () {
    $(".sidebar, .content").toggleClass("open");
    return false;
  });

  // Specify the dates to highlight in YYYY-MM-DD format
  var highlightedDates = ["2024-08-05", "2024-08-15", "2024-08-20"];

  $("#calendar").datetimepicker({
    inline: true,
    format: "L",
    icons: {
      time: "fa fa-time",
      date: "fa fa-calendar",
      up: "fa fa-chevron-up",
      down: "fa fa-chevron-down",
      previous: "fa fa-chevron-left",
      next: "fa fa-chevron-right",
      today: "fa fa-crosshairs",
      clear: "fa fa-trash",
      close: "fa fa-times",
    },
    tooltips: {
      today: "Go to today",
      clear: "Clear selection",
      close: "Close the picker",
    },
  });

  // Highlight specific dates
  $("#calendar").on("dp.update", function (e) {
    $(".day").each(function () {
      var currentDate = $(this).data("day");
      if (highlightedDates.includes(currentDate)) {
        $(this).addClass("highlighted-date");
      }
    });
  });

  // Alert the selected date
  $("#calendar").on("change.datetimepicker", function (event) {
    // Extract day, month, and year using Moment.js methods
    var day = event.date.date(); // Get the day of the month
    var month = event.date.month(); // Get the month (0-indexed)
    var year = event.date.year(); // Get the full year

    // Call the function with the extracted date components
    showDateDetails(day, month, year);
  });

  var currDateReq = null;

  function showDateDetails(date, month, year) {
    console.log("received");
    document.getElementById("appointments").innerHTML = "&nbsp;";
    document.getElementById("apptLdr").style.display = "inline-block";
    if(r != null){
      r.abort();
    }
    var number = month + 1;
    var r = new XMLHttpRequest();
    currDateReq = r;
    r.onreadystatechange = function () {
      if (r.readyState == 4) {
        console.log("data received");
        var content = "No Appointments";
        var jsonObj = "";
        if (isValidJSON(r.responseText)) {
          console.log("JSON valid");
          jsonObj = JSON.parse(r.responseText);
          console.log("Parsed JSON:", jsonObj);
          if (jsonObj.status == "success") {
            // Corrected property name
            console.log("init Gen");
            content = generateHTML(jsonObj.records);
            console.log("set to render");
          } else {
            content = jsonObj.message;
          }
        } else {
          console.log("Error: " + r.responseText);
        }
        document.getElementById("apptLdr").style.display = "none";
        console.log(document.getElementById("appointments")); // Should not be null
        document.getElementById("appointments").innerHTML = content;
      }
    };
    r.open("POST", "../Backend/backend.php", true);
    r.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    r.send(
      `act=getSchedule&date=${date.toString().padStart(2, "0")}-${number
        .toString()
        .padStart(2, "0")}-${year}`
    );
  }

  function isValidJSON(jsonString) {
    try {
      JSON.parse(jsonString);
      return true;
    } catch (e) {
      return false;
    }
  }

  function generateHTML(data) {
    let htmlString = "";
    console.log("generating content");
    console.log("Number of records:", data.length);
    data.forEach((record) => {
      htmlString += `
      <div class="row ms-5" onclick="singleview(${record.id});">
          <span class="fs-5 text-white"> ${record.name}</span>
          <span>${record.date}</span>
          <span> ${record.time}</span>
      </div>
      <hr>`;
    });
    console.log("Generated HTML:", htmlString);
    return htmlString;
  }
})(jQuery);
