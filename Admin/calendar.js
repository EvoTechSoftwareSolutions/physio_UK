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
    alert("Selected date: " + event.date.format("L"));
  });
})(jQuery);
