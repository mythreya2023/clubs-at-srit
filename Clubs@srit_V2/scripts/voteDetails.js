$(document).ready(() => {
  const url = window.location.href;

  // Create a URLSearchParams object
  const urlParams = new URLSearchParams(window.location.search);

  // Get the value of the 'id' parameter
  const id = urlParams.get("cid");
  const eid = urlParams.get("evid");
  let cname = urlParams.get("cname");

  $(".reg-page-back-btn").click((e) => {
    window.location.href = `https://clubsatsrit.in/tools?cid=${id}&cname=${cname}&evid=${eid}`;
  });
  $(".view-voters-btn").click((e) => {
    window.location.href = `https://clubsatsrit.in/voters?cid=${id}&cname=${cname}&evid=${eid}`;
  });

  $(".voted-page-back-btn").click((e) => {
    window.location.href = `https://clubsatsrit.in/votingDetails?cid=${id}&cname=${cname}&evid=${eid}`;
  });
  var file_name = "",
    vfile_name = "",
    usid = "",
    rollNO = "";
  $.ajax({
    url: "https://clubsatsrit.in/php_scripts/voteManage",
    method: "post",
    data: {
      eid: eid,
      cid: id,
      fetch_file_type: "vote_count",
    },
    beforeSend: function () {
      // setting a timeout
      $("#preloader").show();
    },
    success: function (data) {
      $("#preloader").hide();
      if (data != 0) {
        data = JSON.parse(data);

        file_name = data.file;
        rollNO = data.rollno;
        vfile_name = data.vfile;
        usid = data.uid;
        $(".registered-count").text(data.reg);
        $(".attended-count").text(data.att);
        getDetails(rollNO);
      } else {
        $(".vote-cards-container").html(`<center style="margin-top:50px">
            <img src="https://www.shutterstock.com/image-vector/hand-drawn-illustration-hands-holding-600nw-2077629565.jpg" width="200" alt="">
            <p class="sub-heading" style='color:gray;margin:auto'>No Voting Created Yet!</p>
            <a href='https://clubsatsrit.in/createVoting?cid=${id}&cname=${cname}&evid=${eid}'><p class="sub-heading">Create Voting</p></a>
          </center>`);
        $(".view-voters-btn").hide();
      }
    },
  });
  function getDetails() {
    fetch(vfile_name)
      .then((response) => {
        // Check if the request was successful
        if (!response.ok) {
          throw new Error("Network response was not ok " + response.statusText);
        }
        return response.json();
      })
      .then((jsonData) => {
        console.log(jsonData);
        $(".submited-count").text(jsonData.submits.length);
        if (url.includes("votingDetails")) {
          createChartBlock(jsonData);
        } else if (url.includes("voters")) {
          console.log(jsonData.submits);
          getVoters(jsonData.submits);
        }
      })
      .catch((error) => {
        console.error(
          "There has been a problem with your fetch operation:",
          error
        );
      });
  }
  function getVoters(uids) {
    $.ajax({
      url: "https://clubsatsrit.in/php_scripts/voteManage",
      method: "post",
      data: {
        voted_user: "true",
        uids: uids.toString(),
      },
      success: (data) => {
        console.log(data);
        if (data != 0) {
          let users = JSON.parse(data);
          users.forEach((data) => {
            $(".registered-users").append(`
            <div class="team-member voter-user">
            <div class="mem-details">
              <img
                src="${data.dp}"
                alt=""
                class="img-sq userImage"
              />
              <div class="mem-txt-det">
                <p class="mem-name">${data.fname}</p>
                <p class="mem-role">${data.uname}</p>
              </div>
            </div>
            <div
              style="
                color: #504747;
                text-align: center;
                font-family: Inter;
                font-size: 15px;
                font-style: normal;
                font-weight: 400;
                line-height: normal;
                margin-right: 18px;
              "
            ></div>
          </div>
            `);
          });
        }
      },
    });
  }
});
function createChartBlock(jsonData) {
  let cards = jsonData.cards;
  let cardLen = jsonData.cards.length;
  let charts = [];
  for (let i = 0; i < cardLen; i++) {
    $(".vote-cards").append(`
        <div class="vote-card-box">
            <h3 class="sub-heading vcard-id">Card ${i + 1}/${cardLen}</h3>
            <div class="vote-card" style="padding-bottom: 15px">
            <div  class="text-box card-quest-div" >
            ${cards[i].quest}
            </div>
            <hr style="margin-top: -3px; width: 92%">
            <div class="card-options">
                <div id="bar_chart${
                  i + 1
                }" style="width: 100%; height: 300px;"></div>
            </div>
            </div>
        </div>
    `);
    var twoDArray = cards[i].options.map(function (item) {
      return [item.op, item.count];
    });
    let arr = [["Title", "Count"]];
    let data = arr.concat(twoDArray);
    let chartData = {
      title: cards[i].quest,
      data: data,
      div_id: `bar_chart${i + 1}`,
    };

    charts.push(chartData);
  }
  console.log(charts);
  for (let i = 0; i < cardLen; i++) {
    load_chart(charts[i]);
    console.log(charts[i]);
  }
}
function load_chart(chartData) {
  google.charts.load("current", { packages: ["bar"] });
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable(chartData.data);

    var options = {
      chart: {
        title: chartData.title,
        subtitle: chartData.subtitle,
      },
    };

    var chart = new google.charts.Bar(
      document.getElementById(chartData.div_id)
    );
    chart.draw(data, google.charts.Bar.convertOptions(options));
  }
}
