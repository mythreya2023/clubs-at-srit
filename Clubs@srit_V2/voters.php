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
    <title>Voters</title>
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
        <div class="voted-page-back-btn back-btn animi-btn">
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
        <h3 class="heading">Voters</h3>
      </div>
      <div class="registered-users">
        <!-- <div class="team-member voter-user">
          <div class="mem-details">
            <img
              src="images/IMG_20201021_221757.jpg"
              alt=""
              class="img-sq userImage"
            />
            <div class="mem-txt-det">
              <p class="mem-name">Mythreya C</p>
              <p class="mem-role">214g1a0563</p>
            </div>
          </div>
          <div
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
          ></div>
        </div> -->
      </div>
    </div>
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <script src="scripts/jquery-3.5.1.js"></script>
    <script src="scripts/voteDetails.js"></script>
  </body>
</html>
