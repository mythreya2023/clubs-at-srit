<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Clubs@SRIT</title>
    <meta name="theme-color" content="#ffffff">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Inter"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Istok Web"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Inria Sans"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
    />
    <link rel="stylesheet" href="styles/common.css" />
    <link rel="stylesheet" href="styles/login.css" />
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon_io/favicon-16x16.png">
    <!--<link rel="manifest" href="/images/favicon_io/site.webmanifest">-->
    <link rel="manifest" href="/manifest.json" />
    <style>
      .body-container{
        display: flex;
        flex-wrap: wrap;
        align-content: center;
        justify-content: center;
        align-items: center;
        margin-top: 50px;
      }
      .button-group{
        margin:auto;
      }
      .srit-logo{
        width:500px;
      }
      .illu{
        width:400px;
      }
      @media only screen and (max-width: 768px) {
          .illu {
              display: none; /* Hide the image */
          }
          .srit-logo{
            width:350px;
          }
          
      .body-container{
        margin-top: 70px;
        margin-bottom:20px;
      }
      }
      body{
        display:block;
      }
      .containers-all{
        
    display: flex;
    justify-content: center;

      }
    </style>
  </head>
  <body>
    <div class="body-container">
      <div class="imageSide">
            <img src="https://www.srit.ac.in/wp-content/uploads/2021/05/SRIT-STRIP-JPG-2048x308.jpg" alt="" class='srit-logo'>
            <div>
            <img src="https://thumbs.dreamstime.com/b/student-club-abstract-concept-vector-illustration-organization-university-interest-school-activity-program-college-205626382.jpg" alt="" class="illu" >
            </div>
      </div>
      <div class="containers-all">
          <div class="signup-container lgn-pg-container">
            <h1 class="app-name">CLUBS@SRIT</h1>
            <div class="button-group">
              <button class="btn-login animi-btn">Login</button>
              <button class="btn-signup animi-btn">Sign up</button>
            </div>
            <div class="login-form">
              <input
                type="text"
                placeholder="Full Name"
                id="signup-fullname"
                class="lgn-txt text-box"
                required
              />
              <input
                type="email"
                id="signup-mail"
                placeholder="College Email ID"
                class="lgn-txt text-box"
                required
              />
              <input
                type="password"
                id="signup-pwd"
                placeholder="Password"
                class="text-box lgn-txt"
                required
              />
              <input
                type="password"
                id="signup-cpwd"
                placeholder="Confirm Password"
                class="text-box lgn-txt"
                required
              />
              <button
                type="submit"
                id="signup-btn"
                class="btn-submit btn animi-btn lgn-btn"
              >
                Sign up
              </button>
            </div>
          </div>
          <div class="login-container lgn-pg-container" >
            <h1 class="app-name">CLUBS@SRIT</h1>
            <div class="button-group">
              <button class="btn-login animi-btn">Login</button>
              <button class="btn-signup animi-btn">Sign up</button>
            </div>
            <form class="login-form">
              <input
                type="email"
                placeholder="Email ID"
                id="login-mail"
                class="lgn-txt text-box"
                required
              />
              <input
                type="password"
                id="login-pwd"
                placeholder="Password"
                class="text-box lgn-txt"
                required
              />
              <button
                type="submit"
                id="login-btn"
                class="btn-submit btn animi-btn lgn-btn"
              >
                Log in
              </button>
            </form>
            <p class="forgot-password">Forgot Password ?</p>
          </div>
          
          <div class="forgot-pwd-container lgn-pg-container" style="min-width:300px; display:none;">
            <h1 class="app-name">CLUBS@SRIT</h1>
            <p class="sub-heading">Enter your email id for verification.</p>
            <div class="login-form">
              <input
                type="email"
                placeholder="Email ID"
                id="mail-forgot-pwd"
                class="lgn-txt text-box"
                required
              />
              <button
                type="submit"
                id="send-mail-btn"
                class="btn-submit btn animi-btn lgn-btn"
              >
                Send Email
              </button>
            </div>
          </div>
          
          <div class="otp-pwd-container lgn-pg-container" style="min-width:300px; display:none;">
            <h1 class="app-name">CLUBS@SRIT</h1>
            <div class="login-form">
              <input
                type="text"
                placeholder="Enter OTP"
                id="otp-forgot-pwd"
                class="lgn-txt text-box"
                required
              />
              <button
                type="submit"
                id="verify-otp-btn"
                class="btn-submit btn animi-btn lgn-btn"
              >
                Verify
              </button>
            </div>
          </div>
          
          <div class="reset-pwd-container lgn-pg-container" style="min-width:300px; display:none;">
            <h1 class="app-name">CLUBS@SRIT</h1>
            <div class="login-form">
            <input
                type="password"
                id="reset-pwd"
                placeholder="Password"
                class="text-box lgn-txt"
                required
              />
              <input
                type="password"
                id="reset-cpwd"
                placeholder="Confirm Password"
                class="text-box lgn-txt"
                required
              />
              <button
                type="submit"
                id="reset-pwd-btn"
                class="btn-submit btn animi-btn lgn-btn"
              >
                Reset Password
              </button>
            </div>
          </div>
      </div>
    </div>
    <div class="sub-heading" style="color:darkslategray; text-align:center">Developed by department of CSE</div>
    <script src="scripts/jquery-3.5.1.js"></script>
    <script src="scripts/loginSignup.js"></script>
  </body>
</html>
