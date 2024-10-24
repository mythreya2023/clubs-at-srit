<?php
session_start();
include 'db_conn.php';
class eveDetails extends dbconnect{
    public function createEve($cid,$cname,$evname,$about,$img,$date,$eveType,$branch,$year,$linkCTA){
       $conn=$this->connect();
       $cid_NL=mysqli_real_escape_string($conn,$cid);
        $cid_N=$this->dec($cid_NL,$this->iky);
        $cid=$this->sblen($cid_N,$this->iky,'idx');
        $cname_N=mysqli_real_escape_string($conn,$cname);
        $cname=$this->enc($cname_N,$this->iky,'mtr');
        $N_name=mysqli_real_escape_string($conn,$evname);
        $evname=$this->enc($N_name,$this->iky,'mtr');
        $eve_about=mysqli_real_escape_string($conn,$about);
        $about=$this->enc($eve_about,$this->iky,'mtr');
        $Ndate=mysqli_real_escape_string($conn,$date);
        $date=$this->dateFormater($Ndate,"write");
        // $date=$this->enc($Ndate,$this->iky,'mtr');
        $eig=mysqli_real_escape_string($conn,$img);
        $img=$this->enc($eig,$this->iky,'mtr');
        $eveType=$this->enc(mysqli_real_escape_string($conn,$eveType),$this->iky,'idx');
        $branch_n=mysqli_real_escape_string($conn,$branch);
        $branch=$this->enc($branch_n,$this->iky,'mtr');
        $year=$this->enc(mysqli_real_escape_string($conn,$year),$this->iky,'mtr');
        $linkCTA=$linkCTA==""?"":$this->enc(mysqli_real_escape_string($conn,$linkCTA),$this->iky,'mtr');
        $stmt = $conn->prepare("INSERT INTO events (club_id,c_name,eve_name, about_eve, featureImg, eve_org_date, eve_club_typ,branchTo,yearTo,CTAlink) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssssss", $cid,$cname,$evname,$about,$img,$date,$eveType,$branch,$year,$linkCTA);
        $success = $stmt->execute();
        if($success==1){
            $msg="A new event $N_name is going to be conducted on $Ndate for $branch_n.";
            $eid=$this->enc($conn->insert_id,$this->iky,'idx');
            $link="https://clubsatsrit.in/event.php?cid=$cid_NL&cname=$cname_N&evid=$eid";
            $this->notify($cid_N,"",$msg,$link,"followers");

            
            
            $CName = ucwords($cname_N);
            $CName = str_replace("_"," ",$CName);
            $subj = "New Event By $CName";

            $text = "<center><h1>$N_name</h1></center><p>Organised on $date</p><img class='logo' src='$eig' alt='Clubs@SRIT Logo'><p>$eve_about</p>";
            
            $msg = $text."<a href='$link' class='action-btn'>Register Now</a>";
            $this->mail_notify($cid_N,"",$subj,$msg,"followers");

            return $this->enc($conn->insert_id,$this->iky,'idx');
        }else{
            return 0;
        }
    }
   
