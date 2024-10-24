<?php 
session_start();
if(!isset($_SESSION['ses_id'],$_GET['cid'])){
if (isset($_SERVER['HTTP_REFERER'])) {
  header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
  // Fallback if HTTP_REFERER is not set
  header('Location: home.php'); 
}
}
include "php_scripts/verifyTeam.php";
if($team['isMemb']==0){
  header("Location: home.php");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
    />
    <title>Registrations</title>
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
    <link rel="stylesheet" href="styles/profile.css" />
    <link rel="stylesheet" href="styles/creation.css" />
    <link rel="stylesheet" href="styles/team.css" />
    <link rel="stylesheet" href="styles/tools.css" />
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon_io/site.webmanifest">
  </head>
  <body>
    <div class="registrations-div-box">
      <div class="header">
        <div class="reg-page-back-btn back-btn animi-btn">
          <span class="back-arrow"
            ><svg
              xmlns="http://www.w3.org/2000/svg"
              width="30"
              height="30"
              viewBox="0 0 20 20"
              fill="none"
            >
              <path
                d="M15 18L9 12L15 6"
                stroke="black"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
          </span>
          <h2 class="heading" style="margin: 0">Back</h2>
        </div>
      </div>
      <div class="event-manager-page">
        <h3 class="heading">Registrations</h3>
        <div class="search-box-reg-det-div">
          <div class="search-user-box">
            <div class="user-search-box" style="border: none">
              <input
                type="text"
                class="text-box us-search-ipt"
                placeholder="user name"
              />
              <span class="search-icon animi-btn" style="margin-right: 5px"
                ><svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                >
                  <path
                    d="M11.7664 20.7552C16.7306 20.7552 20.7549 16.7308 20.7549 11.7666C20.7549 6.80236 16.7306 2.77805 11.7664 2.77805C6.80215 2.77805 2.77783 6.80236 2.77783 11.7666C2.77783 16.7308 6.80215 20.7552 11.7664 20.7552Z"
                    stroke="black"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                  <path
                    d="M18.0181 18.4851L21.5421 22"
                    stroke="black"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
              </span>
            </div>
          </div>
          <div class="count-reg-atte">
            <div class="reg-count">
              <span class="left-col">Registered : </span>
              <span class="right-col reg-act-count">0</span>
            </div>
            <div class="atte-count">
              <span class="left-col">Attended : </span>
              <span class="right-col atte-act-count">0</span>
            </div>
          </div>
        </div>
      </div>
      <div class="registered-users">
        <center><p class="sub-heading">No Registrations Yet!</p></center>
      </div>
    </div>
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <script src="scripts/jquery-3.5.1.js"></script>
    <script src="scripts/tool.js"></script>
  </body>
</html>
