<?php 
session_start();
if(!isset($_SESSION['ses_id'],$_SESSION['us_tp'])){
if(isset($_COOKIE['_uid_'],$_COOKIE['_us_tp_'])){
  $_SESSION['ses_id']=htmlentities($_COOKIE['_uid_']);
  $_SESSION['us_tp'] =htmlentities($_COOKIE['_us_tp_']);
}else{
  header("Location: login.php");  
}
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
    <title>Voting</title>
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
        <h3 class="heading">Voting</h3>
        
        <div class="voting-area">
          <div class="vote-cards-container">
            <div class="vote-cards"></div>
          </div>
          <button
            type="button"
            style="margin-top: 20px"
            class="btn tool-nav-btn submit-vote-btn"
          >
            Submit
          </button>
        </div>
        <div class="vote-submission-msg" style="display:none">
          <center><img src="https://media.istockphoto.com/id/1445441629/vector/filling-admission-form-isolated-cartoon-vector-illustration.jpg?s=612x612&w=0&k=20&c=8OY5apJqdnCBwOXifMsKBgxvEM9SMfE7s18QJtfIwzw=" width="200" height="200" alt="">
            <h4 style="font-family: Inter;">Your vote has been submitted.<br> Thank you for participating!</h4>
          </center>
        </div>
      </div>
    </div>
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <script src="scripts/jquery-3.5.1.js"></script>
    <script src="scripts/votes.js"></script>
  </body>
</html>
