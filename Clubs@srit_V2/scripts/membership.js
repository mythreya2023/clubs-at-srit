$(document).ready((e) => {
  const url = window.location.href;

  // Create a URLSearchParams object
  const urlParams = new URLSearchParams(window.location.search);

  // Get the value of the 'id' parameter
  const id = urlParams.get("cid");

  $(".reg-page-back-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/club?cid=${id}`;
  });

  var mgtp = "getMsMembers";
  if (url.includes("clubFollowers")) {
    mgtp = "getMsFollowers";
  }
  $.ajax({
    url: "https://clubsatsrit.in/php_scripts/clubDetails",
    method: "post",
    data: {
      memAct: mgtp,
      cid: id,
    },
    beforeSend: function () {
      // setting a timeout
      $("#preloader").show();
    },
    success: function (data) {
      $("#preloader").hide();
      data = JSON.parse(data);
      if (data.mcount != 0) {
        $(".registered-users").show();
        $(".no-members-div").hide();
        $(".mem-act-count").text(data.mcount);
        $(".registered-users").html(data.membs);
        if ($(".search-user-box").length == 0) {
          $(".remove-member-btn").remove();
        }
      } else {
        $(".mem-act-count").text(0);
        $(".registered-users").hide();
        $(".no-members-div").show();
      }
    },
  });
  $(".registered-users").on("click", ".remove-member-btn", (e) => {
    let uname = $(e.target).attr("data-uname");
    var rmbtn = "removeMember";
    if (url.includes("clubFollowers")) {
      rmbtn = "unFollow";
    }
    $.ajax({
      url: "https://clubsatsrit.in/php_scripts/clubDetails",
      method: "post",
      data: {
        memAct: rmbtn,
        cid: id,
        uname: uname,
      },
      success: (data) => {
        console.log(data);
        if (data != 0) {
          $(e.target).closest(".team-member").remove();
          alert("Member removed successfully.");
        } else {
          alert("Failed to remove member. Try again");
        }
      },
    });
  });
  $(".add-btn").click((e) => {
    let uname = $(".us-search-ipt").val().trim();
    if (uname != "") {
      $.ajax({
        url: "https://clubsatsrit.in/php_scripts/clubDetails",
        method: "post",
        data: {
          memAct: "addMember",
          cid: id,
          uname: uname,
        },
        success: (data) => {
          console.log(data);
          if (data == "noUser") {
            alert("No user found! Check once.");
          } else {
            if (data != 0) {
              alert("Member added successfully.");
            } else {
              alert("Failed to add member. Try again");
            }
          }
        },
      });
    }
  });
});
