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
        <h3 class="heading">Create Voting</h3>
        <div class="vote-cards-container">
          <div class="vote-cards">
            <div class="vote-card-box">
              <div class="card-header">
                <h3 class="sub-heading vcard-id">Card 1</h3>
                <span
                  class="rem-card-btn animi-btn text-btn-remove"
                  style="margin-bottom: -10px"
                >
                  Remove</span
                >
              </div>
              <div class="vote-card">
                <input
                  type="text"
                  id=""
                  placeholder="Write Question...?"
                  class="text-box card-quest"
                />
                <hr style="margin-top: -3px; width: 92%" />
                <div class="card-options">
                  <div class="option-card">
                    <div class="option-select">
                      <div>
                        <label class="custom-radio-button">
                          <input
                            type="radio"
                            name="icon-radio"
                            class="opt-radio-btn"
                            value="option"
                            data-rollno1=""
                            data-rollno2=""
                          />
                          <span class="radio-icon"
                            ><i class="fa fa-circle"></i
                          ></span>
                          <span class="opt-txt" contenteditable="true"
                            >Option</span
                          >
                        </label>
                      </div>
                      <span class="drop-icon-btn animi-btn">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="20"
                          height="20"
                          viewBox="0 0 20 20"
                          fill="none"
                        >
                          <path
                            d="M5 7.5L10 12.5L15 7.5"
                            stroke="black"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                          />
                        </svg>
                      </span>
                    </div>
                    <div class="opt-roll-part">
                      <div class="opt-rollnos">
                        <input
                          type="text"
                          class="rollno-txt-box rollno1"
                          placeholder="Add Roll No 1"
                          style="border-bottom: 1px solid rgb(154, 140, 140)"
                        />
                        <input
                          type="text"
                          class="rollno-txt-box rollno2"
                          placeholder="Add Roll No 2"
                        />
                      </div>

                      <div
                        style="
                          display: flex;
                          justify-content: center;
                          margin-bottom: 10px;
                        "
                      >
                        <button class="done-opt-btn btn-mini animi-btn">
                          Done
                        </button>

                        <button class="remove-opt-btn btn-mini animi-btn">
                          Remove
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  style="
                    display: flex;
                    justify-content: center;
                    margin-bottom: 10px;
                    margin-top: 10px;
                  "
                >
                  <button class="btn-mini animi-btn add-opt-btn">
                    Add Option
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div
            style="
              display: flex;
              justify-content: center;
              margin-bottom: 10px;
              margin-top: 10px;
              width: 90%;
            "
          >
            <button class="add-card-btn btn-mini animi-btn">Add Card</button>
          </div>
        </div>
        <button
          type="button"
          id="create-voting-btn"
          class="btn tool-nav-btn animi-btn"
        >
          Create Voting
        </button>
        <button
          type="button"
          id="update-voting-btn"
          class="btn tool-nav-btn animi-btn"
          style="display: none"
        >
          Update Voting
        </button>
        <button
          type="button"
          id="make-voting-live-btn"
          class="btn tool-nav-btn btn-orng animi-btn"
          style="display:none;"
        >
          Make Voting LIve
        </button>
        <button
          type="button"
          id="delete-voting-btn"
          class="btn tool-nav-btn btn-red animi-btn"
          style="display:none;"
        >
          Delete Voting
        </button>
      </div>
    </div>
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <script src="scripts/jquery-3.5.1.js"></script>
    <script src="scripts/createVoting.js"></script>
  </body>
</html>
