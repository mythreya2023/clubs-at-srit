$(document).ready(() => {
  $(".back-btn").click(() => {
    window.history.go(-1);
  });
  $(".add-team-role-btn").click(() => {
    let role = $("#team-role-ipt").val().trim();
    if (role !== "") {
      $(".added-team-roles").append(`<div class="text-box team-role">
        <span>${role}</span>
        <div class="text-btn-remove animi-btn rem-team-role-btn">
          Remove
        </div>
      </div>`);
      $("#team-role-ipt").val("");
    }
  });
  $(".Team-roles-div").on("click", ".rem-team-role-btn", (e) => {
    $(e.target).parent().remove();
  });
  $(".stu-adm-btn").click((e) => {
    let stu_roll = $("#stu-admin-ipt").val();
    if (stu_roll.trim() != "") {
      $.ajax({
        url: "https://clubsatsrit.in/php_scripts/clubDetails",
        method: "POST",
        data: {
          action: "setStudentAdminRole",
          rollno: stu_roll,
        },
        success: function (data) {
          if (data == 0 || data == "0") {
            alert("No Student With That Roll No. Check Once!");
          } else {
            data = JSON.parse(data);
            $(".poster-name").text(data.name);
            $(".club-poster-img").attr("src", data.dp);
            $(".add-SA").hide();
            $(".added-SA").show();
          }
        },
      });
    }
  });
  $(".rem-stu-admin-btn").click((e) => {
    $(".add-SA").show();
    $(".added-SA").hide();
    $("#stu-admin-ipt").val("");
  });
  $("#club_name").keyup((e) => {
    $(".by-club-name").text($(e.target).val());
  });

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
  if (id != null) {
    $.ajax({
      url: "https://clubsatsrit.in/php_scripts/clubDetails",
      method: "POST",
      data: {
        cd: "clubDetails",
        cid: id,
      },
      success: function (data) {
        data = JSON.parse(data);

        $("#club_name").val(data.name);
        $("#about-area").html(data.about_c);
        $(".img-area").css("background-image", `url('${data.img}')`);
        $(".eve-feat-img").attr("src", data.img);
        $("#stu-admin-ipt").val(data.std_admin);
        $(".branch-select").val(data.branch);
        $(".stu-adm-btn").click();

        $(".by-club-name").text(data.name);
        let t_role = data.team_roles;
        t_role = t_role.split(",");
        $(".added-team-roles").html("");
        for (let i = 0; i < t_role.length; i++) {
          $(".added-team-roles").append(`
            <div class="text-box team-role">
              <span>${t_role[i]}</span>
              <div class="text-btn-remove animi-btn rem-team-role-btn">
                Remove
              </div>
            </div>
          `);
        }
        if (data.isMs == 1) {
          $("#mem-cb").prop("checked", true);
        } else $("#mem-cb").prop("checked", false);
        $(".update-club-btn").show();
        $(".create-club-btn").hide();
      },
    });
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
          form_data.append("createType", "club");
          form_data.append("preImg", preImg);
          console.log(form_data);
        }
      }
    }
  });
  function eve_C_U(action) {
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
          createClub(action, id);
        },
      });
    } else {
      createClub(action, id);
    }
  }
  $(".create-club-btn").click((e) => {
    eve_C_U("createClub");
  });

  $("body").on("click", ".update-club-btn", (e) => {
    eve_C_U("updateClub");
  });
});
function createClub(act, id) {
  let name = $("#club_name").val();
  let about = $("#about-area").html();
  let img = $(".eve-feat-img").attr("src");
  img =
    img != ""
      ? img
      : "https://developers.google.com/static/community/images/gdsc-solution-challenge/solutionchallenge-homepage.png";
  let studadmin =
    $(".added-SA").css("display") == "none" ? "" : $("#stu-admin-ipt").val();
  let teamRoles = "";
  $(".team-role").each((i, e) => {
    teamRoles +=
      $(e).children("span").text() +
      ($(".team-role").length > i + 1 ? "," : "");
  });
  let branch = $(".branch-select").val();
  let membership = $("#mem-cb").is(":checked") ? 1 : 0;
  if (
    name.trim() != "" &&
    about.trim() != "" &&
    studadmin != "" &&
    teamRoles != ""
  ) {
    var askConf =
      act == "createClub"
        ? "Once the club is created, you cannot delete it! Click ok to create club."
        : "Confirm to update details.";
    if (confirm(askConf)) {
      $.ajax({
        url: "https://clubsatsrit.in/php_scripts/clubDetails",
        method: "POST",
        data: {
          action: act,
          cid: id,
          name: name,
          about: about,
          img: img,
          teamRoles: teamRoles,
          stu_admin: studadmin,
          branch: branch,
          ms: membership,
        },
        success: function (data) {
          console.log(data);
          if (data == "error") {
            alert(
              "There is an error occured. Please try again after some time."
            );
          } else {
            window.location.href = `https://clubsatsrit.in/club?cid=${data}`;
          }
        },
      });
    }
  } else {
    alert("Name, About, Student Admin,and Team Roles are required!");
  }
}
