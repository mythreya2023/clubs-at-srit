<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Event Report</title>
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
    <link rel="stylesheet" href="styles/creation.css" />
    <link rel="stylesheet" href="styles/tools.css" />
    <link rel="stylesheet" href="styles/report.css" />
</head>
<body>
    <div>
        <img class="logo" src="https://www.srit.ac.in/wp-content/uploads/2021/05/SRIT-STRIP-JPG.jpg" alt="" />
        <hr />
    </div>
    <div class="container" style="margin-bottom: 20px;">
        <h1>Event Report</h1>
        <div class='tab'>
            <table class="r" style="margin:auto; width: fit-content;">
                <tr>
                    <td class="r"><label>Event Name</label></td>
                    <td class="r">:</td>
                    <td class="r eve_name"></td>
                </tr>
                <tr>
                    <td class="r"><label>Venue</label></td>
                    <td class="r">:</td>
                    <td class="r">Srinivasa Ramanujan Institute of Technology</td>
                </tr>
                <tr>
                    <td class="r"><label>Event Date</label></td>
                    <td class="r">:</td>
                    <td class="r eve_date"></td>
                </tr>
                <tr>
                    <td class="r"><label>Event Organized by</label></td>
                    <td class="r">:</td>
                    <td class="r org_by"></td>
                </tr>
                <tr>
                    <td class="r"><label>Target Audience</label></td>
                    <td class="r">:</td>
                    <td class="r eve_ta"></td>
                </tr>
                <tr>
                    <td class="r"><label>Event Coordinator(s)</label></td>
                    <td class="r">:</td>
                    <td class="r eve_Cord"></td>
                </tr>
                <tr>
                    <td class="r"><label>No. of Participants</label></td>
                    <td class="r">:</td>
                    <td class="r eve_no_part"></td>
                </tr>
            </table>
            
        </div>
        <div class="eve_discri">
        <h4>
            <center>Brief description about the Event</center>
        </h4>
        <div class="Breif_description" style="padding: 0 20px 0 20px;">

        </div>
        </div>
        <div class="partici_big_box">
        <h3 style="margin-top: 80px;"><center>Participants Attended</center></h3>
        <div class="participants_list">
        <table style="margin:auto; width: fit-content;">
                <thead class="stu_h">
                    <tr>
                        <th>S.NO</th>
                        <th>Name</th>
                        <th>Roll no.</th>
                    </tr>
                </thead>
                <tbody class="students_attended">

                </tbody>
            </table>
        </div>
        <div id="eventDetails">
            <!-- Event details will be populated here -->
        </div>
        </div>
        <div class="feedBack_box">
        <h3 style="margin-top: 80px;"><center>Feedback/Voting Report</center></h3>
        <div class="vote-cards-container">
          <div class="vote-cards">
            
          </div>
        </div>
        </div>
        <div class="signature">
            <div class="eve_cod_sig">Event Coordinators</div>
            <div class="eve_cod_sig">Head of the Department</div>
        </div>
    </div>
    <button class="btn mini-btn animi-btn download_btn">Download as PDF</button>
    <script src="scripts/jquery-3.5.1.js"></script>  
    <script
      type="text/javascript"
      src="https://www.gstatic.com/charts/loader.js"
    ></script>
    <script src="scripts/reportGen.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

</body>
</html>
