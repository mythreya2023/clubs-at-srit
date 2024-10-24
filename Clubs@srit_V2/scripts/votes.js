$(document).ready(() => {
  $(".reg-page-back-btn").click((e) => {
    window.history.back();
  });
  const url = window.location.href;

  // Create a URLSearchParams object
  const urlParams = new URLSearchParams(window.location.search);

  // Get the value of the 'id' parameter
  const id = urlParams.get("cid");
  const eid = urlParams.get("evid");
  let cname = urlParams.get("cname");

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
      fetch_file_type: "vote_cards",
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
        checkIfSubmited(rollNO);
      } else {
        $(".voting-area").hide();
        $(".vote-submission-msg").show();
      }
    },
  });
  function checkIfSubmited(rollNo) {
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
        if (!jsonData.submits.includes(usid)) {
          loadCreatedFile(rollNo);
        } else {
          $(".voting-area").hide();
          $(".vote-submission-msg").show();
        }
      })
      .catch((error) => {
        console.error(
          "There has been a problem with your fetch operation:",
          error
        );
      });
  }
  function loadCreatedFile(rollNo) {
    // let file_name = "file_65994be744381_a020c.json";
    // file_name = "file_65929176def0c_ae2c6.json";
    fetch(file_name)
      .then((response) => {
        // Check if the request was successful
        if (!response.ok) {
          throw new Error("Network response was not ok " + response.statusText);
        }
        return response.json();
      })
      .then((jsonData) => {
        console.log(jsonData);
        createVoteBlocks(JSON.parse(jsonData), rollNo);
      })
      .catch((error) => {
        console.error(
          "There has been a problem with your fetch operation:",
          error
        );
      });
  }
  function createVoteBlocks(jsonObj, myrollno = "") {
    console.log(jsonObj);
    let cardLen = jsonObj.cards.length;
    for (let i = 0; i < cardLen; i++) {
      $(".vote-cards").append(`
    <div class="vote-card-box">
        <h3 class="sub-heading vcard-id">Card ${i + 1}/${cardLen}</h3>
        <div class="vote-card" style="padding-bottom: 15px">
        <div  class="text-box card-quest-div" >
        ${jsonObj.cards[i].quest}
        </div>
        <hr style="margin-top: -3px; width: 92%">
        <div class="card-options">
        
        </div>
        </div>
    </div>
`);
    }
    document.querySelectorAll(".vote-card-box").forEach((ele, idx) => {
      jsonObj.cards[idx].options.forEach((opts) => {
        // console.log($(ele).find(".vcard-id").text());
        if (opts.rollno1 != myrollno && opts.rollno2 != myrollno) {
          $(ele).find(".card-options").append(`
            <div class="option-card">
            <div class="option-select">
                <div>
                <label class="custom-radio-button">
                    <input type="radio" name="icon-radio-${
                      idx + 1
                    }" class="opt-radio-btn" value="${opts.op}" data-rollno1="${
            opts.rollno1
          }" data-rollno2="${opts.rollno2}">
                    <span class="radio-icon"><i class="fa fa-circle"></i></span>
                    <span class="opt-txt">${opts.op}</span>
                </label>
                <span></span>
                </div>
            </div>
            </div>
        `);
        }
      });
    });

    var voteArray = [];
    $(".submit-vote-btn").click((e) => {
      voteArray = [];
      let cardCount = $(".vote-card-box").length;
      document.querySelectorAll(".vote-card-box").forEach((ele, idx) => {
        let quest = $(ele).find(".card-quest-div").text();
        let radioSelected = $(ele)
          .find(`input[name="icon-radio-${idx + 1}"]:checked`)
          .val();
        if (radioSelected != null && radioSelected != undefined) {
          voteArray.push(radioSelected);
        }
      });
      if (voteArray.length == cardCount) {
        let vf_name = vfile_name.split("https://clubsatsrit.in/voteCounts/")[1];
        fetch("php_scripts/voteCount", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            filename: vf_name,
          },
          body: JSON.stringify(voteArray),
        })
          .then((response) => response.text())
          .then((data) => {
            console.log(data);
            if (data == 1) {
              $(".voting-area").hide();
              $(".vote-submission-msg").show();
            }
          })
          .catch((error) => console.error("Error:", error));
      } else {
        alert("Select atleast one option in all cards");
      }
    });
  }
});
