$(document).ready(() => {
  $(".btn-login").click(() => {
    $(".signup-container").hide();
    $(".login-container").show();
    $(".otp-pwd-container,.forgot-pwd-container").hide();
    $(".btn-signup").css("background", "#D9D9D9").css("font-weight", "normal");
    $(".btn-login").css("background", "#6CDE8C").css("font-weight", "700");
  });
  $(".btn-signup").click(() => {
    $(".login-container").hide();
    $(".signup-container").show();
    $(".forgot-pwd-container,.otp-pwd-container").hide();
    $(".btn-login").css("background", "#D9D9D9").css("font-weight", "normal");
    $(".btn-signup").css("background", "#6CDE8C").css("font-weight", "700");
  });
  $("#login-btn").click((e) => {
    e.preventDefault();
    if (
      $("#login-pwd").val().trim() == "" ||
      $("#login-mail").val().trim() == ""
    ) {
      alert("Please fill all the deatils.");
    } else {
      $("#login-btn").text("Logging in...");
      let dataToSend = {
        LoS: "l",
        mail_id: $("#login-mail").val(),
        pwd: $("#login-pwd").val(),
      };
      var urlEncodedData = Object.keys(dataToSend)
        .map(
          (key) =>
            encodeURIComponent(key) + "=" + encodeURIComponent(dataToSend[key])
        )
        .join("&");
      fetch("php_scripts/loginSignup", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: urlEncodedData,
      })
        .then((response) => response.text())
        .then((data) => {
          $("#login-btn").text("Login");
          if (data == "success") {
            window.location.href = "https://clubsatsrit.in/home";
          } else {
            alert(data);
          }
        })
        .catch((error) => console.error("Error:", error));
    }
  });
  $("#signup-btn").click((e) => {
    e.preventDefault();
    if (
      $("#signup-pwd").val().trim() == "" ||
      $("#signup-fullname").val().trim() == "" ||
      $("#signup-mail").val().trim() == "" ||
      $("#signup-cpwd").val().trim() == ""
    ) {
      alert("Please fill all the deatils.");
    } else {
      if ($("#signup-pwd").val().length > 8) {
        if ($("#signup-pwd").val() == $("#signup-cpwd").val()) {
          $("#signup-btn").text("Signing up...");
          let dataToSend = {
            LoS: "s",
            user_name: $("#signup-fullname").val(),
            mail_id: $("#signup-mail").val(),
            pwd: $("#signup-pwd").val(),
          };
          var urlEncodedData = Object.keys(dataToSend)
            .map(
              (key) =>
                encodeURIComponent(key) +
                "=" +
                encodeURIComponent(dataToSend[key])
            )
            .join("&");
          fetch("php_scripts/loginSignup", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: urlEncodedData,
          })
            .then((response) => response.text())
            .then((data) => {
              $("#signup-btn").text("Sign up");
              if (data == 1) {
                alert("Successfully Signed Up!");
                window.location.href = "https://clubsatsrit.in/home";
              } else {
                alert(data);
              }
            })
            .catch((error) => console.error("Error:", error));
        } else {
          alert("Passwords do not match!");
        }
      } else {
        alert("Password should be atleast 8 characters long!");
      }
    }
  });
  $(".forgot-password").click(() => {
    $(".forgot-pwd-container").show();
    $(".otp-pwd-container,.login-container,.signup-container").hide();
  });
  $("#send-mail-btn").click((e) => {
    if ($("#mail-forgot-pwd").val().trim() != "") {
      $("#send-mail-btn").text("Sending...");
      let dataToSend = {
        fog_pwd: "true",
        mail_id: $("#mail-forgot-pwd").val(),
      };
      var urlEncodedData = Object.keys(dataToSend)
        .map(
          (key) =>
            encodeURIComponent(key) + "=" + encodeURIComponent(dataToSend[key])
        )
        .join("&");
      fetch("php_scripts/loginSignup", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: urlEncodedData,
      })
        .then((response) => response.text())
        .then((data) => {
          $("#send-mail-btn").text("Send Email");
          if (data.includes("successfully")) {
            $(".lgn-pg-container").hide();
            $(".otp-pwd-container").show();
            alert(data);
            window.location.href =
              "https://clubsatsrit.in/login?tp=forgot_pwd_otp";
          }
        })
        .catch((error) => console.error("Error:", error));
    }
  });
  const url = window.location.href;
  const urlParams = new URLSearchParams(window.location.search);

  // Get the value of the 'id' parameter
  const type = urlParams.get("tp");
  if (type == "forgot_pwd_otp") {
    $(".lgn-pg-container").hide();
    $(".otp-pwd-container").show();
  }

  $("#verify-otp-btn").click((e) => {
    if ($("#otp-forgot-pwd").val().trim() != "") {
      $("#verify-otp-btn").text("Verifying...");
      let dataToSend = {
        fog_pwd_otp: "true",
        otp: $("#otp-forgot-pwd").val(),
      };
      var urlEncodedData = Object.keys(dataToSend)
        .map(
          (key) =>
            encodeURIComponent(key) + "=" + encodeURIComponent(dataToSend[key])
        )
        .join("&");
      fetch("php_scripts/loginSignup", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: urlEncodedData,
      })
        .then((response) => response.text())
        .then((data) => {
          $("#verify-otp-btn").text("Verify");
          if (data != 0) {
            alert(data);
            if (data == "verified") {
              $(".lgn-pg-container").hide();
              $(".reset-pwd-container").show();
            }
          }
        })
        .catch((error) => console.error("Error:", error));
    }
  });
  $("#reset-pwd-btn").click((e) => {
    if ($("#reset-pwd").val().trim() != "") {
      if ($("#reset-pwd").val().length > 8) {
        if ($("#reset-pwd").val() == $("#reset-cpwd").val()) {
          $("#reset-pwd-btn").text("Resetting...");
          let dataToSend = {
            reset_pwd_: "true",
            pwd: $("#reset-pwd").val(),
          };
          var urlEncodedData = Object.keys(dataToSend)
            .map(
              (key) =>
                encodeURIComponent(key) +
                "=" +
                encodeURIComponent(dataToSend[key])
            )
            .join("&");
          fetch("php_scripts/loginSignup", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: urlEncodedData,
          })
            .then((response) => response.text())
            .then((data) => {
              $("#reset-pwd-btn").text("Reset Password");
              if (data == 1) {
                alert("Password Reseted Successfully!");
                window.location.href = "https://clubsatsrit.in/login";
              }
            })
            .catch((error) => console.error("Error:", error));
        } else {
          alert("Passwords do not match!");
        }
      } else {
        alert("Password should be atleast 8 characters long!");
      }
    }
  });
});
