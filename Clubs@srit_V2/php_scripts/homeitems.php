<?php
include 'db_conn.php';
class home extends dbconnect{
    public function all_items(){
        $conn=$this->connect();
        
        // fetching clubs.
        // $array=[];
        $clubs="";
        $events="";
        $stmt = $conn->prepare("SELECT cid,cname,cpic FROM clubs");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $rows = $result->fetch_all();
            foreach($rows as $row){
                $cid=$this->enc($row[0],$this->iky,'strix');
                $name=$this->sbldc($row[1],$this->iky);
                $img=$this->dec($row[2],$this->iky);
                $clubs.="<div class='club-card animi-btn' data-cid='$cid'>
                            <img src='$img' class='club-img' />
                            <h3 class='club-card-name'>$name</h3>
                        </div>";
            }
        }
        $stmt = $conn->prepare("SELECT eve_id,club_id,c_name,eve_name,featureImg FROM events WHERE eve_org_date >= CURDATE() ORDER BY eve_org_date ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $colors=["#f9c9a6","#c2bcff","#B2ECF4","#eae699"];
            $rows = $result->fetch_all();
            $i=0;
            foreach($rows as $row){
                $cname=$this->dec($row[2],$this->iky);
                $cid=$this->sbldc($row[1],$this->iky);
                $cid=$this->enc($cid,$this->iky,'idx');
                $eid=$this->enc($row[0],$this->iky,'idx');
                $ename=$this->dec($row[3],$this->iky);
                $img=$this->dec($row[4],$this->iky);
                $events.="<div class='eve-card animi-btn' style='background:$colors[$i]'  data-eid='$eid' data-cid='$cid' data-cname='$cname'>
                            <img src='$img' class='eve-img' />
                            <div class='eve-text'>
                            <h3 class='eve-card-name'>$ename</h3>
                            <p class='eve-card-by'>by $cname</p>
                            </div>
                        </div>";
                        
                if($i>count($colors)) $i=0;
                $i++;
            }
        }
        $uid=$_SESSION['ses_id'];
        $uid=$this->dec($uid,$this->iky);
        $stmt=$conn->prepare("SELECT full_name,user_name,dp FROM users WHERE uid=?");
        $stmt->bind_param("s",$uid);
        $stmt->execute();
        $result = $stmt->get_result();
        $array=[];
        if($result->num_rows>0){
            $row=$result->fetch_assoc();
            $array=["fname"=>$this->dec($row["full_name"],$this->iky),"uname"=>$this->sbldc($row["user_name"],$this->iky),"dp"=>$this->dec($row["dp"],$this->iky)];
                
        }
        return array_merge(["clubs"=>$clubs,"events"=>$events],$array);
    }
    public function getFollowingClubs(){
        $conn=$this->connect();
        $usid=$_SESSION['ses_id'];
        $usid=$this->dec(mysqli_real_escape_string($conn,$usid),$this->iky);
        $usid=$this->enc($usid,$this->iky,'idx');
        $stmt = $conn->prepare("SELECT clb_id FROM club_follow WHERE us_id=?");
        $stmt->bind_param("s",$usid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $cids="";
            $rows = $result->fetch_all();
            $i=1;
            foreach($rows as $row){
                $cids.=$this->dec($row[0],$this->iky);
                $cids.=$i>=count($rows)?"":",";
                $i++;
            }
            return $cids;
        }else{
            return "none";
        }
    }
}

$home = new home();

if(isset($_POST['home'])&&$_POST['home']=='all_items'){
    $clubsEves=$home->all_items();
    $follClubs=["Fclubs"=>$home->getFollowingClubs()];
    $data=array_merge($clubsEves,$follClubs);
    echo json_encode($data);
}
