var cname = "";
$(document).ready(() => {
  $(".updates-btn").click(() => {
    $(".events-btn")
      .css("background", "transparent")
      .css("border", "1px solid rgba(158, 141, 141, 0.38)");
    $(".updates-btn").css("background", "#CDE4E9").css("border", "none");
    $(".events-tab").hide();
    $(".updates-tab").show();
  });
  $(".events-btn").click(() => {
    $(".updates-btn")
      .css("background", "transparent")
      .css("border", "1px solid rgba(158, 141, 141, 0.38)");
    $(".events-btn").css("background", "#CDE4E9").css("border", "none");
    $(".updates-tab").hide();
    $(".events-tab").show();
  });
  $(".club-details-btn").click(() => {
    $(".club-esse-display").hide();
    $(".club-details-container").show();
  });
  $(".club-pg-btn").click(() => {
    $(".club-details-container").hide();
    $(".club-esse-display").show();
  });
  const url = window.location.href;

  // Create a URLSearchParams object
  const urlParams = new URLSearchParams(window.location.search);

  // Get the value of the 'id' parameter
  const id = urlParams.get("cid");
  getTeamMembers(id);
  $.ajax({
    url: "https://clubsatsrit.in/php_scripts/clubDetails",
    method: "POST",
    data: {
      cd: "clubDetails",
      cid: id,
    },
    success: function (data) {
      console.log(data);
      if (data != "") {
        var replacer = (data) => {
          while (true) {
            if (
              data.includes("&lt;br&gt;") ||
              data.includes("&lt;div&gt;") ||
              data.includes("&lt;span&gt;") ||
              data.includes("&lt;/div&gt;") ||
              data.includes("&lt;/span&gt;")
            ) {
              data = data
                .replace("&lt;br&gt;", "<br>")
                .replace("&lt;div&gt;", "<div>")
                .replace("&lt;span&gt;", "<span>")
                .replace("&lt;/div&gt;", "</div>")
                .replace("&lt;/span&gt;", "</span>");
            } else {
              break;
            }
          }
          return data;
        };
        data = JSON.parse(data);
        cname = data.name;
        $(".about-txt").html(replacer(data.about_c));
        $(".club_name").text(data.name);
        $(".club-page-img").attr("src", data.img);
        $("#follower-count").text(data.followers);
        if (data.isMs == 1) {
          $(".membership").show();
          $(".mship-members-count").text(data.memCount);
        } else {
          $(".membership").hide();
        }
        if (data.following == 1) {
          $(".follow-btn-blue").hide();
          $("#following-btn").show();
        } else {
          $(".follow-btn-blue").show();
          $("#following-btn").hide();
        }
      }
    },
  });
  $(".go-back-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/home`;
  });
  $(".about-edit-btn").click((e) => {
    $(".about-txt")
      .attr("contentEditable", "true")
      .css("border", "2px solid gray")
      .css("border-radius", "6px")
      .css("padding", "10px");
    $(".save-about-txt").show();
    $(".about-edit-btn").hide();
  });

  $(".save-about-txt").click((e) => {
    $(".about-txt")
      .attr("contentEditable", "false")
      .css("border", "none")
      .css("border", "none")
      .css("padding", "0");
    var content = $(".about-txt").html();
    $.ajax({
      url: "https://clubsatsrit.in/php_scripts/clubDetails",
      method: "POST",
      data: {
        action: "update-about",
        cid: id,
        text: content,
      },
      beforeSend: function () {
        // setting a timeout
        $("#preloader").show();
      },
      success: function (data) {
        $("#preloader").hide();
        if (data == 1) {
          $(".save-about-txt").hide();
          $(".about-edit-btn").show();
        } else {
          alert("Please Try again later");
        }
      },
    });
  });
  $(".exe-team-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/team?cid=${id}`;
  });
  $(".membership-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/membership?cid=${id}`;
  });
  $(".club-edit-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/createClub?cid=${id}`;
  });
  $(".create-event-btn").click((e) => {
    e.preventDefault();
    cname = cname.replace(" ", "_");
    window.location.href = `https://clubsatsrit.in/eventDetails?cid=${id}&cname=${cname}`;
  });
  $(".followers").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/clubFollowers?cid=${id}`;
  });
  $(".post-update-btn").click((e) => {
    e.preventDefault();
    cname = cname.replace(" ", "_");
    window.location.href = `https://clubsatsrit.in/postupdate?cid=${id}&cname=${cname}`;
  });
  $(".follow-btn-blue").click((e) => {
    $.ajax({
      url: "https://clubsatsrit.in/php_scripts/clubDetails",
      method: "POST",
      data: {
        follow: "true",
        cid: id,
      },
      success: function (data) {
        if (data == 1) {
          let count = parseInt($("#follower-count").text());
          count += 1;
          console.log(count);
          $("#follower-count").text(count);
          $(".follow-btn-blue").hide();
          $("#following-btn").show();
        } else {
          alert("Please Try again later");
        }
      },
    });
  });
  $("#following-btn").click((e) => {
    $.ajax({
      url: "https://clubsatsrit.in/php_scripts/clubDetails",
      method: "POST",
      data: {
        unfollow: "true",
        cid: id,
      },
      success: function (data) {
        if (data == 1) {
          let count = parseInt($("#follower-count").text());
          count -= 1;
          console.log(count);
          $("#follower-count").text(count--);
          $("#following-btn").hide();
          $(".follow-btn-blue").show();
        } else {
          alert("Please Try again later");
        }
      },
    });
  });
  getEvents(id);
  $(".events-display").on("click", ".eve-card", (e) => {
    e.preventDefault();
    let eid = $(e.target).closest(".eve-card").attr("data-eid");
    let cname = $(e.target).closest(".eve-card").attr("data-cname");
    window.location.href = `https://clubsatsrit.in/event?cid=${id}&cname=${cname}&evid=${eid}`;
  });
});
function getTeamMembers(id) {
  $.ajax({
    url: "https://clubsatsrit.in/php_scripts/clubDetails",
    method: "POST",
    data: {
      getTeam: "true",
      cid: id,
    },
    success: function (data) {
      console.log(data);
      if (data != "") {
        $(".team-members").html("");
        data = JSON.parse(data);
        data.forEach((dat) => {
          if (dat.member_type != "member") {
            $(".team-members").prepend(`
            <div class="member">
            <div style="
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-content: center;
            align-items: center;">
                <img
                  src="${dat.dp}"
                  alt="${dat.name}"
                  class="img-round"
                />
                <div class='member-name' style="
                background: #808080f5;
                background: linear-gradient(0deg, #767070, transparent);
                color: white;
                position: relative;
                top: -17px;
                border-radius: 0 0px 100px 100px;
                padding: 3px;
                font-size: 9px;
                width: 47px;">${
                  dat.member_type == "fac" ? "Faculty" : "Admin"
                }</div>
            </div>
            <div style="margin-top:-10px">
            <div class="member-name">${dat.name}</div>
            <div class="member-name" style="
            font-weight: 400;
            color: darkslategray;
            margin-top: 3px;">${dat.t_role}</div>
            </div>
          </div>
        `);
          } else {
            $(".team-members").append(`
            <div class="member">
            <img
              src="${dat.dp}"
              alt="${dat.name}"
              class="img-round"
            />
            <div >
            <div class="member-name">${dat.name}</div>
            <div class="member-name" style="
            font-weight: 400;
            color: darkslategray;
            margin-top: 3px;">${dat.t_role}</div>
          </div>
        `);
          }
        });
      }
    },
  });
}
function getEvents(id) {
  $.ajax({
    url: "https://clubsatsrit.in/php_scripts/eveDetails",
    method: "POST",
    data: {
      getEvents: "true",
      cid: id,
    },
    beforeSend: function () {
      // setting a timeout
      $("#preloader").show();
    },
    success: function (data) {
      $("#preloader").hide();
      data = JSON.parse(data);
      if (data != "") {
        $(".events-display").html("");
        let colors = ["#f9c9a6", "#c2bcff", "#B2ECF4", "#eae699"];
        let i = 0;
        data.forEach((data) => {
          $(".events-display").append(`
              <div class="eve-card animi-btn" style='background:${colors[i]}' data-eid='${data.eid}' data-cname='${data.cname}'>
              <img src="${data.img}" class="eve-img" />
              <div class="eve-text">
                <h3 class="eve-card-name">${data.ename}</h3>
                <p class="eve-card-by">by ${data.cname}</p>
              </div>
            </div>
          `);
          if (i >= 3) {
            i = 0;
          } else {
            i++;
          }
        });
      } else {
        $(".events-display").html(`<center style="margin-top:70px;">
            <img src="https://img.freepik.com/free-vector/colleagues-preparing-corporate-party-time-management-deadline-brand-event-event-brand-management-sponsored-event-organization-concept_335657-120.jpg?size=626&ext=jpg&ga=GA1.1.1141335507.1718323200&semt=ais_user" width='300' height='200' alt="">
            <p class="sub-heading" style="color:gray;">No Events Yet!</p>
          </center>`);
      }
    },
  });
}
