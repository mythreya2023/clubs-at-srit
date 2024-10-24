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
// YlE9PTo6MTIzNDU2Nzg5MTAxMTEyMQ==
$update=false;
if(isset($_GET['evid'])&&$_GET['evid']!=""){
$update=true;
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
    <title>Profile Page</title>
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
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon_io/site.webmanifest">
  </head>
  <body>
    <div class="header">
      <div class="back-btn animi-btn">
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
      <?php if($update){?>
      <h3 class="heading">Update Event</h3>
      <?php }else{?>
      <h3 class="heading">Create Event</h3>
      <?php }?>
      <div class="event-details">
        <div class="sub-heading">Event Name</div>
        <input
          type="text"
          name="eve_name"
          id="eve_name"
          class="text-box"
          placeholder="Event Name"
        />
        <div class="sub-heading">About Event</div>
        <!-- <textarea
          name=""
          id="eve-desc"
          class="text-area"
          placeholder="Short descripton about the event..."
        ></textarea> -->
        <div
          id="eve-desc"
          class="text-area editableDiv"
          contentEditable="true"
          onpaste="handlePaste(event)"
          data-placeholder="Short descripton about the club..."
        ></div>
        <div class="sub-heading">Add Image</div>
        
        <label><input type="file" id="fileInput1" accept="image/*" style="display:none;" />
        <div class="text-area img-area" style="
          padding: 0;
          background-image: url('');
          overflow: hidden;
          background-position: center;
          background-repeat: no-repeat;
          background-size: cover;
          text-align: center;
          display: flex;">
          <img
            src=""
            alt=""
            class="eve-feat-img"
            style="display:none"
          />
          <span class="add-icon" style="margin:auto;position:unset"
            ><svg
              xmlns="http://www.w3.org/2000/svg"
              width="38"
              height="38"
              viewBox="0 0 38 38"
              fill="none"
            >
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M26.9945 0.639625C33.1824 0.639625 37.34 4.98804 37.34 11.4594V26.5406C37.34 33.012 33.1824 37.3604 26.9945 37.3604H11.0054C4.81756 37.3604 0.659912 33.012 0.659912 26.5406V11.4594C0.659912 4.98804 4.81756 0.639625 11.0054 0.639625H26.9945ZM26.9945 3.20154H11.0054C6.27623 3.20154 3.21899 6.4415 3.21899 11.4594V26.5406C3.21899 31.5585 6.27623 34.7985 11.0054 34.7985H26.9945C31.7254 34.7985 34.7809 31.5585 34.7809 26.5406V11.4594C34.7809 6.4415 31.7254 3.20154 26.9945 3.20154ZM19 11.4463C19.7063 11.4463 20.2795 12.0202 20.2795 12.7272V17.702L25.2552 17.7023C25.9615 17.7023 26.5347 18.2762 26.5347 18.9833C26.5347 19.6904 25.9615 20.2642 25.2552 20.2642L20.2795 20.2639V25.2413C20.2795 25.9484 19.7063 26.5223 19 26.5223C18.2937 26.5223 17.7204 25.9484 17.7204 25.2413V20.2639L12.7447 20.2642C12.0367 20.2642 11.4652 19.6904 11.4652 18.9833C11.4652 18.2762 12.0367 17.7023 12.7447 17.7023L17.7204 17.702V12.7272C17.7204 12.0202 18.2937 11.4463 19 11.4463Z"
                fill="#ffff"
              />
            </svg>
          </span>
        </div>
      </label>
        <div class="sub-heading">Event Date</div>
        <div class="date-of-eve">
          <input
            type="number"
            name="date"
            id="date"
            class="text-box"
            placeholder="Date"
          />
          <input
            type="number"
            name="Month"
            id="Month"
            class="text-box"
            placeholder="Month"
          />
          <input
            type="number"
            name="Year"
            id="Year"
            class="text-box"
            placeholder="Year"
          />
        </div>
        <div class="sub-heading">Event For</div>
        <div class="custom-select text-box">
          <select class="eve-for">
            <option value="Membership">Membership Only</option>
            <option value="Everyone">Everyone</option>
            <!-- Add more options here -->
          </select>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
          >
            <path
              d="M18.0002 8.99988L12.0002 14.9999L6.00024 8.99988"
              stroke="#181515"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            ></path>
          </svg>
        </div>
        <div class="sub-heading">Branch</div>
        <!-- <div class="custom-select text-box">
          <select class="branch-select">
            <option value="All">All Branches</option>
            <option value="CSE">CSE</option>
            <option value="CSM">CSM</option>
            <option value="CSD">CSD</option>
            <option value="ECE">ECE</option>
            <option value="EEE">EEE</option>
            <option value="MEC">MEC</option>
            <option value="CIV">CIV</option>
          </select>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
          >
            <path
              d="M18.0002 8.99988L12.0002 14.9999L6.00024 8.99988"
              stroke="#181515"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg> 
        </div>-->

        <div class="year-cbs">
          <label class="btn-check">
            <input
              type="checkbox"
              id="cb1"
              name="branch_selec"
              class="checkbox-btn"
              value="CSE"
            />
            <p class="btn-cb animi-btn">CSE</p> </label
          ><label class="btn-check">
            <input
              type="checkbox"
              id="cb1"
              name="branch_selec"
              class="checkbox-btn"
              value="CSD"
            />
            <p class="btn-cb animi-btn">CSD</p> </label
          ><label class="btn-check">
            <input
              type="checkbox"
              id="cb1"
              name="branch_selec"
              class="checkbox-btn"
              value="CSM"
            />
            <p class="btn-cb animi-btn">CSM</p> </label
          ><label class="btn-check">
            <input
              type="checkbox"
              id="cb1"
              name="branch_selec"
              class="checkbox-btn"
              value="ECE"
            />
            <p class="btn-cb animi-btn">ECE</p> </label
          ><label class="btn-check">
            <input
              type="checkbox"
              id="cb1"
              name="branch_selec"
              class="checkbox-btn"
              value="EEE"
            />
            <p class="btn-cb animi-btn">EEE</p> </label
          ><label class="btn-check">
            <input
              type="checkbox"
              id="cb1"
              name="branch_selec"
              class="checkbox-btn"
              value="MEC"
            />
            <p class="btn-cb animi-btn">MEC</p> </label
          ><label class="btn-check">
            <input
              type="checkbox"
              id="cb1"
              name="branch_selec"
              class="checkbox-btn"
              value="CIVIL"
            />
            <p class="btn-cb animi-btn">CIVIL</p>
          </label>
        </div>
        <div class="sub-heading">For Which Year?</div>
        <div class="year-cbs">
          <label class="btn-check">
            <input
              type="checkbox"
              id="cb1"
              name="year-cb"
              class="checkbox-btn"
              value="1st"
            />
            <p class="btn-cb animi-btn">1st</p>
          </label>
          <label class="btn-check">
            <input
              type="checkbox"
              id="cb2"
              name="year-cb"
              class="checkbox-btn"
              value="2nd"
            />
            <p class="btn-cb animi-btn">2nd</p>
          </label>
          <label class="btn-check">
            <input
              type="checkbox"
              id="cb3"
              name="year-cb"
              class="checkbox-btn"
              value="3rd"
            />
            <p class="btn-cb animi-btn">3rd</p>
          </label>
          <label class="btn-check">
            <input
              type="checkbox"
              id="cb4"
              name="year-cb"
              class="checkbox-btn"
              value="4th"
            />
            <p class="btn-cb animi-btn">4th</p>
          </label>
        </div>
        <div class="sub-heading add-CTA-opt">
          Add Call To Action Button
          <label class="custom-checkbox">
            <input type="checkbox" id="cta" class="checkbox-input" />
            <i class="fa-regular fa-square"></i>
            <i class="fa-solid fa-square-check"></i>
          </label>
        </div>
        <div class="sub-heading cta-checked">Call To Action Link</div>
        <input
          type="text"
          id="CTA-link"
          placeholder="Add Link"
          class="text-box"
        />
        <?php if($update){?>
        <button type="button" class="update-eve-btn main-eve-btn btn animi-btn">
          Update Event
        </button>
      <?php }else{?>
        <button type="button" class="create-eve-btn main-eve-btn btn animi-btn">
          Create Event
        </button>
      <?php }?>
      </div>
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
    <script src="scripts/eventDetails.js"></script>
  </body>
</html>
