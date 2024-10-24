document.addEventListener("DOMContentLoaded", () => {
  // Fetch event details and populate the table
  const url = window.location.href;

  // Create a URLSearchParams object
  const urlParams = new URLSearchParams(window.location.search);

  // Get the value of the 'id' parameter
  const id = urlParams.get("cid");
  const eid = urlParams.get("evid");
  let cname = urlParams.get("cname");
  const cordName = urlParams.get("cord");

  $(".eve_Cord").text(cordName);

  offset = 0;
  let srch = "///dsf_get_attended_for_report_gen_dsf///"; // this line is sooo important, if this code miss a single character then report generation will get error.
  registerations(eid, srch, offset);
  function registerations(eid, person, offset) {
    $.ajax({
      url: "https://clubsatsrit.in/php_scripts/eveDetails",
      method: "POST",
      data: {
        getRegistered: "true",
        evid: eid,
        srch: person,
        offset: offset,
      },
      success: function (data) {
        console.log(data);
        data = JSON.parse(data);
        data.forEach((data, idx) => {
          $(".students_attended").append(`
              <tr >
                <td class='stu'>${idx + 1}</td>
              <td class='stu'>${data.name}</td>
                <td class='stu'>${data.u_name}</td>
              <tr>
            `);
        });
        console.log(data);
      },
    });
  }

  $.ajax({
    url: "https://clubsatsrit.in/php_scripts/eveDetails",
    method: "post",
    data: {
      eveDetails: "true",
      cid: id,
      evid: eid,
    },
    success: function (data) {
      console.log(data);
      if (data != "") {
        data = JSON.parse(data);
        $(".eve_name").text(data.ename);
        $(".org_by").text(data.cname);
        $(".Breif_description").html(data.about_eve);
        $(".eve_date").text(data.date);
        $(".eve_no_part").text(data.regCount);
        $(".eve_ta").text(`${data.years} year ${data.branches}.`);
      }
    },
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
    success: function (data) {
      if (data != 0) {
        data = JSON.parse(data);

        file_name = data.file;
        rollNO = data.rollno;
        vfile_name = data.vfile;
        usid = data.uid;
        console.log(data);
        getDetails();
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
        createChartBlock(jsonData);
      })
      .catch((error) => {
        console.error(
          "There has been a problem with your fetch operation:",
          error
        );
      });
  }

  $(".download_btn").click(() => {
    window.print();
  });
});
function createChartBlock(jsonData) {
  let cards = jsonData.cards;
  let cardLen = jsonData.cards.length;
  let charts = [];
  for (let i = 0; i < cardLen; i++) {
    $(".vote-cards").append(`
      <div class="vote-card-box" style="background:white;">
          <div class="vote-card" style="background:white;padding-bottom: 15px">
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
