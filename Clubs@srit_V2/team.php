<?php
session_start();
if(!isset($_SESSION['ses_id'])){
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
    <title>Team</title>
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
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon_io/site.webmanifest">
  </head>
  <body>
    <div class="team-members-container">
      <div class="header">
        <div class="team-page-back-btn back-btn animi-btn">
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
        <h3 class="heading">Team</h3>
        <div class="event-details">
          <div class="team-members">
            <div class="team-faculty">
              <!-- <div class="team-member">
                <div class="mem-details">
                  <img
                    src="images/IMG_20201021_221757.jpg"
                    alt=""
                    class="img-sq userImage"
                  />
                  <div class="mem-txt-det">
                    <p class="mem-name">Mythreya C</p>
                    <p class="mem-role">Graphic Designer</p>
                  </div>
                </div>
                <div
                  style="
                    color: #504747;
                    text-align: center;
                    font-family: Inter;
                    font-size: 15px;
                    font-style: normal;
                    font-weight: 600;
                    line-height: normal;
                    margin-right: 18px;
                  "
                >
                  Faculty
                </div>
              </div> -->
            </div>
            <div class="team-studs">
              <!-- <div class="team-member">
                <div class="mem-details" data-mem_id="214g1a0563">
                  <img
                    src="images/IMG_20201021_221757.jpg"
                    alt=""
                    class="img-sq userImage"
                  />
                  <div class="mem-txt-det">
                    <p class="mem-name">Mythreya C</p>
                    <p class="mem-role">Graphic Designer</p>
                  </div>
                </div>
                <div class="text-btn-remove animi-btn">Remove</div>
              </div> -->
            </div>
          </div>
          <button
            type="button"
            class="open-add-member-btn main-eve-btn btn animi-btn"
          >
            Add Member
          </button>
        </div>
      </div>
    </div>
    <div class="add-team-member-container">
      <div class="header">
        <div class="team-back-btn back-btn animi-btn">
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
        <h3 class="heading new_mem_heading" style="margin-bottom: 7px">Add Member</h3>
        <div class="event-details">
          <div class="search-user-box">
            <div class="user-search-box">
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
            <div class="mem-details searched-user-det" style="display:none">
              <img
                src="images/IMG_20201021_221757.jpg"
                alt=""
                id="searched-us-img"
                class="userImage img-sq"
              />
              <div class="mem-txt-det">
                <p class="mem-name us-srch-name"></p>
                <p class="mem-role us-srch-rollno"></p>
              </div>
            </div>
          </div>
          <div class="sub-heading" style="margin-top: 30px; font-size: 17px">
            Select a Role
          </div>
          <div class="role-selection-box">
            <div class="select-role">
              <label class="radio-label">
                <input
                  type="radio"
                  name="role-radio"
                  value="Public Relations"
                  class="radio-input"
                />
                <span class="radio-text">Public Relations</span>
              </label>
            </div>
            <div class="select-role">
              <label class="radio-label">
                <input
                  type="radio"
                  name="role-radio"
                  value="Graphic Designer"
                  class="radio-input"
                />
                <span class="radio-text">Graphic Designer</span>
              </label>
            </div>
            <div class="select-role">
              <label class="radio-label">
                <input
                  type="radio"
                  name="role-radio"
                  value="Anchor"
                  class="radio-input"
                />
                <span class="radio-text">Anchor</span>
              </label>
            </div>
            <div class="select-role">
              <label class="radio-label">
                <input
                  type="radio"
                  name="role-radio"
                  value="Timer"
                  class="radio-input"
                />
                <span class="radio-text">Timer</span>
              </label>
            </div>
            <div class="select-role">
              <label class="radio-label">
                <input
                  type="radio"
                  name="role-radio"
                  value="Reporter"
                  class="radio-input"
                />
                <span class="radio-text">Reporter</span>
              </label>
            </div>
          </div>
          <button
            type="button"
            class="add-team-member-btn main-eve-btn btn animi-btn"
          >
            Add Member
          </button>
          <button
            type="button" style="display:none"
            class="update-team-member-btn main-eve-btn btn animi-btn"
          >
            Update Member
          </button>
        </div>
      </div>
    </div>
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <script src="scripts/jquery-3.5.1.js"></script>
    <script src="scripts/teams.js"></script>
  </body>
</html>
