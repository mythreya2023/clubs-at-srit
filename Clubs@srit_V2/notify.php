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
    <meta name="theme-color" content="#d6edf0">

    <title>Notifications</title>
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
    <link rel="stylesheet" href="styles/home.css" />
    <link rel="stylesheet" href="styles/notify.css" />
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon_io/site.webmanifest">
    <link rel="manifest" href="/manifest.json" />

    <style>
      .sub-heading{
        font-weight:700;
      }
      .img-round{
    box-shadow: 0 0 11px -4px black;
      }
    </style>
  </head>
  <body>
    <div class="header">
      <div class="greetings">
        <p class="greet_name">HI,</p>
        <p class="greet_user">Good Morning!</p>
      </div>
      <img
        src=""
        alt=""
        class="img-round animi-btn"
        id="profile-btn"
      />
    </div>
    <div class="home-body">
      <h2 class="heading">Notifications</h2>
      <div class="notifications-box">
        <center>
          <img src="https://cdni.iconscout.com/illustration/premium/thumb/no-notification-7359561-6024629.png"  width='200' height='200' alt="">
          <p class="sub-heading">No new notifications!</p>
        </center>
       
      </div>
    </div>
    <div class="floating-nav">
      <div class="home-btn animi-btn">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
        >
          <path
            d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z"
            stroke="black"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
          <path
            d="M9 22V12H15V22"
            stroke="black"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
      </div>
      <div class="notifi-btn animi-btn">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="black"
        >
          <path
            d="M18 8C18 6.4087 17.3679 4.88258 16.2426 3.75736C15.1174 2.63214 13.5913 2 12 2C10.4087 2 8.88258 2.63214 7.75736 3.75736C6.63214 4.88258 6 6.4087 6 8C6 15 3 17 3 17H21C21 17 18 15 18 8Z"
            stroke="black"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
          <path
            d="M13.73 21C13.5542 21.3031 13.3018 21.5547 12.9982 21.7295C12.6946 21.9044 12.3504 21.9965 12 21.9965C11.6496 21.9965 11.3054 21.9044 11.0018 21.7295C10.6981 21.5547 10.4458 21.3031 10.27 21"
            stroke="black"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
      </div>
    </div>
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <script src="scripts/jquery-3.5.1.js"></script>
    <script src="scripts/home.js"></script>
  </body>
</html>
