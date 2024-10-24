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
$user_details=$v->getPerson($_SESSION['ses_id']);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
    />
    <title>Post Update</title>
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
    <style>
        .editableDiv {
            border: 1px solid #ccc;
            padding: 10px;
        }

        .editableDiv:empty:before {
            content: attr(data-placeholder);
            color: grey;
        }
    </style>
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
      <h3 class="heading">Post An Update</h3>
      <div class="event-details">
        <div class="club-post-header">
          <img
            src="<?php echo $user_details['dp'];?>"
            alt=""
            class="club-poster-img img-sq"
          />
          <div class="club-poster-details">
            <h3 class="poster-name"><?php echo $user_details["name"];?></h3>
            <p class="by-club-name">Tech Talks</p>
          </div>
        </div>
        <div
          id="post-text-area"
          class="text-area editableDiv"
          contentEditable="true"
          onpaste="handlePaste(event)"
          data-placeholder="What's on your mind now..?"
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
            class="eve-feat-img post-img"
            style="display:none"
          />
          <span class="add-icon" style="margin:auto;position:unset"
            >
            <svg
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
        <button
          type="button"
          class="post-update-btn main-eve-btn btn animi-btn"
        >
          Post
        </button>
      </div>
    </div>
    <div id="preloader" style="display:none;">
        <div class="spinner"></div>
    </div>
    <script>
       function handlePaste(e) {
    // Prevent the default paste behavior
    e.preventDefault();

    // Get the text content from the clipboard
    var pastedText = (e.originalEvent || e).clipboardData.getData("text/plain");

    console.log(pastedText);
    // Insert the plain text into the editable div
    document.execCommand("insertText", false, pastedText);
  }
    </script>
    <script src="scripts/jquery-3.5.1.js"></script>
    <script src="scripts/posts.js"></script>
  </body>
</html>
