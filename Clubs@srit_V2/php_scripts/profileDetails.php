<?php
include 'posts.php';
class profile extends post{
    public function getProfile(){
        $conn=$this->connect();
        $uid=$_SESSION['ses_id'];
        $uid=$this->dec(mysqli_real_escape_string($conn,$uid),$this->iky);
        $stmt=$conn->prepare("SELECT * FROM users WHERE uid=?");
        $stmt->bind_param("s",$uid);
        $stmt->execute();
        $result=$stmt->get_result();
        if ($result->num_rows > 0) {
            $branchCodes=["05"=>"CSE","33"=>"CSM","32"=>"CSD","01"=>"CIVIL","03"=>"MECH","02"=>"EEE","04"=>"ECE"];
            $row=$result->fetch_assoc();
            $name=$this->dec($row['full_name'],$this->iky);
            $usname=$this->sbldc($row['user_name'],$this->iky);
            $email=$this->sbldc($row['mail_id'],$this->iky);
            $branch=$this->sbldc($row['branch'],$this->iky);
            $u_type=$row['u_type'];
            $dp=$row['dp']==""?"":$this->dec($row['dp'],$this->iky);
            $otherDetails=$this->extract_Clg_Mail($email);
            $details=["name"=>$name,"uName"=>$usname,"branche"=>$branch,"uType"=>$u_type,"dp"=>$dp];
            $userDetails=$otherDetails==false?$details:array_merge($details,$otherDetails);
            return json_encode($userDetails);
        }
        else{
            return 0;
        }
        
    }
    public function updateProfile($name_,$pwd_){
        $conn=$this->connect();
        $uid=$_SESSION['ses_id'];
        $uid=$this->dec(mysqli_real_escape_string($conn,$uid),$this->iky);
        $name=$this->enc(mysqli_real_escape_string($conn,$name_),$this->iky,'mtr');
        $pwd_=mysqli_real_escape_string($conn,$pwd_);
        $pwd=password_hash($pwd_, PASSWORD_DEFAULT);
        if($name_!=""&&$pwd_!=""){
            $stmt=$conn->prepare("UPDATE users SET full_name=?,pwd=? WHERE uid=?");
            $stmt->bind_param("sss",$name,$pwd,$uid);
        }elseif($name_!=""&&$pwd_==""){
            $stmt=$conn->prepare("UPDATE users SET full_name=? WHERE uid=?");
            $stmt->bind_param("ss",$name,$uid);
        }elseif($name_==""&&$pwd_!=""){
            $stmt=$conn->prepare("UPDATE users SET pwd=? WHERE uid=?");
            $stmt->bind_param("ss",$pwd,$uid);
        }
        $success=$stmt->execute();
        $stmt->close();
        return $success;
    }
    public function getSavedPosts($offset){
        $conn=$this->connect();
        $uid=$_SESSION['ses_id'];
        $uid=$this->dec(mysqli_real_escape_string($conn,$uid),$this->iky);
        $sid=$this->enc($uid,$this->iky,'idx');
        $stmt=$conn->prepare("SELECT post_id FROM post_interactions WHERE us_id=? LIMIT 5 OFFSET ?;");
        $stmt->bind_param("ss",$sid,$offset);
        $stmt->execute();
        $result=$stmt->get_result();
        if ($result->num_rows > 0) {
            $pids="";
            $rows = $result->fetch_all();
            $i=1;
            foreach($rows as $row){
                $pids.=$this->dec($row[0],$this->iky);
                $pids.=$i>=count($rows)?"":",";
                $i++;
            }
            return $this->allPosts("",$offset,"",$pids);
        }else{
            return 0;
        }
    }
    public function getRegEvents($offset){
        $conn=$this->connect();
        $uid=$_SESSION['ses_id'];
        $uid=$this->dec(mysqli_real_escape_string($conn,$uid),$this->iky);
        $sid=$this->sblen($uid,$this->iky,'idx');
        $stmt=$conn->prepare("SELECT eve_id FROM participants WHERE u_id=?  ORDER BY parti_id DESC LIMIT 10 OFFSET ?;");
        $stmt->bind_param("ss",$sid,$offset);
        $stmt->execute();
        $result=$stmt->get_result();
        if ($result->num_rows > 0) {
            $eids="";
            $rows = $result->fetch_all();
            $i=1;
            foreach($rows as $row){
                $eids.=$this->sbldc($row[0],$this->iky);
                $eids.=$i>=count($rows)?"":",";
                $i++;
            }
            return $this->allParticipatedEvents($offset,$eids);
        }else{
            return 0;
        }
    }
    protected function allParticipatedEvents($offset,$eids){
        $conn=$this->connect();
        $eids=mysqli_real_escape_string($conn,$eids);
        $eidsAll="";
        $i=1;
        $eids=explode(',', $eids);
        foreach ($eids as $eid) {
            $eidsAll.= "eve_id='".$eid."'";
            $eidsAll.= $i>=count($eids)? "" : " OR ";
            $i++;
        }
        $stmt = $conn->prepare("SELECT eve_id,club_id,c_name,eve_name,featureImg,eve_org_date FROM events WHERE $eidsAll");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $rows = $result->fetch_all();
            $events="";
            foreach($rows as $row){
                $cname=$this->dec($row[2],$this->iky);
                $cid=$this->sbldc($row[1],$this->iky);
                $cid=$this->enc($cid,$this->iky,'idx');
                $eid=$this->enc($row[0],$this->iky,'idx');
                $ename=$this->dec($row[3],$this->iky);
                $img=$this->dec($row[4],$this->iky);
                $date=$this->dateFormater($row[5],"display");
                $events.="<div class='event eve-nav-btn animi-btn' data-cname='$cname' data-eid='$eid' data-cid='$cid'>
                <img src='$img' class='event-img' />
                <div class='event-info'>
                  <div class='eve-name'>$ename</div>
                  <div class='eve-by'>
                    <span>by $cname</span>
                    <span>$date </span>
                  </div>
                </div>
                <span class='arrow-right'>
                  <svg
                    xmlns='http://www.w3.org/2000/svg'
                    width='24'
                    height='24'
                    viewBox='0 0 24 24'
                    fill='none'
                  >
                    <path
                      d='M9 18L15 12L9 6'
                      stroke='black'
                      stroke-width='2'
                      stroke-linecap='round'
                      stroke-linejoin='round'
                    />
                  </svg>
                </span>
              </div>";
            }
            return $events;
            // return 0;
        }else{
            return 0;
        }
    }
}

$user = new profile();

if(isset($_POST['pro_Details'])&&$_POST['pro_Details']=="true"){
echo $user->getProfile();
}

if(isset($_POST['updateDetails'],$_POST['name'],$_POST['pwd'])&&$_POST['updateDetails']=="true"){
    echo $user->updateProfile($_POST['name'],$_POST['pwd']);
}
    
if(isset($_POST['postAction'])&&$_POST['postAction']=="s_posts"&&isset($_POST['offSet'])){
    echo $user->getSavedPosts($_POST['offSet']);
}
if(isset($_POST['regEvents'])&&$_POST['regEvents']=="true"&&isset($_POST['offSet'])){
    echo $user->getRegEvents($_POST['offSet']);
}