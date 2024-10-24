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
    <title>Voting Details</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon_io/site.webmanifest">
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
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
    />
    <link rel="stylesheet" href="styles/common.css" />
    <link rel="stylesheet" href="styles/creation.css" />
    <link rel="stylesheet" href="styles/tools.css" />
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
        <h3 class="heading">Voting Details</h3>
        <div class="reg-sub-att-count">
          <div class="reg-att-count">
            <div class="reg-count">
              <span class="lft-side">Registered :</span
              ><span class="right-side registered-count">0</span>
            </div>
            <div class="reg-count">
              <span class="lft-side">Attended :</span
              ><span class="right-side attended-count">0</span>
            </div>
          </div>
          <div class="reg-count">
            <span class="lft-side">Submitted :</span
            ><span class="right-side submited-count">0</span>
          </div>
        </div>
        <div class="vote-cards-container">
          <div class="vote-cards">
            <!-- <div class="vote-card-box">
              <h3 class="sub-heading vcard-id">Card 1/2</h3>
              <div class="vote-card" style="padding-bottom: 15px">
                <div class="text-box card-quest-div">
                  Best Presentation of the day?
                </div>
                <hr style="margin-top: -3px; width: 92%" />
                <div class="card-options">
                  <div id="bar_chart" style="width: 100%; height: 300px"></div>
                </div>
              </div>
            </div> -->
          </div>
        </div>
        <button
          type="button"
          style="margin-top: 20px; background: #197a62"
          class="btn tool-nav-btn view-voters-btn"
        >
          Voters
        </button>
      </div>
    </div>
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <script src="scripts/jquery-3.5.1.js"></script>
    <script
      type="text/javascript"
      src="https://www.gstatic.com/charts/loader.js"
    ></script>
    <script src="scripts/voteDetails.js"></script>
  </body>
</html>
