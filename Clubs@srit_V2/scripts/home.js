$(document).ready(() => {
  $(".create-club-btn,.plus-icon,.create-btn-txt").click((e) => {
    e.preventDefault();
    window.location.href = "https://clubsatsrit.in/createClub";
  });
  $("#profile-btn").click((e) => {
    e.preventDefault();
    window.location.href = "https://clubsatsrit.in/profile";
  });

  const url = window.location.href;
  // if (url.includes("home")) {
  $.ajax({
    url: "https://clubsatsrit.in/php_scripts/homeitems",
    method: "POST",
    data: {
      home: "all_items",
    },
    beforeSend: function () {
      // setting a timeout
      $("#preloader").show();
    },
    success: function (data) {
      $("#preloader").hide();
      data = JSON.parse(data);
      $(".updates-display")
        .attr("data-clubs", data.Fclubs)
        .attr("data-reqStatus", "completed");
      $("#club-list").prepend(data.clubs);
      $("#profile-btn").attr("src", data.dp);
      $(".greet_name").text("Hi, " + data.fname.split(" ")[0]);

      $(".greet_user").text("Good " + getTimeOfDay() + "!");

      if (data.events != "") {
        $("#event-list").html(data.events);
      } else {
        $(".eve-sec-main").hide();
        // $("#event-list").html(` <center>
        //     <img src="https://img.freepik.com/free-vector/tiny-people-using-appointment-calendar-app-planning-events-male-female-employees-with-online-deadline-reminder-planner-schedule-flat-vector-illustration-time-management-organizer-concept_74855-22071.jpg" width='200' height='200' alt="">
        //     <p class="sub-heading">No Events Yet!</p>
        //   </center>`);
      }
    },
  });

  function getTimeOfDay() {
    var currentTime = new Date();
    var currentHour = currentTime.getHours();

    if (currentHour >= 5 && currentHour < 12) {
      return "Morning";
    } else if (currentHour >= 12 && currentHour < 17) {
      return "Afternoon";
    } else if (currentHour >= 17 && currentHour < 21) {
      return "Evening";
    } else {
      return "Night";
    }
  }

  $("body").on("click", ".club-card", (e) => {
    e.preventDefault();
    let cid = $(e.target).closest(".club-card").attr("data-cid");
    window.location.href = `https://clubsatsrit.in/club?cid=${cid}`;
  });
  $("body").on("click", ".eve-card", (e) => {
    e.preventDefault();
    let eid = $(e.target).closest(".eve-card").attr("data-eid");
    let cname = $(e.target).closest(".eve-card").attr("data-cname");
    let id = $(e.target).closest(".eve-card").attr("data-cid");
    window.location.href = `https://clubsatsrit.in/event?cid=${id}&cname=${cname}&evid=${eid}`;
  });
  $("body").on("click", ".notifi-btn", (e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/notify`;
  });

  $(".home-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/home`;
  });

  if (url.includes("notify")) {
    $.ajax({
      url: "https://clubsatsrit.in/php_scripts/notifications",
      method: "post",
      data: {
        getNotifies: "true",
      },
      success: (data) => {
        console.log(data);
        if (data != 0) {
          $(".notifications-box").html(data);
        }
      },
    });
    $("body").on("click", ".notify-details", (e) => {
      var $url = $(e.target).closest(".notify-details").attr("data-url");
      if ($url != "" && $url != undefined) {
        window.location.href = $url;
      }
    });
  }
});