    public function updateEve($cid,$eveid,$evname,$about,$img,$date,$eveType,$branch,$year,$linkCTA){
        $conn=$this->connect();
       $cid_NL=mysqli_real_escape_string($conn,$cid);
        $cid_N=$this->dec($cid_NL,$this->iky);
        $cid=$this->sblen($cid_N,$this->iky,'idx');
        $eveid=$this->dec(mysqli_real_escape_string($conn,$eveid),$this->iky);
        $N_name=mysqli_real_escape_string($conn,$evname);
        $evname=$this->enc($N_name,$this->iky,'mtr');
        $about=$this->enc(mysqli_real_escape_string($conn,$about),$this->iky,'mtr');
        $Ndate=mysqli_real_escape_string($conn,$date);
        $date=$this->dateFormater($Ndate,"write");
        // $date=$this->enc($Ndate,$this->iky,'mtr');
        $img=$this->enc(mysqli_real_escape_string($conn,$img),$this->iky,'mtr');
        $eveType=$this->enc(mysqli_real_escape_string($conn,$eveType),$this->iky,'idx');
        $branch_n=mysqli_real_escape_string($conn,$branch);
        $branch=$this->enc($branch_n,$this->iky,'mtr');
        $year=$this->enc(mysqli_real_escape_string($conn,$year),$this->iky,'mtr');
        $linkCTA=$linkCTA==""?"":$this->enc(mysqli_real_escape_string($conn,$linkCTA),$this->iky,'mtr');
        $stmt = $conn->prepare("UPDATE events SET eve_name=?, about_eve=?, featureImg=?, eve_org_date=?, eve_club_typ=?,branchTo=?,yearTo=?,CTAlink=? WHERE eve_id=? AND club_id=?");
        $stmt->bind_param("ssssssssss", $evname,$about,$img,$date,$eveType,$branch,$year,$linkCTA,$eveid,$cid);
        $success = $stmt->execute();
        $stmt->close();
        
        $msg="The event $N_name has been updated, its going to be conducted on $Ndate for $branch_n.";
        $eid=$this->enc($conn->insert_id,$this->iky,'idx');
        $link="https://clubsatsrit.in/event.php?cid=$cid_NL&evid=$eid";
        $this->notify($cid_N,"",$msg,$link,"followers");
        return $success;
    }
    public function getEveDetails($cid,$eveid){
        $conn=$this->connect();
        $cid=$this->dec(mysqli_real_escape_string($conn,$cid),$this->iky);
        $Club_id=$this->enc($cid,$this->iky,'idx');
        $cid=$this->sblen($cid,$this->iky,'idx');
        $eveid=$this->dec(mysqli_real_escape_string($conn,$eveid),$this->iky);
        $stmt=$conn->prepare("SELECT * FROM  events WHERE club_id=? AND eve_id=?");
        $stmt->bind_param('ss',$cid,$eveid);
        if(!$res=$stmt->execute()){return 0;}
        $result=$stmt->get_result();
        $details=0;
        if ($result->num_rows > 0) {
            $row=$result->fetch_assoc();
            $uid=$_SESSION['ses_id'];
            $uid=$this->dec($uid,$this->iky);
            $stmt=$conn->prepare("SELECT branch,user_name FROM users WHERE uid=?");
            $stmt->bind_param('s',$uid);
            $stmt->execute();
            $result=$stmt->get_result();
            $my_res=$result->fetch_assoc();
            $my_branch=$this->sbldc($my_res['branch'],$this->iky);
            $us_name_mem=$my_res['user_name'];


            $eid=$this->sblen($eveid,$this->iky,'idx');
            $stmt=$conn->prepare("SELECT count(u_id)as u_id FROM participants WHERE eve_id=?");
            $stmt->bind_param('s',$eid);
            $stmt->execute();
            $count=$stmt->get_result()->fetch_assoc();
            $count=$count['u_id'];
            
            $isVoteLive=0;
            $evid=$this->enc($eveid,$this->iky,'idx');
            $stmt=$conn->prepare("SELECT vote_cards_file, vote_count_file, is_live FROM vote_cards WHERE club_id=? AND eve_id=?");
            $stmt->bind_param("ss", $Club_id,$evid);
            $stmt->execute();
            $result_vot = $stmt->get_result();
            if($result_vot->num_rows>0){
                $isVoteLive=1;
            }
            
            $isMemb=0;
            $stmt=$conn->prepare("SELECT count(u_id)as u_id FROM membership WHERE club_id=? AND u_id=?");
            $stmt->bind_param('ss',$Club_id,$us_name_mem);
            $stmt->execute();
            $res_mem=$stmt->get_result();
            if($res_mem->num_rows>0){
                $isMemb=$res_mem->fetch_assoc()['u_id'];
                // $isMemb=1;
            }

            $uid=$this->sblen($uid,$this->iky,'idx');
            $stmt=$conn->prepare("SELECT status FROM participants WHERE eve_id=? AND u_id=?");
            $stmt->bind_param('ss',$eid,$uid);
            $stmt->execute();
            $res=$stmt->get_result();
            $hasAtt="";
            if($res->num_rows>0){
            $stat=$res->fetch_assoc();
            $hasAtt=$stat['status'];
            }
            
            //checking the year
            $year="";
            $utype=$_SESSION['us_tp'];
            $email_ext=$this->sbldc($us_name_mem,$this->iky)."@srit.ac.in";
            $data=$utype==1?$this->extract_Clg_Mail($email_ext):"";
            if($data!=""){
            // $data['year'] = '21'; // Assuming '24' is the year in the data
            // $data['type'] = 'LE'; // Assuming 'Regular' is the type in the data

            // Extract the year from the data
            $joinYear = intval("20" . $data['year']);

            // Calculate the year based on the current year and the type
            $currentYear = date('Y');
            $year = $currentYear - ($data['type'] == "Regular" ? $joinYear : $joinYear - 1);
            }

            $branch=$this->dec($row["branchTo"],$this->iky);
            $isfMe=strpos($branch, $my_branch)!==false?1:0;

            $attCode=$row["attCode"]==""?0:1;
            // $date=$this->dec($row["eve_org_date"],$this->iky);
            $date=$this->dateFormater($row["eve_org_date"],"display");

           $details=["cname"=>$this->dec($row["c_name"],$this->iky),"ename"=>$this->dec($row["eve_name"],$this->iky),"about_eve"=>$this->replacer($this->dec($row["about_eve"],$this->iky)),"img"=>$this->dec($row["featureImg"],$this->iky),"date"=>$date,"eType"=>$this->dec($row["eve_club_typ"],$this->iky),"branches"=>$branch,"years"=>$this->dec($row["yearTo"],$this->iky),"CTAlink"=>$row["CTAlink"]==""?"":$this->dec($row["CTAlink"],$this->iky),"regCount"=>$count,"isfMe"=>$isfMe,"isVoteLive"=>$isVoteLive,"myYear"=>$year,"isMember"=>$isMemb,"hasAttCode"=>$attCode,"isAtt"=>$hasAtt];
        }
        return $details;
    }
    public function getEvents($cid){
        $conn=$this->connect();
        $cid=$this->dec(mysqli_real_escape_string($conn,$cid),$this->iky);
        $cid=$this->sblen($cid,$this->iky,'idx');
        $stmt=$conn->prepare("SELECT eve_id,eve_name,c_name,featureImg FROM  events WHERE club_id=? ORDER BY eve_org_date ASC");
        $stmt->bind_param('s',$cid);
        if(!$res=$stmt->execute()){return 0;}
        $result=$stmt->get_result();
        $events=[];
        if ($result->num_rows > 0) {
            $rows=$result->fetch_all();
            foreach($rows as $row){
                $event=["cname"=>$this->dec($row[2],$this->iky),"ename"=>$this->dec($row[1],$this->iky),"eid"=>$this->enc($row[0],$this->iky,'idx'),"img"=>$this->dec($row[3],$this->iky)];
                array_push($events,$event);
            }
        }
        return json_encode($events);
    }
    public function registerToEve($eid){
        $conn=$this->connect();
        $eid=$this->dec(mysqli_real_escape_string($conn,$eid),$this->iky);
        $eid=$this->sblen($eid,$this->iky,'idx');
        $uid=$_SESSION['ses_id'];
        $uid=$this->dec(mysqli_real_escape_string($conn,$uid),$this->iky);
        $stmt=$conn->prepare("SELECT full_name,user_name FROM users WHERE uid=?");
        $stmt->bind_param('s',$uid);
        $stmt->execute();
        $row=$stmt->get_result()->fetch_assoc();
        $full_name=$this->dec($row['full_name'],$this->iky);
        $full_name=$this->sblen(strtolower($full_name),$this->iky,'idx');
        $user_name=$row['user_name'];
        $uid=$this->sblen($uid,$this->iky,'idx');
        date_default_timezone_set("Asia/Kolkata");
        $status=0;
        $time=time();
        $stmt=$conn->prepare("INSERT INTO participants (eve_id,u_id,usname,u_name,status,timestamp_reg) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param('ssssss',$eid,$uid,$user_name,$full_name,$status,$time);
        $success=$stmt->execute();
        $stmt->close();
        return $success;
    }
    public function unRegister($eid){
        $conn=$this->connect();
        $eid=$this->dec(mysqli_real_escape_string($conn,$eid),$this->iky);
        $eid=$this->sblen($eid,$this->iky,'idx');
        $uid=$_SESSION['ses_id'];
        $uid=$this->dec(mysqli_real_escape_string($conn,$uid),$this->iky);
        $uid=$this->sblen($uid,$this->iky,"idx");
        $stmt=$conn->prepare("DELETE FROM participants WHERE eve_id=? AND u_id=?");
        $stmt->bind_param('ss',$eid,$uid);
        $success=$stmt->execute();
        $stmt->close();
        return $success;
    }
    public function isRegistered($eid){
        $conn=$this->connect();
        $eid=$this->dec(mysqli_real_escape_string($conn,$eid),$this->iky);
        $eid=$this->sblen($eid,$this->iky,'idx');
        $uid=$_SESSION['ses_id'];
        $uid=$this->dec(mysqli_real_escape_string($conn,$uid),$this->iky);
        $uid=$this->sblen($uid,$this->iky,'idx');
        $stmt=$conn->prepare("SELECT u_id FROM participants WHERE eve_id=? AND u_id=?");
        $stmt->bind_param('ss',$eid,$uid);
        $success=$stmt->execute();
        $result=$stmt->get_result();
        $stmt->close();
        if($result->num_rows>0){
            return 1;
        }else{
            return 0;
        }
    }
    public function getRegAttCount($eid){
        $conn=$this->connect();
        $eid=$this->dec(mysqli_real_escape_string($conn,$eid),$this->iky);
        $eid=$this->sblen($eid,$this->iky,'idx');
        $stmt=$conn->prepare("SELECT COUNT(parti_id) FROM participants WHERE eve_id=? AND status=1");
        $stmt->bind_param('s',$eid);
        $stmt->execute();
        $attCount=$stmt->get_result()->fetch_assoc()["COUNT(parti_id)"];
        $stmt->close();
        $stmt=$conn->prepare("SELECT COUNT(parti_id) FROM participants WHERE eve_id=? AND status=0");
        $stmt->bind_param('s',$eid);
        $stmt->execute();
        $regCount=$stmt->get_result()->fetch_assoc()["COUNT(parti_id)"];
        $stmt->close();
        return  json_encode(["att"=>$attCount,"reg"=>$regCount]);
    }
    public function getRegistrations($eid,$srch,$offset){
        $conn=$this->connect();
        $eid=$this->dec(mysqli_real_escape_string($conn,$eid),$this->iky);
        $eid=$this->sblen($eid,$this->iky,'idx');

        $srch=mysqli_real_escape_string($conn,$srch);
        if($srch==""){
            $stmt=$conn->prepare("SELECT pa.usname,pa.u_name,pa.status,us.dp FROM participants pa INNER JOIN users us ON pa.usname=us.user_name WHERE pa.eve_id=? ORDER BY pa.timestamp_reg DESC LIMIT 10 OFFSET ?;");
            $stmt->bind_param('ss',$eid,$offset);
        }elseif($srch=="///dsf_get_attended_for_report_gen_dsf///"){
            $stmt=$conn->prepare("SELECT pa.usname,pa.u_name,pa.status,us.dp FROM participants pa INNER JOIN users us ON pa.usname=us.user_name WHERE pa.eve_id=? AND pa.status = 1 ORDER BY pa.timestamp_reg;");
            $stmt->bind_param('s',$eid);
        }
        else{
            $srch=$this->sblen(strtolower($srch),$this->iky,'idx');
            $stmt = $conn->prepare("SELECT pa.usname, pa.u_name, pa.status, us.dp 
            FROM participants pa 
            INNER JOIN users us ON pa.usname = us.user_name 
            WHERE pa.eve_id = ? 
            AND (pa.usname LIKE CONCAT('%', ?, '%') OR pa.u_name LIKE CONCAT('%', ?, '%')) 
            ORDER BY pa.timestamp_reg DESC LIMIT 10 OFFSET ?;");
            $stmt->bind_param('ssss',$eid,$srch,$srch,$offset);
        }
        $success=$stmt->execute();
        $result=$stmt->get_result();
        $stmt->close();
        if($result->num_rows>0){
            $users=$result->fetch_all();
            $reg_users=[];
            foreach($users as $user){
                $name=$this->sbldc($user[1],$this->iky);
                $u_name=$this->sbldc($user[0],$this->iky);
                $status=$user[2]==1?"Attended":"Registered";
                $dp=$user[3]==""?"":$this->dec($user[3],$this->iky);
                $reg_user=["name"=>ucwords($name),"u_name"=>$u_name,"status"=>$status,"dp"=>$dp];
                array_push($reg_users,$reg_user);
            }
            return json_encode($reg_users);
        }
        return 0;
    }
    public function generateAttendenceCode($cid,$eid){
        $conn=$this->connect();
        $cid_NL=mysqli_real_escape_string($conn,$cid);
         $cid_N=$this->dec($cid_NL,$this->iky);
         $cid=$this->sblen($cid_N,$this->iky,'idx');
         $eid=mysqli_real_escape_string($conn,$eid);
         $eveid=$this->dec($eid,$this->iky);

         $attCode = rand(100000, 999999);
         $encAttCode=$this->enc($attCode,$this->iky,'mtr');
         $stmt = $conn->prepare("UPDATE events SET attCode=? WHERE eve_id=? AND club_id=?");
         $stmt->bind_param("sss", $encAttCode,$eveid,$cid);
         $success = $stmt->execute();
         $stmt->close();
         if($success){
            
            $msg="The event is live now. Enter the attendance code.";
            $link="https://clubsatsrit.in/event.php?cid=$cid_NL&evid=$eid";
            $this->notify($cid_N,"",$msg,$link,"followers");

            return $attCode;
         }else{
            return 0;
         }
    }
    public function fetchAttendenceCode($cid,$eid,$Acode="", $type=''){
        $conn=$this->connect();
        $cid_NL=mysqli_real_escape_string($conn,$cid);
         $cid_N=$this->dec($cid_NL,$this->iky);
         $cid=$this->sblen($cid_N,$this->iky,'idx');
         $eveid=$this->dec(mysqli_real_escape_string($conn,$eid),$this->iky);

         $stmt = $conn->prepare("SELECT attCode FROM events WHERE eve_id=? AND club_id=?");
         $stmt->bind_param("ss", $eveid,$cid);
         $stmt->execute();
         $result=$stmt->get_result();
         if($result->num_rows>0){
            $row=$result->fetch_assoc();
            $code=$this->dec($row['attCode'],$this->iky);
                if($type=="attend"){
                    if($code==$Acode){
                        $uid=$_SESSION['ses_id'];
                        $uid=$this->dec($uid,$this->iky);
                        $uid=$this->sblen($uid,$this->iky,'idx');
                        $eid=$this->sblen($eveid,$this->iky,'idx');
                        $stmt=$conn->prepare("UPDATE participants SET status=1 WHERE eve_id=? AND u_id=?");
                        $stmt->bind_param('ss',$eid,$uid);
                        if($stmt->execute()){
                            return "done";
                        }else{
                            return 0;
                        }
                    }else{
                        return "missMatch";
                    }
                }else{
                    return $code;
                }
         }else{
            return 0;
         }
    }
}


$eve = new eveDetails();

if(isset($_POST['eveDetails'])&&$_POST['eveDetails']=="true"){
    if(isset($_POST['cid'],$_POST['evid'])){
            $array= $eve->getEveDetails($_POST['cid'],$_POST['evid']);
            $isReg =["reg"=>$eve->isRegistered($_POST['evid'])];
            $merged=array_merge($array,$isReg);
            echo json_encode($merged);
    }
}

if(isset($_POST['getEvents'])&&$_POST['getEvents']=="true"){
    if(isset($_POST['cid'])){
            echo $eve->getEvents($_POST['cid']);
    }
}
if(isset($_SESSION['ses_id'])){

if(isset($_POST['actn'],$_POST['code'],$_POST['cid'],$_POST['eid'])&&$_POST['actn']=="checkAttendCode"){
    echo $eve->fetchAttendenceCode($_POST['cid'],$_POST['eid'],$_POST['code'],"attend");
}
if(isset($_POST['actn'],$_POST['cid'],$_POST['eid'])&&$_POST['actn']=="fetchAttendCode"){
    echo $eve->fetchAttendenceCode($_POST['cid'],$_POST['eid'],"","");
}
if(isset($_POST['Code_actn'],$_POST['cid'],$_POST['eid'])&&$_POST['Code_actn']=="genAttendCode"){
    echo $eve->generateAttendenceCode($_POST['cid'],$_POST['eid'],"","");
}

if(isset($_POST['getRegAtCount'],$_POST['evid'])&&$_POST['getRegAtCount']=="true"){
    echo $eve->getRegAttCount($_POST['evid']);
}
if(isset($_POST['getRegistered'],$_POST['evid'],$_POST['srch'],$_POST['offset'])&&$_POST['getRegistered']=="true"){
    echo $eve->getRegistrations($_POST['evid'],$_POST['srch'],$_POST['offset']);
}
if(isset($_POST['registerUser'],$_POST['evid'])&&$_POST['registerUser']=="true"){
echo $eve->registerToEve($_POST['evid']);
}

if(isset($_POST['unRegisterUser'],$_POST['evid'])&&$_POST['unRegisterUser']=="true"){
    echo $eve->unRegister($_POST['evid']);
}
if(isset($_POST['eventAction'])&&$_POST['eventAction']=="create"){
    if(isset($_POST['cid'],$_POST['cname'],$_POST['ename'],$_POST['about_e'],$_POST['imge'],$_POST['date'],$_POST['clubType'],$_POST['branch'],$_POST['year'],
        $_POST['linkCTA'])){
            echo $eve->createEve($_POST['cid'],$_POST['cname'],$_POST['ename'],$_POST['about_e'],$_POST['imge'],$_POST['date'],$_POST['clubType'],$_POST['branch'],$_POST['year'],$_POST['linkCTA']);
    }
}
if(isset($_POST['eventAction'])&&$_POST['eventAction']=="update"){
    if(isset($_POST['cid'],$_POST['eid'],$_POST['ename'],$_POST['about_e'],$_POST['imge'],$_POST['date'],$_POST['clubType'],$_POST['branch'],$_POST['year'],
        $_POST['linkCTA'])){
            echo $eve->updateEve($_POST['cid'],$_POST['eid'],$_POST['ename'],$_POST['about_e'],$_POST['imge'],$_POST['date'],$_POST['clubType'],$_POST['branch'],$_POST['year'],$_POST['linkCTA']);
    }
}
}