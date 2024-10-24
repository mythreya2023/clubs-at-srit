$(document).ready(() => {
  const url = window.location.href;

  // Create a URLSearchParams object
  const urlParams = new URLSearchParams(window.location.search);

  // Get the value of the 'id' parameter
  const id = urlParams.get("cid");
  let name = urlParams.get("cname");
  name = name != null ? name : "";
  $(".by-club-name").text(name.replace("_", " "));

  if (url.includes("postupdate")) {
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
    $(".back-btn").click((e) => {
      e.preventDefault();
      window.history.go(-1);
    });
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
            let preImg = $(".post-img").attr("src");
            if (preImg == "" || preImg == undefined || preImg == null)
              preImg = "";
            else preImg = preImg.split("https://clubsatsrit.in/")[1];
            form_data = new FormData();
            form_data.append("image", property);
            form_data.append("createType", "post");
            form_data.append("preImg", preImg);
          }
        }
      }
    });
    $(".post-update-btn").click((e) => {
      let postIt = () => {
        // getTags($("#post-text-area"));
        let txt = $("#post-text-area").html();
        let img = $(".eve-feat-img").attr("src");
        $.ajax({
          url: "https://clubsatsrit.in/php_scripts/posts",
          method: "POST",
          data: {
            post: "true",
            cid: id,
            txt: txt,
            img: img,
            cname: name,
          },
          beforeSend: function () {
            // setting a timeout
            $("#preloader").show();
          },
          success: function (data) {
            $("#preloader").hide();
            if (data == 1) {
              window.history.go(-1);
            }
          },
        });
      };
      //uploading image
      if (form_data != "") {
        $.ajax({
          url: "https://clubsatsrit.in/php_scripts/addImg_c",
          type: "POST",
          data: form_data,
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function () {
            // setting a timeout
            $("#preloader").show();
          },
          success: function (data) {
            $("#preloader").hide();
            $(".post-img").attr("src", data);
            $(".img-area").css("background-image", `url('${data}')`);
            postIt();
          },
        });
      } else {
        postIt();
      }
    });
  }

  if (!url.includes("postupdate")) {
    let menuShow = false;
    $("body").on("click", ".post-menue-btn", (e) => {
      if (menuShow == false) {
        menuShow = true;
        $(".menu-dots").hide();
        $(e.target).parent().parent().parent().children(".menu-dots").show();
      } else {
        menuShow = false;
        $(e.target).parent().parent().parent().children(".menu-dots").hide();
      }
    });
    $("body").on("click", ".delete-post-btn", (e) => {
      let cpd = $(e.target).attr("data-cpid");
      let delImg = $(e.target).attr("data-img");
      $.ajax({
        url: "https://clubsatsrit.in/php_scripts/posts",
        method: "POST",
        data: {
          post: "delete",
          cid: cpd,
          img: delImg,
        },
        success: (data) => {
          console.log(data);
          if (data == 1) {
            $(e.target).parent().parent(".club-post").remove();
          }
        },
      });
    });

    var offset = 0;
    var stopScroll = false;
    if (url.includes("home")) {
      let IntId = setInterval(() => {
        var status = $(".updates-display").attr("data-reqStatus");
        if (status == "completed") {
          getPosts();
          clearInterval(IntId);
        }
      }, 100);
    } else {
      getPosts();
    }
    $(window).scroll(function () {
      if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
        if (stopScroll == false) getPosts();
      }
    });
    function getPosts() {
      let body = {
        allpost: "true",
        cid: id,
        offSet: offset,
      };
      if (url.includes("home")) {
        let allcids = $(".updates-display").attr("data-clubs");
        body = {
          postAction: "F_clubs",
          cids: allcids,
          offSet: offset,
        };
      }
      $.ajax({
        url: "https://clubsatsrit.in/php_scripts/posts",
        method: "POST",
        data: body,
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
    $(".updates-display").on("click", ".unsave-post-btn", (e) => {
      let pid = $(e.target).closest(".unsave-post-btn").attr("data-cpid");
      let pd = $(e.target).closest(".unsave-post-btn").attr("data-pd");
      $.ajax({
        url: "https://clubsatsrit.in/php_scripts/posts",
        method: "POST",
        data: {
          unsavePost: "true",
          cpid: pid,
          pod: pd,
        },
        success: (data) => {
          if (data == 1) {
            $(e.target)
              .closest(".unsave-post-btn")
              .addClass("save-post-btn")
              .removeClass("unsave-post-btn")
              .children("svg")
              .attr("fill", "none");
          }
        },
      });
    });

    $(".updates-display").on("click", ".save-post-btn", (e) => {
      let pid = $(e.target).closest(".save-post-btn").attr("data-cpid");
      $.ajax({
        url: "https://clubsatsrit.in/php_scripts/posts",
        method: "POST",
        data: {
          savePost: "true",
          cpid: pid,
        },
        success: (data) => {
          if (data == 1) {
            $(e.target)
              .closest(".save-post-btn")
              .addClass("unsave-post-btn")
              .removeClass("save-post-btn")
              .children("svg")
              .attr("fill", "black");
          }
        },
      });
    });
  }
});
