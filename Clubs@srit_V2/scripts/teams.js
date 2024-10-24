$(document).ready(() => {
  const url = window.location.href;

  // Create a URLSearchParams object
  const urlParams = new URLSearchParams(window.location.search);

  // Get the value of the 'id' parameter
  const id = urlParams.get("cid");

  $(".team-page-back-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/club?cid=${id}`;
  });
  $(".open-add-member-btn").click(() => {
    $(".add-team-member-container,.user-search-box").show();
    $(".team-members-container").hide();
    $(".searched-user-det").hide();
    $(".us-search-ipt").val("");
    $(".us-srch-name").text("");
    $(".us-srch-rollno").text("").attr("data-uid", "");
    $("#searched-us-img").attr("src", "");
    $(`input[name="role-radio"]`).prop("checked", false);
    $(".new_mem_heading").text("Add Member");
    $(".update-team-member-btn").hide();
    $(".add-team-member-btn").show();
  });
  $(".team-back-btn").click(() => {
    $(".add-team-member-container").hide();
    $(".team-members-container").show();
  });

  $(".team-members").on("click", ".mem-details", (e) => {
    $(".user-search-box").hide();
    $(".new_mem_heading").text("Update Member");
    $(".update-team-member-btn").show();
    $(".add-team-member-btn").hide();
    let userName = $(e.target).closest(".mem-details").attr("data-mem_id");
    $(".us-search-ipt").val(userName);
    $(".us-srch-name").text($(e.target).closest(".mem-name").text());
    $(".us-srch-rollno")
      .text(userName)
      .attr("data-uid", $(e.target).closest(".mem-details").attr("data-uid"));
    $("#searched-us-img").attr(
      "src",
      $(e.target).closest(".mem-details").find(".userImage").attr("src")
    );
    let role = $(e.target).closest(".mem-details").find(".mem-role").text();
    $(`input[name="role-radio"][value="${role}"]`).prop("checked", true);

    $(".searched-user-det").show();

    $(".add-team-member-container").show();
    $(".team-members-container").hide();
  });
  $(".team-studs").on("click", ".text-btn-remove", (e) => {
    let sid = $(e.target).attr("data-uid");
    let exid = $(e.target).attr("data-exeid");
    $.ajax({
      url: "https://clubsatsrit.in/php_scripts/clubDetails",
      method: "POST",
      data: {
        remTM: "true",
        mid: sid,
        exId: exid,
        cid: id,
      },
      beforeSend: function () {
        // setting a timeout
        $("#preloader").show();
      },
      success: function (data) {
        $("#preloader").hide();
        if (data == 1) {
          $(e.target).parent(".team-member").remove();
        }
      },
    });
  });
  $(".search-icon").click(() => {
    let usname = $(".us-search-ipt").val();
    $.ajax({
      url: "https://clubsatsrit.in/php_scripts/clubDetails",
      method: "POST",
      data: {
        action: "setStudentAdminRole",
        rollno: usname,
      },
      success: function (data) {
        if (data != 0) {
          data = JSON.parse(data);
          $(".searched-user-det").show();
          $(".us-srch-name").text(data.name);
          $(".us-srch-rollno").text(usname).attr("data-uid", data.uid);
          $("#searched-us-img").attr("src", data.dp);
        } else {
          $(".searched-user-det").hide();
          $(".us-srch-name").text("");
          $(".us-srch-rollno").text("").attr("data-uid", "");
          $("#searched-us-img").attr("src", "");
          alert("User not found.");
        }
      },
    });
  });
  $(".add-team-member-btn").click(() => {
    let img = $("#searched-us-img").attr("src");
    let mem_name = $(".us-srch-name").text();
    let sid = $(".us-srch-rollno").attr("data-uid");
    let rol = $('input[name="role-radio"]:checked').val();
    if (rol != undefined && mem_name != "" && sid != "") {
      $.ajax({
        url: "https://clubsatsrit.in/php_scripts/clubDetails",
        method: "POST",
        data: {
          addTM: "true",
          mid: sid,
          cid: id,
          rolle: rol,
        },
        beforeSend: function () {
          // setting a timeout
          $("#preloader").show();
        },
        success: function (data) {
          $("#preloader").hide();
          console.log(data);
          if (data == 1) {
            $(".team-studs").append(`
            <div class="team-member">
              <div class="mem-details" data-mem_id="${id}">
                <img
                  src="${img}"
                  alt=""
                  class="img-sq userImage"
                />
                <div class="mem-txt-det">
                  <p class="mem-name">${mem_name}</p>
                  <p class="mem-role">${rol}</p>
                </div>
              </div>
              <div class="text-btn-remove animi-btn">Remove</div>
            </div>
            `);
            $(".team-back-btn").click();
          } else {
            alert("This member is already a part of the team");
          }
        },
      });
    } else {
      alert("Select a valid user and his/her role.");
    }
  });
  $(".update-team-member-btn").click(() => {
    let sid = $(".us-srch-rollno").attr("data-uid");
    let rol = $('input[name="role-radio"]:checked').val();
    if (rol != undefined && sid != "") {
      $.ajax({
        url: "https://clubsatsrit.in/php_scripts/clubDetails",
        method: "POST",
        data: {
          updateTM: "true",
          mid: sid,
          cid: id,
          rolle: rol,
        },
        beforeSend: function () {
          // setting a timeout
          $("#preloader").show();
        },
        success: function (data) {
          $("#preloader").hide();
          if (data == 1) {
            $(`.mem-details[data-uid='${sid}']`).find(".mem-role").text(rol);
            $(".team-back-btn").click();
          } else {
            alert("Unable to update! Try again later.");
          }
        },
      });
    } else {
      alert("Select a valid user and his/her role.");
    }
  });
  getRoles(id);
  getTeamMembers(id);
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
        data = JSON.parse(data);
        data.forEach((dat) => {
          if (dat.member_type != "member") {
            $(".team-faculty").append(`
        <div class="team-member">
        <div class="mem-details" data-mem_id="${dat.usname}" data-uid='${
              dat.uid
            }'>
          <img
            src="${dat.dp}"
            alt=""
            class="img-sq userImage"
          />
          <div class="mem-txt-det">
            <p class="mem-name">${dat.name}</p>
            <p class="mem-role">${dat.t_role}</p>
          </div>
        </div>
          <div
          style="
            color: #504747;
            text-align: center;
            font-family: Inter;
            font-size: 15px;
            font-style: normal;
            font-weight: 600;
            line-height: normal;
            margin-right: 18px;
          "
        >
          ${dat.member_type == "fac" ? "Faculty" : "Admin"}
        </div>
        </div>
        `);
          } else {
            $(".team-studs").append(`
        <div class="team-member">
        <div class="mem-details" data-mem_id="${dat.usname}" data-uid='${dat.uid}'>
          <img
            src="${dat.dp}"
            alt=""
            class="img-sq userImage"
          />
          <div class="mem-txt-det">
            <p class="mem-name">${dat.name}</p>
            <p class="mem-role">${dat.t_role}</p>
          </div>
        </div>
        <div class="text-btn-remove animi-btn" data-exeId='${dat.exe_id}' data-uid='${dat.uid}'>Remove</div>
      </div>
        `);
          }
        });
      }
    },
  });
}
function getRoles(id) {
  $.ajax({
    url: "https://clubsatsrit.in/php_scripts/clubDetails",
    method: "POST",
    data: {
      roles: "true",
      cid: id,
    },
    beforeSend: function () {
      // setting a timeout
      $("#preloader").show();
    },
    success: function (data) {
      $("#preloader").hide();
      console.log(data);
      if (data != "") {
        let roles = data.split(",");
        $(".role-selection-box").html("");
        roles.forEach((role) => {
          $(".role-selection-box").append(`
            <div class="select-role">
              <label class="radio-label">
                <input
                  type="radio"
                  name="role-radio"
                  value="${role}"
                  class="radio-input"
                />
                <span class="radio-text">${role}</span>
              </label>
            </div>
        `);
        });
      }
    },
  });
}
