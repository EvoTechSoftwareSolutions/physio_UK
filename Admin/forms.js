function promptTimeslot(id) {
  Swal.fire({
    title: 'Select a timeslot',
    html: `
      <input type="time" id="timeslot" class="swal2-input" placeholder="Select time">
      <div id="time-picker"></div>
    `,
    showCancelButton: true,
    confirmButtonText: 'Submit',
    cancelButtonText: 'Cancel',
    preConfirm: () => {
      const timeslot = document.querySelector('#timeslot').value;
      if (!timeslot) {
        Swal.showValidationMessage('Please select a timeslot');
        return false;
      }
      return { timeslot };
    },
    didOpen: () => {
      // Initialize flatpickr
      flatpickr('#time-picker', {
        enableTime: true,
        noCalendar: true,
        dateFormat: 'H:i',
        defaultHour: 9,
        defaultMinute: 0
      });
      // Sync the flatpickr value with the input field
      document.querySelector('#time-picker').addEventListener('change', function (e) {
        document.querySelector('#timeslot').value = e.target.value;
      });
    }
  }).then((result) => {
    if (result.isConfirmed) {
      const timeslot = result.value.timeslot;
      accept(id, timeslot);
    }
  });
}

function accept(id, timeslot) {
  console.log(timeslot);
  var f = new FormData();
  f.append("id", id);
  f.append("act", "acceptAppt");
  f.append("timeslot", timeslot);

  var r = new XMLHttpRequest();

  r.onreadystatechange = function () {
    if (r.readyState == 4) {
      if (r.responseText == "Success") {
        Swal.fire({
          title: "Accepted",
          text: "Appointment accepted successfully",
          icon: "success",
        });
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      } else {
        Swal.fire({
          title: "Error",
          text: r.responseText,
          icon: "warning",
        });
      }
    }
  };

  r.open("POST", "../Backend/backend.php", true);
  r.send(f);
}

function decline(id){
  event.preventDefault();
  // alert("Request " + id + " accepted");
  var f = new FormData();
  f.append("id", id);
  f.append("act","declineAppt");

  var r = new XMLHttpRequest();

  r.onreadystatechange = function () {
    if (r.readyState == 4) {
      setTimeout(() => {
        window.location.href = "single.php?id="+id+"#bottom";
      }, 5000);
      if (r.responseText == "Success") {
        Swal.fire({
          title: "Declined",
          text: "Appointment declined successfully",
          icon: "success",
        });
      } else {
        Swal.fire({
          title: "Error",
          text: r.responseText,
          icon: "error",
        });
      }
    }
  };

  r.open("POST", "../Backend/backend.php", true);
  r.send(f);
}

function declineQuick(id){
  event.preventDefault();
  // alert("Request " + id + " accepted");
  var f = new FormData();
  f.append("id", id);
  f.append("act","declineAppt");

  var r = new XMLHttpRequest();

  r.onreadystatechange = function () {
    if (r.readyState == 4) {
      setTimeout(() => {
        window.location.reload();
      }, 5000);
      if (r.responseText == "Success") {
        Swal.fire({
          title: "Declined",
          text: "Appointment declined successfully",
          icon: "success",
        });
      } else {
        Swal.fire({
          title: "Error",
          text: r.responseText,
          icon: "error",
        });
      }
    }
  };

  r.open("POST", "../Backend/backend.php", true);
  r.send(f);
}