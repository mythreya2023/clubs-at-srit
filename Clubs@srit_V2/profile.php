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
    <!--<meta name="theme-color" content="#d6edf0">-->
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#d6edf0">
<meta name="theme-color" media="(prefers-color-scheme: dark)"  content="#d6edf0">
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
    <link rel="manifest" href="manifest.json" />
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon_io/site.webmanifest">
  </head>
  <body>
    <div class="profile-container">
      <div class="header animi-btn back-arrow">
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
        <h2 class="heading">Profile</h2>
      </div>
      <div class="logout-btn" style="position:absolute;right:30px; top:20px;">
      <a href="http://localhost/Clubs@srit_v2php_scripts/logout" style="text-decoration:none;">
        <span class="text-btn-remove animi-btn">
        <!--<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAABnUlEQVR4nO2Wu0oDQRiFT6lgOgs13lCRvItaiDfUJwhRQV18BwsLOxULsVDxXmgeQAvxBQJWgmLtDYwxiSs/nIUlJDP/bkZByIFpNt9/zmT3nwvQ0D/QLYCb3wzoALANYLXiuc8R1hrZ9npDxwC8MuBeEfzEZ1IzGjd0HsA3jc4AdCuChTnnc6nNRA2dYqGMxRpMteBAS6H6cW2ozPqFpgsGzhQMTlh+fwbQpQneZcGxhbMFi07I7NhCZWZlAPkq3zROcA+ATwAlAEkTuEKzPdsMlcGifXKeCcoSmnAYPEnuwgQ9Eup1GNxH7sEE5Qk1OQxuJvdhgt4IJRSG1wCuFFyCnuJdU3eEBuFOKXrmNM017TB4VtNcGUIHDoMP6Zk2QZ0AigAK7MZ6NUCvom0DEW0pt0yNTum1oT34g+5eriPUo4ccOG3aohHu2aWY4R5rxWMoavEcC32eMv3Kbxq83nKci0Cg4dDVp8AOneHabOFIcckcAfgKvd7I/7RSrQDW2Zm+ZQiz6eKyF1aSa/GSu9A7R46bQ1qzZBrCX+sHtgqQFu26S5EAAAAASUVORK5CYII=">-->
        Logout
        </span></a>
      </div>
      <center>
        <div class="profile-picture">
          <img
            src=""
            alt="Profile Picture"
            class="img-round profile_pic"
            style="width: 150px; height: 150px; margin: auto"
          />
          <label><input type="file" id="fileInput1" accept="image/*" style="display:none;" />
          <span class="camera-icon animi-btn"
            ><svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
            >
              <path
                d="M23 19C23 19.5304 22.7893 20.0391 22.4142 20.4142C22.0391 20.7893 21.5304 21 21 21H3C2.46957 21 1.96086 20.7893 1.58579 20.4142C1.21071 20.0391 1 19.5304 1 19V8C1 7.46957 1.21071 6.96086 1.58579 6.58579C1.96086 6.21071 2.46957 6 3 6H7L9 3H15L17 6H21C21.5304 6 22.0391 6.21071 22.4142 6.58579C22.7893 6.96086 23 7.46957 23 8V19Z"
                stroke="white"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
              <path
                d="M12 17C14.2091 17 16 15.2091 16 13C16 10.7909 14.2091 9 12 9C9.79086 9 8 10.7909 8 13C8 15.2091 9.79086 17 12 17Z"
                stroke="white"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
          </span>
          </label>
        </div>
        <div class="profile-name-id">
          <h3 class="profile-name"></h3>
          <p class="profile-id"></p>
        </div>
      </center>
      <div class="profile-details">
        <div class="detail-item usdetails">
          <span class="detail-item-name">Details</span>
          <span class="item-icon animi-btn" id="details-down">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 20"
              fill="none"
            >
              <path
                d="M6 9L12 15L18 9"
                stroke="black"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
          </span>
        </div>
        <div class="person-details">
          <table class="user-details">
            <tr>
              <td style="font-weight: bold; color: darkslategray">Year</td>
              <td id="stu_year"></td>
            </tr>
            <tr>
              <td style="font-weight: bold; color: darkslategray">Branch</td>
              <td id="stu_branch"></td>
            </tr>
            <tr>
              <td style="font-weight: bold; color: darkslategray">Type</td>
              <td id="stu_type"></td>
            </tr>
            <tr>
              <td style="font-weight: bold; color: darkslategray">Roll No</td>
              <td id="stu_rollno"></td>
            </tr>
          </table>
        </div>
        <div class="detail-item-divider" style="border-bottom: 1px solid #9d9a9a98"></div>
        <div class="detail-item">
          <span class="detail-item-name">Edit Profile Details</span>
          <span class="item-icon animi-btn edit-profile-btn"
            ><svg
              xmlns="http://www.w3.org/2000/svg"
              width="17"
              height="17"
              viewBox="0 0 17 17"
              fill="none"
            >
              <g clip-path="url(#clip0_28_102)">
                <path
                  d="M12.0417 2.125C12.2277 1.93896 12.4486 1.79139 12.6916 1.6907C12.9347 1.59002 13.1952 1.5382 13.4583 1.5382C13.7214 1.5382 13.9819 1.59002 14.225 1.6907C14.4681 1.79139 14.6889 1.93896 14.875 2.125C15.061 2.31104 15.2086 2.5319 15.3093 2.77497C15.41 3.01804 15.4618 3.27857 15.4618 3.54167C15.4618 3.80477 15.41 4.06529 15.3093 4.30836C15.2086 4.55143 15.061 4.77229 14.875 4.95833L5.31249 14.5208L1.41666 15.5833L2.47916 11.6875L12.0417 2.125Z"
                  stroke="black"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </g>
              <defs>
                <clipPath id="clip0_28_102">
                  <rect width="17" height="17" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </span>
        </div>
      </div>
      <div class="profile-btns">
        <div class="saved-posts-btn animi-btn">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            style="margin-top: 8px"
          >
            <path
              d="M19 21L12 16L5 21V5C5 4.46957 5.21071 3.96086 5.58579 3.58579C5.96086 3.21071 6.46957 3 7 3H17C17.5304 3 18.0391 3.21071 18.4142 3.58579C18.7893 3.96086 19 4.46957 19 5V21Z"
              stroke="#221E1E"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              fill="none"
            />
          </svg>
        </div>
        <div class="participations-tab-btn animi-btn">
          <p class="ptb-txt">Participations</p>
        </div>
      </div>
      <div class="saved-posts updates-display" >
        
      <center style="margin-top:50px">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/post-interface-5266761-4397160.png" width="200" alt="">
            <p class="sub-heading" style='color:gray;margin:auto'>No Posts Saved Yet!</p>
          </center>
      </div>
      <div class="participations-tab">
        <center>
          <p class="sub-heading">No Participations!</p>
        </center>
      </div>
    </div>
    <style>
    .edit_prof_popUp {
      display:none;
        position: fixed;
        top: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        background: #ffffff7a;
    }
    .edit_prof_box {
        width: 80%;
        height: fit-content;
        background: white;
        border-radius: 6px;
        border: 1px solid gray;
        padding: 29px 10px;
        padding-top:10px;
        margin: auto;
        position: fixed;
        transform: translate(-50%, -50%);
        top: 50%;
        left: 50%;
    }
    .save-changes-btn{
      margin-top: 20px;
    }
    .text-box{
      margin-left:5%;
    }
    .sub-heading{
      margin-top:5%;
      margin-left:5%;
    }
    .edit-details-header {
    display: flex;
    justify-content: space-between;
    align-content: center;
    align-items: center;
    flex-direction: row;
    width:90%;
    margin:auto;
    }
    </style>
    <div class="edit_prof_popUp">
      <div class="edit_prof_box">
        <div class="edit-details-header">
          <h3 >Edit Details</h3>
          <span  class="close_btn" ><img class="close_btn" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAvklEQVR4nO2VvQ7CIBRGz9M0rlYdLIbBn2iivv8TuFmNxladNCSYENKaKgUWzsTwhcO9AS4kEomADAD5Q34E5K7SDDgAFbDokB8CJXAGxi5iCdyAl5bPv2QnWqiyD2CDIwK46g1rYNnS3lJnnsDeVdokV9VsLenJh/RDAVwM+S6EtKnyu3EQtV7jmakhNKv3ThFDLGK0Wlg3O8jlEtZbXoV4wzNDWrV8IHnfchnry8z+GBLHPoZEtLGYSCToyhtdZ1aPJU42rgAAAABJRU5ErkJggg=="></span>
        </div>
        <h3 class="sub-heading">Edit Name</h3>
        <input type="text" class="text-box" id="edit_name" placholder="Full Name" autocomplete="off">
        <h3 class="sub-heading">Change Password</h3>
        <input type="password" class="text-box" id="new_Pwd" placeholder="New Password" autocomplete="off">
        <input type="password" class="text-box" id="conf_pwd" placeholder="Confirm Password" autocomplete="off">
        <button type="button" class="save-changes-btn btn animi-btn">Save Changes</button>
      </div>
    </div>
    <div id="preloader">
        <div class="spinner"></div>
    </div>
    <script src="scripts/jquery-3.5.1.js"></script>
    <script src="scripts/profile.js"></script>
  </body>
</html>
