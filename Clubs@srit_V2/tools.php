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
    <title>Tools</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Inter"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Istok Web"
    />
    <link rel="stylesheet" href="styles/common.css" />
    <link rel="stylesheet" href="styles/creation.css" />
    <link rel="stylesheet" href="styles/tools.css" />
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon_io/site.webmanifest">
  </head>
  <body>
    <div class="registrations-div-box">
      <div class="header">
        <div class="tools-page-back-btn back-btn animi-btn">
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
        <h3 class="heading">Tools</h3>
        <div class="tool-buttons">
          <button
            type="button"
            id="reg-nav-btn"
            class="btn tool-nav-btn animi-btn"
          >
            Registrations
          </button>
          <button
            type="button"
            id="create-vote-btn"
            class="btn btn-green tool-nav-btn animi-btn"
          >
            Create Voting
          </button>
          <button
            type="button"
            id="vote-nav-btn"
            class="btn btn-orng tool-nav-btn animi-btn"
          >
            Voting Details
          </button>
          <button
            type="button"
            id="gen-rep-nav-btn"
            class="btn btn-green tool-nav-btn animi-btn"
          >
            Generate Report
          </button>
          <section class="Att-code-generator">
            <p class="heading" style="font-size:18px;">Attendance Code:</p>
            <h4 class="heading att-code-display" style="margin-top:0;"></h4>
          <button
            type="button"
            id="att-code-gen-btn"
            class="btn btn tool-nav-btn animi-btn"
          >
            Generate Code
          </button>
          </section>
        </div>
      </div>
    </div>
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <script src="scripts/jquery-3.5.1.js"></script>
    <script src="scripts/tool.js"></script>
  </body>
</html>
