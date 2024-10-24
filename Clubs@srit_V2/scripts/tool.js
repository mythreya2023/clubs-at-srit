$("documnt").ready(() => {
  $("#preloader").hide();
  const url = window.location.href;

  // Create a URLSearchParams object
  const urlParams = new URLSearchParams(window.location.search);

  // Get the value of the 'id' parameter
  const id = urlParams.get("cid");
  const eid = urlParams.get("evid");
  let cname = urlParams.get("cname");

  $("#reg-nav-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/registrations?cid=${id}&cname=${cname}&evid=${eid}`;
  });
  $(".reg-page-back-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/tools?cid=${id}&cname=${cname}&evid=${eid}`;
  });
  $("#create-vote-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/createVoting?cid=${id}&cname=${cname}&evid=${eid}`;
  });
  $("#vote-nav-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/votingDetails?cid=${id}&cname=${cname}&evid=${eid}`;
  });
  $("#gen-rep-nav-btn").click((e) => {
    e.preventDefault();
    let cordName = "";
    if ((cordName = prompt("Enter Event Coordinatiors:"))) {
      window.location.href = `https://clubsatsrit.in/event_report?cid=${id}&cname=${cname}&evid=${eid}&cord=${cordName}`;
    }
  });
  $(".tools-page-back-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/event?cid=${id}&cname=${cname}&evid=${eid}`;
  });
  if (url.includes("tools")) {
    $.ajax({
      url: "https://clubsatsrit.in/php_scripts/eveDetails",
      method: "POST",
      data: {
        actn: "fetchAttendCode",
        cid: id,
        eid: eid,
      },
      success: (data) => {
        console.log(data);
        if (data != 0) {
          $(".att-code-display").text(data);

          $("#att-code-gen-btn").hide();
        }
      },
    });
    $("#att-code-gen-btn").click((e) => {
      $.ajax({
        url: "https://clubsatsrit.in/php_scripts/eveDetails",
        method: "POST",
        data: {
          Code_actn: "genAttendCode",
          cid: id,
          eid: eid,
        },
        beforeSend: function () {
          // setting a timeout
          $("#preloader").show();
        },
        success: function (data) {
          $("#preloader").hide();
          if (data != 0) {
            $(".att-code-display").text(data);

            $("#att-code-gen-btn").hide();
          } else {
            alert("Failed to generate code. Try again.");
          }
        },
      });
    });
  }
  if (url.includes("registrations")) {
    getRegCount(eid);
    var offset = 0;
    var stopScroll = false;
    let callback = (sc_st) => {
      if (sc_st) {
        stopScroll = true;
      } else {
        stopScroll = false;
        offset += 10;
      }
      console.log(sc_st, offset);
    };
    registerations(eid, "", offset);
    $(window).scroll(function () {
      if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
        if (stopScroll == false) {
          let srch = $(".us-search-ipt").val().trim();
          registerations(eid, srch, offset, callback);
        }
      }
    });
    $(".search-icon").click((e) => {
      let srch = $(".us-search-ipt").val();
      if (srch.trim() != "") {
        offset = 0;
        registerations(eid, srch, offset, callback);
      }
    });
    $(".us-search-ipt").keyup((e) => {
      offset = 0;
      let srch = $(".us-search-ipt").val();

      // if (srch.trim() != "") {
      registerations(eid, srch, offset, callback);
      // }
    });
  }
});
function getRegCount(eid) {
  $.ajax({
    url: "https://clubsatsrit.in/php_scripts/eveDetails",
    method: "POST",
    data: {
      getRegAtCount: "true",
      evid: eid,
    },
    beforeSend: function () {
      // setting a timeout
      $("#preloader").show();
    },
    success: function (data) {
      $("#preloader").hide();
      data = JSON.parse(data);
      $(".atte-act-count").text(data.att);
      $(".reg-act-count").text(data.reg);
    },
  });
}
function registerations(eid, person, offset, callback = () => {}) {
  var scrollStop = false;
  $.ajax({
    url: "https://clubsatsrit.in/php_scripts/eveDetails",
    method: "POST",
    data: {
      getRegistered: "true",
      evid: eid,
      srch: person,
      offset: offset,
    },
    beforeSend: function () {
      // setting a timeout
      $("#preloader").show();
    },
    success: function (data) {
      $("#preloader").hide();
      if (data != 0) {
        scrollStop = false;
        data = JSON.parse(data);
        data.forEach((data, idx) => {
          let child = `
          <div class="team-member reg-user">
          <div class="mem-details">
            <img
              src="${data.dp}"
              alt=""
              class="img-sq userImage"
            />
            <div class="mem-txt-det">
              <p class="mem-name">${data.name}</p>
              <p class="mem-role">${data.u_name}</p>
            </div>
          </div>
          <div
            class="eve-att-status"
            style="
              color: #504747;
              text-align: center;
              font-family: Inter;
              font-size: 15px;
              font-style: normal;
              font-weight: 400;
              line-height: normal;
              margin-right: 18px;
            "
          >
            ${data.status}
          </div>
        </div>
          `;
          if (offset == 0 && idx == 0) {
            $(".registered-users").html(child);
          } else {
            $(".registered-users").append(child);
          }
        });
      } else {
        scrollStop = true;
        if (person != "" && offset == 0) {
          $(".registered-users").html(
            "<center><p style='color:gray;font-weight:bold;font-size:20px;margin:auto;'>No User Found!</p></center>"
          );
        }
      }
      callback(scrollStop);
    },
  });
  return scrollStop;
}
