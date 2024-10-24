var selectedBranch = [],
  selectedYear = [];
$(document).ready(() => {
  document.addEventListener("DOMContentLoaded", function () {
    var editableDiv = document.querySelector(".editableDiv");

    editableDiv.addEventListener("focus", function () {
      if (this.textContent.trim() === "") {
        this.innerHTML = "";
      }
    });

    editableDiv.addEventListener("blur", function () {
      if (this.textContent.trim() === "") {
        this.innerHTML =
          '<span style="color: grey;">' +
          this.getAttribute("data-placeholder") +
          "</span>";
      }
    });
  });

  const url = window.location.href;

  // Create a URLSearchParams object
  const urlParams = new URLSearchParams(window.location.search);

  // Get the value of the 'id' parameter
  const id = urlParams.get("cid");
  const eid = urlParams.get("evid");
  let cname = urlParams.get("cname");

  $(".back-btn").click((e) => {
    e.preventDefault();
    if (eid == null) {
      window.location.href = `https://clubsatsrit.in/club?cid=${id}`;
    } else {
      window.location.href = `https://clubsatsrit.in/event?cid=${id}&cname=${cname}&evid=${eid}`;
    }
  });
  $(".page-back-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/club?cid=${id}`;
  });

  $("#vote-eve-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/voting?cid=${id}&evid=${eid}`;
  });

  $(".custom-checkbox").click(() => {
    if ($("#cta").is(":checked")) {
      $("#CTA-link").show();
      $(".cta-checked").show();
    } else {
      $("#CTA-link").hide();
      $(".cta-checked").hide();
    }
  });
  $(".checkbox-btn,.btn-check").click(function () {
    selectedYear = [];
    selectedBranch = [];
    $('input[name="year-cb"]:checked').each(function () {
      selectedYear.push($(this).val());
    });
    $('input[name="branch_selec"]:checked').each(function () {
      selectedBranch.push($(this).val());
    });
  });
  if (cname != null) {
    cname = cname.replace("_", " ");
  }

  var form_data = "";
  function resizeImage(file, maxWidth, maxHeight, callback) {
    var img = new Image();
    img.onload = function () {
      var width = img.width;
      var height = img.height;

      // Calculate the new dimensions
      if (width > height) {
        if (width > maxWidth) {
          height *= maxWidth / width;
          width = maxWidth;
        }
      } else {
        if (height > maxHeight) {
          width *= maxHeight / height;
          height = maxHeight;
        }
      }

      // Create a canvas element
      var canvas = document.createElement("canvas");
      canvas.width = width;
      canvas.height = height;

      // Resize the image on the canvas
      var ctx = canvas.getContext("2d");
      ctx.drawImage(img, 0, 0, width, height);

      // Callback with the resized image data URL
      canvas.toBlob(function (blob) {
        let name = generateUniqueFileName() + ".jpeg";
        let property = new File([blob], name, {
          type: "image/jpeg",
          lastModified: new Date(),
        });
        callback(property);
      }, "image/jpeg");
    };
    img.src = URL.createObjectURL(file);
  }
  function generateUniqueFileName() {
    // Generate a random string
    var randomString = Math.random().toString(36).substring(2, 15);

    // Get the current timestamp
    var timestamp = new Date().getTime();

    // Combine the random string with the timestamp
    var uniqueFileName = randomString + "_" + timestamp;

    return uniqueFileName;
  }
  $(document).on("change", "#fileInput1", function (e) {
    var property = document.querySelector("#fileInput1").files[0];

    resizeImage(property, 800, 800, (e) => {
      property = e;
      console.log(property);
      imageUpload(property);
    });
    function imageUpload(property) {
      var image_name = property.name;
      var extension = image_name.split(".").pop().toLowerCase();
      if ($.inArray(extension, ["png", "jpeg", "jpg"]) == -1) {
        alert("Invalid File");
      } else {
        var img_size = property.size;
        if (img_size > 1000000) {
          alert("File is too big");
        } else {
          var reader = new FileReader();
          reader.onload = function (e) {
            let blob = e.target.result;

            $(".img-area").css("background-image", `url('${blob}')`);
          };
          reader.readAsDataURL(property);
          let preImg = $(".eve-feat-img").attr("src");

          if (preImg == "" || preImg == undefined || preImg == null)
            preImg = "";
          else preImg = preImg.split("https://clubsatsrit.in/")[1];
          form_data = new FormData();
          form_data.append("image", property);
          form_data.append("createType", "event");
          form_data.append("preImg", preImg);
          console.log(form_data);
        }
      }
    }
  });
  function eve_C_U(form_data, action) {
    if (form_data != "") {
      $.ajax({
        url: "https://clubsatsrit.in/php_scripts/addImg_c",
        type: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
          console.log(data);
          $(".eve-feat-img").attr("src", data);
          $(".img-area").css("background-image", `url('${data}')`);
          eventDetails(id, cname, action, eid);
        },
      });
    } else {
      eventDetails(id, cname, action, eid);
    }
  }
  $(".create-eve-btn").click(() => eve_C_U(form_data, "create"));
  $(".update-eve-btn").click(() => eve_C_U(form_data, "update"));
  $(".back-btn").click((e) => {
    e.preventDefault();
    window.history.back;
  });
  if (eid != null) {
    getEveDetails(id, eid, url);
  }
  $("#reg-eve-btn").click((e) => {
    e.preventDefault();
    // window.location.href = $(e.target).attr("data-ref");
    registerToEvent(eid, $(e.target).attr("data-ref"));
  });
  $("#unreg-eve-btn").click((e) => {
    e.preventDefault();
    unRegisterToEvent(eid);
  });
  $(".edit-eve-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/eventDetails?cid=${id}&cname=${cname}&evid=${eid}`;
  });
  $(".eve-tools-btn").click((e) => {
    e.preventDefault();
    window.location.href = `https://clubsatsrit.in/tools?cid=${id}&cname=${cname}&evid=${eid}`;
  });
  $("#attend-eve-btn").click((e) => {
    e.preventDefault();
    let code = $("#attCode-ipt").val();
    if (code.trim() != "") {
      $.ajax({
        url: "https://clubsatsrit.in/php_scripts/eveDetails",
        method: "POST",
        data: {
          actn: "checkAttendCode",
          code: code,
          cid: id,
          eid: eid,
        },
        success: (data) => {
          console.log(data);
          if (data == "done") {
            alert("You have successfully attended the event!");
            $(".attCode-sec,#reg-eve-btn,#unreg-eve-btn").hide();
          } else if (data == "missMatch") {
            alert("Check code once. Code miss matched.");
          }
        },
      });
    }
  });
});
function registerToEvent(eid, ref) {
  $.ajax({
    url: "https://clubsatsrit.in/php_scripts/eveDetails",
    method: "post",
    data: {
      registerUser: "true",
      evid: eid,
    },
    success: (data) => {
      console.log(data);
      if (data == 1) {
        alert("Registered Successfully!");

        $("#reg-eve-btn").hide();
        $("#unreg-eve-btn").show();
        window.location.href = ref;
      } else {
        alert("Failed to Register!");
      }
    },
  });
}
function unRegisterToEvent(eid) {
  $.ajax({
    url: "https://clubsatsrit.in/php_scripts/eveDetails",
    method: "post",
    data: {
      unRegisterUser: "true",
      evid: eid,
    },
    success: (data) => {
      console.log(data);
      if (data == 1) {
        alert("Unregistered Successfully!");
        $("#reg-eve-btn").show();
        $("#unreg-eve-btn").hide();
        $(".attCode-sec").hide();
      } else {
        alert("Failed to unregister!");
      }
    },
  });
}
function getEveDetails(id, eid, url) {
  $.ajax({
    url: "https://clubsatsrit.in/php_scripts/eveDetails",
    method: "post",
    data: {
      eveDetails: "true",
      cid: id,
      evid: eid,
    },
    beforeSend: function () {
      // setting a timeout
      $("#preloader").show();
    },
    success: function (data) {
      $("#preloader").hide();
      if (data != "") {
        data = JSON.parse(data);
        if (url.includes("eventDetails")) {
          $("#eve_name").val(data.ename);
          $("#eve-desc").html(data.about_eve);
          $(".eve-feat-img").attr("src", data.img);
          $(".img-area").css("background-image", `url(${data.img})`);
          let date = data.date.split("-");
          $("#date").val(parseInt(date[0]));
          $("#Month").val(parseInt(date[1]));
          $("#Year").val(parseInt(date[2]));
          $(".eve-for").val(data.eType);
          let branches = data.branches.split(",");
          for (let i of branches) {
            $(`#cb1[value='${i}']`).click();
          }
          let years = data.years.split(",");
          for (let i of years) {
            $(`input[name='year-cb'][value='${i}']`).click();
          }
          if (data.CTAlink != "") {
            $("#cta").click();
          }
          $("#CTA-link").val(data.CTAlink);
        } else if (url.includes("event")) {
          $(".club-name-head").text(data.ename);
          $(".header-title").text(data.cname);
          $(".club-name-sub-head").text("by " + data.cname);
          $(".about-txt").html(data.about_eve);
          $("#eve-date").text(data.date);
          $(".feat-img").attr("src", data.img);
          $(".reg-btn").attr("data-ref", data.CTAlink);
          $("#reg-count").text(data.regCount);
          $(".avail-to-txt").text(
            `Avaliable for only ${data.years} year ${data.branches}.`
          );
          $("#vote-eve-btn").hide();
          if (data.isfMe == 1 && data.years.includes(data.myYear.toString())) {
            if (data.isMember == 0 && data.eType == "Membership") {
              $(
                "#reg-eve-btn,#unreg-eve-btn,.attCode-sec,#vote-eve-btn"
              ).remove();
            }
            if (data.isAtt == 1) {
              $("#reg-eve-btn,#unreg-eve-btn,.attCode-sec").remove();
              if (data.isVoteLive == 1) {
                $("#vote-eve-btn").show();
              } else {
                $("#vote-eve-btn").hide();
              }
            }
            if (checkDate(data.date) == "past") {
              $(
                "#reg-eve-btn,#unreg-eve-btn,.attCode-sec,#vote-eve-btn"
              ).remove();
            }
            if (data.hasAttCode == 1 && data.isAtt.toString() == "0") {
              $(".attCode-sec").show();
            } else {
              $(".attCode-sec").hide();
            }

            if (data.reg == 1) {
              $("#reg-eve-btn").hide();
              $("#unreg-eve-btn").show();
            } else {
              $("#reg-eve-btn").show();
              $("#unreg-eve-btn").hide();
            }
          } else {
            $(
              "#reg-eve-btn,#unreg-eve-btn,.attCode-sec,#vote-eve-btn"
            ).remove();
            $("#reg-eve-btn").remove();
            $("#unreg-eve-btn").remove();
          }
        }
      }
    },
  });
}
function checkDate(date) {
  // Parse the string into a Date object
  var dateString = date;
  var parts = dateString.split("-");
  var year =
    parts[2].length > 2 ? parseInt(parts[2]) : parseInt(parts[2]) + 2000; // Assuming 22 refers to 2022
  var month = parseInt(parts[1]) - 1; // JavaScript months are 0-indexed
  var day = parseInt(parts[0]);
  console.log(year);
  var parsedDate = new Date(year, month, day);
  // Get the current date
  var currentDate = new Date();

  // Compare the parsed date with the current date
  if (parsedDate.toDateString() === currentDate.toDateString()) {
    return "today";
  } else if (parsedDate < currentDate) {
    return "past";
  } else {
    return "future";
  }
}
function eventDetails(id, cname, action, eid) {
  cname = cname.replace("_", " ");
  var name = $("#eve_name").val();
  var about = $("#eve-desc").html();
  var img = $(".eve-feat-img").attr("src");
  img =
    img != ""
      ? img
      : "https://developers.google.com/static/community/images/gdsc-solution-challenge/solutionchallenge-homepage.png";

  var org_date =
    $("#date").val() + "-" + $("#Month").val() + "-" + $("#Year").val();
  var branch = selectedBranch.toString();
  var clubType = $(".eve-for").val();
  var year = selectedYear.toString();
  var linkCTA = $("#cta").prop("checked") ? $("#CTA-link").val() : "";
  console.log(linkCTA);
  if (
    name.trim() != "" &&
    $("#eve-desc").text().trim() != "" &&
    org_date != "" &&
    branch != "" &&
    year != ""
  ) {
    var askConf =
      action == "create"
        ? "Once the event is created, you cannot delete it! Click ok to create event."
        : "Confirm to update details.";
    if (confirm(askConf)) {
      let body = {
        eventAction: action,
        cid: id,
        cname: cname,
        ename: name,
        about_e: about,
        imge: img,
        date: org_date,
        clubType: clubType,
        branch: branch,
        year: year,
        linkCTA: linkCTA,
      };
      let body2 = {
        eventAction: action,
        cid: id,
        eid: eid,
        ename: name,
        about_e: about,
        imge: img,
        date: org_date,
        clubType: clubType,
        branch: branch,
        year: year,
        linkCTA: linkCTA,
      };
      $.ajax({
        url: "https://clubsatsrit.in/php_scripts/eveDetails",
        method: "post",
        data: action == "create" ? body : body2,
        success: (data) => {
          console.log(data);

          cname = cname.replace(" ", "_");
          if (action == "create") {
            if (data != 1) {
              alert(`Event created Successfully`);
              window.location.href = `https://clubsatsrit.in/event?cid=${id}&cname=${cname}&evid=${data}`;
            } else {
              alert("Failed to create the Event.");
            }
          } else {
            if (data == 1) {
              alert(`Event updated Successfully`);
              window.location.href = `https://clubsatsrit.in/event?cid=${id}&cname=${cname}&evid=${eid}`;
            } else {
              alert("Failed to update the Event.");
            }
          }
        },
      });
    }
  } else {
    alert("All fields are required, except 'Call To Action Link' field.");
  }
}
