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

  // Specify the dates to highlight
  var highlightedDates = [
    moment("2024-08-10"),
    moment("2024-08-15"),
    moment("2024-08-20"),
  ];

  $("#calendar").datetimepicker({
    inline: true,
    format: "L",
    // Customize day rendering to highlight certain dates
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
    daysOfWeekHighlighted: [0, 6], // Highlight weekends (optional)
    tooltips: {
      today: "Go to today",
      clear: "Clear selection",
      close: "Close the picker",
    },
  });

  // Highlight specific dates
  highlightedDates.forEach(function (date) {
    var currentDate = $("#calendar").find(`[data-day="${date.format("L")}"]`);
    currentDate.addClass("highlighted");
  });

  // Alert the selected date
  $("#calendar").on("change.datetimepicker", function (event) {
    alert("Selected date: " + event.date.format("L"));
  });
})(jQuery);
