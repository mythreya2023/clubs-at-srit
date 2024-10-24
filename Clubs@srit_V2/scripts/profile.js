$(document).ready(() => {
  $(".back-arrow").click((e) => {
    e.preventDefault();
    window.location.href = "https://clubsatsrit.in/home";
  });
  $(".saved-posts-btn").click(() => {
    $(".participations-tab").hide();
    $(".saved-posts").show();
    $(".saved-posts-btn")
      .children("svg")
      .children("path")
      .attr("fill", "#221E1E");
    $(".participations-tab-btn").css("background", "#d8e8ebab");
    $(".saved-posts-btn").css("background", "#CDE4E9");
  });
  $(".participations-tab-btn").click(() => {
    $(".saved-posts").hide();
    $(".participations-tab").show();
    $(".saved-posts-btn").css("background", "#d8e8ebab");
    $(".participations-tab-btn").css("background", "#CDE4E9");
    $(".saved-posts-btn").children("svg").children("path").attr("fill", "none");
  });
  let x = 0;
  $("#details-down").click(function () {
    if (x == 0) {
      $(".person-details").slideDown();
      $("#details-down").css("transform", "rotate(180deg)");
      x = 1;
    } else {
      x = 0;
      $(".person-details").slideUp();
      $("#details-down").css("transform", "rotate(0deg)");
    }
  });

  $(".close_btn").click(() => {
    $(".edit_prof_popUp").hide();
  });
  $(".edit-profile-btn").click(() => {
    $(".edit_prof_popUp").show();
  });
  $(".save-changes-btn").click(() => {
    let name = $("#edit_name").val().trim();
    let pwd = $("#new_Pwd").val();
    let cpwd = $("#conf_pwd").val();
    if (pwd.trim() != "" && cpwd.trim() != "") {
      if (pwd.length < 8) {
        alert("Password should contain min 8 characters.");
      } else if (pwd != cpwd) {
        alert("Password and Confirm Password fields do not match.");
      } else if (name.trim() == "") {
        alert("Your name cannot be empty!");
      } else {
        updateDet();
      }
    } else {
      if (name != "") {
        updateDet();
      }
    }
    function updateDet() {
      $.ajax({
        url: "https://clubsatsrit.in/php_scripts/profileDetails",
        method: "post",
        data: {
          updateDetails: "true",
          name: name,
          pwd: pwd,
        },
        success: (data) => {
          console.log(data);
          if (data == 1) {
            alert("Updated Successfully!");
            $(".edit_prof_popUp").hide();
          } else {
            alert("Unable to Update! Try again.");
          }
        },
      });
    }
  });

  console.log("HI");
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
          let preImg = $(".profile_pic").attr("src");
          if (preImg == "" || preImg == undefined || preImg == null)
            preImg = "";
          else preImg = preImg.split("https://clubsatsrit.in/")[1];
          form_data = new FormData();
          form_data.append("image", property);
          form_data.append("createType", "profile_pic");
          form_data.append("preImg", preImg);

          $.ajax({
            url: "https://clubsatsrit.in/php_scripts/addImg_c.php",
            type: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: (e) => {
              console.log(e);
              var reader = new FileReader();
              reader.onload = function (e) {
                let blob = e.target.result;
                $(".profile_pic").attr("src", blob);
              };
              reader.readAsDataURL(property);
            },
            success: function (data) {
              console.log(data);
              $(".profile_pic").attr("src", data);
            },
          });
        }
      }
    }
  });

  getDetails();
  function getDetails() {
    $.ajax({
      url: "https://clubsatsrit.in/php_scripts/profileDetails",
      method: "post",
      data: {
        pro_Details: "true",
      },
      beforeSend: function () {
        // setting a timeout
        $("#preloader").show();
      },
      success: function (data) {
        $("#preloader").hide();
        console.log(data);
        if (data != 0) {
          data = JSON.parse(data);
          $(".profile-name").text(data.name);
          $(".profile-id").text(data.uName);
          $(".profile_pic").attr("src", data.dp);
          $("#edit-name").val(data.name);
          if (data.uType == 1) {
            let currentDate = new Date();
            let currentYear = currentDate.getFullYear();
            let currentMonth = currentDate.getMonth(); // January is 0, December is 11
            let joinYear = parseInt("20" + data.year);

            // Check if current month is before June
            if (currentMonth >= 5) {
              // Months are 0-indexed, so June is 5
              currentYear++; // It's still the previous academic year
            }
            let year =
              currentYear - (data.type === "Regular" ? joinYear : joinYear - 1);

            let suffixs = ["st", "nd", "rd", "th"];
            $("#stu_year").text(year.toString() + suffixs[year - 1]);
            $("#stu_branch").text(data.branche);
            $("#stu_type").text(data.type);
            $("#stu_rollno").text(data.rollno);
          } else {
            $(".usdetails,.detail-item-divider").remove();
          }
        }
      },
    });
  }
  var offset = 0;
  var stopScroll = false;
  getSavedPosts();
  $(window).scroll(function () {
    if (
      $(window).scrollTop() + $(window).height() >=
      $(document).height() - 2
    ) {
      if (stopScroll == false && $(".saved-posts").css("display") != "none") {
        getSavedPosts();
      }
    }
  });
  function getSavedPosts() {
    $.ajax({
      url: "https://clubsatsrit.in/php_scripts/profileDetails",
      method: "POST",
      data: {
        postAction: "s_posts",
        offSet: offset,
      },
      beforeSend: function () {
        // setting a timeout
        $("#preloader").show();
      },
      success: function (data) {
        $("#preloader").hide();
        if (data != 0) {
          if (offset == 0) {
            $(".updates-display").html(data);
          } else {
            $(".updates-display").append(data);
          }
          offset += 10;
        } else {
          stopScroll = true;
        }
      },
    });
  }

  $("body").on("click", ".eve-nav-btn", (e) => {
    e.preventDefault();
    let eid = $(e.target).closest(".eve-nav-btn").attr("data-eid");
    let cname = $(e.target).closest(".eve-nav-btn").attr("data-cname");
    let id = $(e.target).closest(".eve-nav-btn").attr("data-cid");
    window.location.href = `https://clubsatsrit.in/event?cid=${id}&cname=${cname}&evid=${eid}`;
  });
});
var Eoffset = 0;
var stopScroll_e = false;
getRegEves();
$(window).scroll(function () {
  if ($(window).scrollTop() + $(window).height() >= $(document).height() - 2) {
    if (
      stopScroll_e == false &&
      $(".participations-tab").css("display") != "none"
    ) {
      getRegEves();
    }
  }
});
function getRegEves() {
  $.ajax({
    url: "https://clubsatsrit.in/php_scripts/profileDetails",
    method: "POST",
    data: {
      regEvents: "true",
      offSet: Eoffset,
    },
    beforeSend: function () {
      // setting a timeout
      $("#preloader").show();
    },
    success: function (data) {
      $("#preloader").hide();
      if (data != 0) {
        if (Eoffset == 0) {
          $(".participations-tab").html(data);
        } else {
          $(".participations-tab").append(data);
        }
        Eoffset += 10;
      } else {
        stopScroll_e = true;
      }
    },
  });
}
