<?php
session_start();
if(isset($_COOKIE['_uid_'],$_COOKIE['_us_tp_'])){
  $_SESSION['ses_id']=htmlentities($_COOKIE['_uid_']);
  $_SESSION['us_tp'] =htmlentities($_COOKIE['_us_tp_']);
}
include "php_scripts/verifyTeam.php";

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
    />
    <meta name="theme-color" content="#E0E9E9">

    <title>club</title>
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
    <link rel="stylesheet" href="styles/club.css" />
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon_io/site.webmanifest">
    <link rel="manifest" href="/manifest.json" />
  </head>
  <body>
    <div class="club-details-container">
      <section class="club-feature-img">
        <header class="header">
        <div class="club-pg-btn animi-btn" style="display: flex;align-items: center;flex-direction: row;justify-content: flex-start;">
          <span class="back-arrow"
            ><svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 20 20"
              fill="none"
            >
              <path
                d="M15 18L9 12L15 6"
                stroke="white"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
          </span>
          <h2 class="header-title club_name"></h2>
          </div>
          <?php 
          if($team['isMemb']==1&&($team['mt']=="fac"||$team['mt']=="SA")){
          ?>
          <span class="club-edit-btn animi-btn"><svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="white">
              <g clip-path="url(#clip0_111_2408)">
                <path d="M13.4583 2.375C13.6662 2.16707 13.9131 2.00214 14.1848 1.88961C14.4564 1.77708 14.7476 1.71916 15.0416 1.71916C15.3357 1.71916 15.6269 1.77708 15.8985 1.88961C16.1702 2.00214 16.4171 2.16707 16.625 2.375C16.8329 2.58293 16.9978 2.82977 17.1104 3.10144C17.2229 3.37311 17.2808 3.66428 17.2808 3.95833C17.2808 4.25239 17.2229 4.54356 17.1104 4.81523C16.9978 5.0869 16.8329 5.33374 16.625 5.54167L5.93748 16.2292L1.58331 17.4167L2.77081 13.0625L13.4583 2.375Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
              </g>
              <defs>
                <clipPath id="clip0_111_2408">
                  <rect width="19" height="19" fill="white"></rect>
                </clipPath>
              </defs>
            </svg>
          </span>
          <?php }?>
        </header>
        <img src="" alt="" class="feat-img club-page-img" />
        <span class='flo-icon animi-btn' style='background:#00000000'></span>
     
      </section>
      <section class="profile">
        <h1 class="heading club_name" style="margin-top: 0">
          Club Name
        </h1>
        <div class="follow-details">
          <div class="followers">
            <span style="font-weight: 300" id="follower-count">0</span
            ><span style="font-weight: 500; margin-left: 6px">followers</span>
          </div>
          <button class="follow-btn follow-btn-blue animi-btn" id="follow-btn">Follow</button>
          <button class="follow-btn animi-btn" id="following-btn" style="display:none;background: transparent;color: gray;">Following</button>
        </div>
      </section>
      <section class="membership" style="margin-bottom: 20px;">
        <button class="membership-btn btn animi-btn btn-green" style='font-size:15px;height:36px;background:#2e9432;'><span class='mship-members-count'>28</span>+ Membership Members</button>
      </section>
      <section class="about">
        <div class="sec-head">
          <h2 class="sub-heading">About</h2>
          <?php 
          if($team['isMemb']==1){
        ?>
          <span class="save-about-txt animi-btn" style="display:none">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 30 30">
            <path d="M 26.980469 5.9902344 A 1.0001 1.0001 0 0 0 26.292969 6.2929688 L 11 21.585938 L 4.7070312 15.292969 A 1.0001 1.0001 0 1 0 3.2929688 16.707031 L 10.292969 23.707031 A 1.0001 1.0001 0 0 0 11.707031 23.707031 L 27.707031 7.7070312 A 1.0001 1.0001 0 0 0 26.980469 5.9902344 z"></path>
            </svg>
          </span>
          <span class="member-edit-btn about-edit-btn animi-btn" 
            ><svg
              xmlns="http://www.w3.org/2000/svg"
              width="19"
              height="19"
              viewBox="0 0 19 19"
              fill="none"
            >
              <g clip-path="url(#clip0_111_2408)">
                <path
                  d="M13.4583 2.375C13.6662 2.16707 13.9131 2.00214 14.1848 1.88961C14.4564 1.77708 14.7476 1.71916 15.0416 1.71916C15.3357 1.71916 15.6269 1.77708 15.8985 1.88961C16.1702 2.00214 16.4171 2.16707 16.625 2.375C16.8329 2.58293 16.9978 2.82977 17.1104 3.10144C17.2229 3.37311 17.2808 3.66428 17.2808 3.95833C17.2808 4.25239 17.2229 4.54356 17.1104 4.81523C16.9978 5.0869 16.8329 5.33374 16.625 5.54167L5.93748 16.2292L1.58331 17.4167L2.77081 13.0625L13.4583 2.375Z"
                  stroke="black"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </g>
              <defs>
                <clipPath id="clip0_111_2408">
                  <rect width="19" height="19" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </span>
          <?php } ?>
        </div>
        <p class="about-txt"
          onpaste="handlePaste(event)">
        </p>
      </section>
      <section class="executive-team">
        <div class="sec-head">
          <h2 class="sub-heading">Executive Team</h2>
          <?php 
          if($team['isMemb']==1){
          ?>
          <span class="member-edit-btn exe-team-btn animi-btn"
            ><svg
              xmlns="http://www.w3.org/2000/svg"
              width="19"
              height="19"
              viewBox="0 0 19 19"
              fill="none"
            >
              <g clip-path="url(#clip0_111_2408)">
                <path
                  d="M13.4583 2.375C13.6662 2.16707 13.9131 2.00214 14.1848 1.88961C14.4564 1.77708 14.7476 1.71916 15.0416 1.71916C15.3357 1.71916 15.6269 1.77708 15.8985 1.88961C16.1702 2.00214 16.4171 2.16707 16.625 2.375C16.8329 2.58293 16.9978 2.82977 17.1104 3.10144C17.2229 3.37311 17.2808 3.66428 17.2808 3.95833C17.2808 4.25239 17.2229 4.54356 17.1104 4.81523C16.9978 5.0869 16.8329 5.33374 16.625 5.54167L5.93748 16.2292L1.58331 17.4167L2.77081 13.0625L13.4583 2.375Z"
                  stroke="black"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </g>
              <defs>
                <clipPath id="clip0_111_2408">
                  <rect width="19" height="19" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </span>
          <?php } ?>
        </div>
        <div class="team-members">
        </div>
      </section>
    </div>
    <div class="club-esse-display">
      <section class="esse-header">
        <div class="right-half">
          <span class="back-btn go-back-btn animi-btn"
            ><svg class="go-back-btn"
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 20 20"
              fill="none"
            >
              <path  class="go-back-btn"
                d="M15 18L9 12L15 6"
                stroke="black"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
          </span>
          <div class="club-details-btn animi-btn">
            <img
              src=""
              alt=""
              class="img-round club-page-img"
            />
            <p class="sub-heading club_name" style="margin-left: 10px"></p>
          </div>
        </div>
        <button type="button" class="follow-btn follow-btn-blue animi-btn"  id="follow-btn" >Follow</button>
        
        <button class="follow-btn animi-btn" id="following-btn" style="display:none;background: transparent;color: gray;">Following</button>
      </section>
      <section class="club-btns">
        <div class="updates-btn club-tab-btn animi-btn">Updates</div>
        <div class="events-btn club-tab-btn animi-btn">Events</div>
      </section>
      <section class="updates-tab tab-box">
        <div class="updates-display">
          
        <center style="margin-top:50px">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/post-interface-5266761-4397160.png" width="200" alt="">
            <p class="sub-heading" style='color:gray;margin:auto'>No Posts Yet!</p>
          </center>
        </div>
        <?php 
          if($team['isMemb']==1){
        ?>
        <button class="post-update-btn circle-button animi-btn">
          <i class="fa fa-plus"></i>
        </button>
        <?php } ?>
      </section>
      <section class="events-tab tab-box">
        <div class="events-display">
          <center>
            <p class="sub-heading" style='color:gray;margin:auto'>No Events Yet!</p>
          </center>
        </div>
        <?php 
          if($team['isMemb']==1){
        ?>
        <button class="create-event-btn circle-button animi-btn">
          <i class="fa fa-plus"></i>
        </button>
        <?php } ?>
      </section>
    </div>
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <script>
       function handlePaste(e) {
    // Prevent the default paste behavior
    e.preventDefault();

    // Get the text content from the clipboard
    var pastedText = (e.originalEvent || e).clipboardData.getData("text/plain");

    console.log(pastedText);
    // var sanitizedText = pastedText.replace(/[^\w\s]/gi, '');
    // Insert the plain text into the editable div
    document.execCommand("insertText", false, pastedText);
  }
    </script>
    <script src="scripts/jquery-3.5.1.js"></script>
    <script src="scripts/clubb.js"></script>
    <script src="scripts/posts.js"></script>
  </body>
</html>
