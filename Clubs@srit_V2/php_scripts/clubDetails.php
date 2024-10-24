<?php
session_start();
include 'db_conn.php';
class club extends dbconnect{
    public function getStudent($rollno){
        $conn=$this->connect();
        $uid=mysqli_real_escape_string($conn,$rollno);
        $rollno=$this->sblen(mysqli_real_escape_string($conn,$rollno),$this->iky,'idx');
        $stmt = $conn->prepare("SELECT full_name,dp,uid,user_name FROM users WHERE user_name = ? OR uid=?");
        $stmt->bind_param("ss", $rollno,$uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return json_encode(["name"=>$this->dec($row["full_name"],$this->iky),"uid"=>$this->enc($row['uid'],$this->iky,'mtr'),"dp"=>$this->dec($row["dp"],$this->iky),"usname"=>$this->sbldc($row['user_name'],$this->iky)]);
        }else{
            return 0;
        }
    }
    public function createClub($name,$about,$img,$teamRoles,$stu_admin,$ms,$branch){
        $conn=$this->connect();
        $fid=$this->dec($_SESSION['ses_id'],$this->iky);
        $fac_id=$this->sblen($fid,$this->iky,'idx');
        $name=$this->sblen(mysqli_real_escape_string($conn,$name),$this->iky,'idx');
        $about=$this->enc(htmlspecialchars(mysqli_real_escape_string($conn,$about)),$this->iky,'mtr');
        $img=$this->enc(mysqli_real_escape_string($conn,$img),$this->iky,'mtr');
        $teamRoles=$this->enc(mysqli_real_escape_string($conn,$teamRoles),$this->iky,'mtr');
        $branch=$this->enc(mysqli_real_escape_string($conn,$branch),$this->iky,'idx');
        $stu_admin=$this->sblen(mysqli_real_escape_string($conn,$stu_admin),$this->iky,'idx');
        $ms=mysqli_real_escape_string($conn,$ms);
        $stmt = $conn->prepare("INSERT INTO clubs (fac_id,cname, about_c, cpic,team_roles ,stud_admn,c_type, branch) VALUES (?, ?, ?, ?,?,?, ?, ?)");
        $stmt->bind_param("ssssssss", $fac_id,$name,$about,$img,$teamRoles,$stu_admin,$ms,$branch);
        $success = $stmt->execute();
        if ($success) {
            // Get the id of the newly inserted row
            $cid=$conn->insert_id;
            $success = $this->enc($cid,$this->iky,'strix');
            $cid=$this->sblen($cid,$this->iky,'idx');
            $fac=$this->enc("fac",$this->iky,'idx');
            $sa=$this->enc("SA",$this->iky,'idx');
            $stmt=$conn->prepare("SELECT uid from users WHERE user_name=?");
            $stmt->bind_param("s",$stu_admin);
            $stmt->execute();
            $result=$stmt->get_result();
            $row=$result->fetch_assoc();
            $stu_admin=$this->sblen($row['uid'],$this->iky,'idx');
            $stmt = $conn->prepare("INSERT INTO club_exe_team (club_id,uid, member_type) VALUES (?,?,?)");
            $stmt->bind_param("sss", $cid,$fac_id,$fac);
            $stmt->execute();
            $stmt->bind_param("sss", $cid,$stu_admin,$sa);
            $stmt->execute();
        } else {
            // Handle the error
            $success="error";
        }
        $stmt->close();
        return $success;
    }
    public function updateClub($cid,$name,$about,$img,$teamRoles,$stu_admin,$ms,$branch){
        $conn=$this->connect();
        $cid=$this->dec($cid,$this->iky);
        $name=$this->sblen(mysqli_real_escape_string($conn,$name),$this->iky,'idx');
        $about=$this->enc(htmlspecialchars(mysqli_real_escape_string($conn,$about)),$this->iky,'mtr');
        $img=$this->enc(mysqli_real_escape_string($conn,$img),$this->iky,'mtr');
        $teamRoles=$this->enc(mysqli_real_escape_string($conn,$teamRoles),$this->iky,'mtr');
        $branch=$this->enc(mysqli_real_escape_string($conn,$branch),$this->iky,'idx');
        $stu_admin=$this->sblen(mysqli_real_escape_string($conn,$stu_admin),$this->iky,'idx');
        $ms=mysqli_real_escape_string($conn,$ms);
        $stmt = $conn->prepare("UPDATE clubs SET cname=?, about_c=?, cpic=?,team_roles=? ,stud_admn=?,c_type=?, branch=? WHERE cid=?");
        $stmt->bind_param("ssssssss", $name,$about,$img,$teamRoles,$stu_admin,$ms,$branch,$cid);
        $success = $stmt->execute();
        if ($success) {
            // Get the id of the newly inserted row
            $success = $this->enc($cid,$this->iky,'strix');
            $cid=$this->sblen($cid,$this->iky,'idx');
            $fac=$this->enc("fac",$this->iky,'idx');
            $sa=$this->enc("SA",$this->iky,'idx');
            $stmt=$conn->prepare("SELECT uid from users WHERE user_name=?");
            $stmt->bind_param("s",$stu_admin);
            $stmt->execute();
            $result=$stmt->get_result();
            $row=$result->fetch_assoc();
            $stu_admin=$this->sblen($row['uid'],$this->iky,'idx');
            // $stmt = $conn->prepare("INSERT INTO club_exe_team (club_id,uid, member_type) VALUES (?,?, ?)");
            $stmt = $conn->prepare("INSERT INTO club_exe_team (club_id, uid, member_type)
                SELECT ?, ?, ?
                FROM dual
                WHERE NOT EXISTS (
                    SELECT 1 
                    FROM club_exe_team 
                    WHERE club_id = ? AND uid = ?
            );");
            $stmt->bind_param("sssss", $cid, $stu_admin, $sa, $cid, $stu_admin);
            $stmt->execute();
            if($conn->insert_id!=0){
                $mem_typ=$this->enc("member",$this->iky,'idx');
                $stmt = $conn->prepare("UPDATE club_exe_team SET member_type=? WHERE club_id=? AND member_type=?");
                $stmt->bind_param("sss", $mem_typ, $cid, $sa);
                $stmt->execute();
            }elseif($conn->insert_id==0){
                $mem_typ=$this->enc("member",$this->iky,'idx');
                $stmt = $conn->prepare("UPDATE club_exe_team SET member_type=? WHERE club_id=? AND member_type=?");
                $stmt->bind_param("sss", $mem_typ, $cid, $sa);
                $stmt->execute();
                $stmt = $conn->prepare("UPDATE club_exe_team SET member_type=? WHERE club_id=? AND uid=? ");
                $stmt->bind_param("sss", $sa, $cid,$stu_admin);
                $stmt->execute();
            }
        } else {
            // Handle the error
            $success="error";
        }
        $stmt->close();
        return $success;
    }
    public function clubDetails($clubId){
        $conn=$this->connect();
        $clubId=$this->dec(mysqli_real_escape_string($conn,$clubId),$this->iky);
        $stmt = $conn->prepare("SELECT cname,cpic,about_c,branch,c_type,stud_admn,team_roles FROM clubs WHERE cid = ?");
        $stmt->bind_param("s", $clubId);
        $stmt->execute();
        $result = $stmt->get_result();
        $array;
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $clubId=$this->enc(mysqli_real_escape_string($conn,$clubId),$this->iky,'idx');
            $stmt = $conn->prepare("SELECT COUNT(folo_id) as folow_count FROM club_follow WHERE clb_id = ?");
            $stmt->bind_param("s", $clubId);
            $stmt->execute();
            $result = $stmt->get_result();

            $memb_count=0;
            if($row["c_type"]==1){
                $stmt = $conn->prepare("SELECT COUNT(m_id) as m_id FROM membership WHERE club_id = ?");
                $stmt->bind_param("s", $clubId);
                $stmt->execute();
                $res= $stmt->get_result();
                $memb_count=$res->num_rows>0?$res->fetch_assoc()['m_id']:0;
            }

            $about=$this->dec($row["about_c"],$this->iky);
            $about=$this->replacer($about);
            $array=["followers"=>$result->num_rows>0?$result->fetch_assoc()['folow_count']:0,"name"=>$this->sbldc($row["cname"],$this->iky),"about_c"=>$about,"team_roles"=>$this->dec($row['team_roles'],$this->iky),"std_admin"=>$this->sbldc($row['stud_admn'],$this->iky),"isMs"=>$row["c_type"],"branch"=>$this->dec($row["branch"],$this->iky),"img"=>$this->dec($row["cpic"],$this->iky),"memCount"=>$memb_count];
            return ($array);
        }else{
            return 0;
        }
    }
    public function updateAbout($clubId,$text){
        $conn=$this->connect();
        $clubId=$this->dec(mysqli_real_escape_string($conn,$clubId),$this->iky);
        $text=$this->enc(htmlspecialchars(mysqli_real_escape_string($conn,$text)),$this->iky,'mtr');
        $stmt = $conn->prepare("UPDATE clubs SET about_c=? WHERE cid = ?");
        $stmt->bind_param("ss", $text,$clubId);
        $success=$stmt->execute();
        $stmt->close();
        return $success;
    }
    public function getRoles($clubId){
        $conn=$this->connect();
        $clubId=$this->dec(mysqli_real_escape_string($conn,$clubId),$this->iky);
        $stmt = $conn->prepare("SELECT team_roles FROM clubs WHERE cid = ?");
        $stmt->bind_param("s", $clubId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $this->dec($row['team_roles'],$this->iky);
        }else{
            return 0;
        }
    }
    public function follow($clubId){
        $conn=$this->connect();
        $clubId=$this->dec(mysqli_real_escape_string($conn,$clubId),$this->iky);
        $clubId=$this->enc(mysqli_real_escape_string($conn,$clubId),$this->iky,'idx');
        $sid=$_SESSION['ses_id'];
        $sid=$this->dec(mysqli_real_escape_string($conn,$sid),$this->iky);
        $sid=$this->enc(mysqli_real_escape_string($conn,$sid),$this->iky,'idx');
        $stmt = $conn->prepare("INSERT INTO club_follow (clb_id,us_id) VALUES (?,?)");
        $stmt->bind_param("ss", $clubId,$sid);
        $success=$stmt->execute();
        $stmt->close();
        return $success;
    }
    public function unfollow($clubId){
        $conn=$this->connect();
        $clubId=$this->dec(mysqli_real_escape_string($conn,$clubId),$this->iky);
        $clubId=$this->enc(mysqli_real_escape_string($conn,$clubId),$this->iky,'idx');
        $sid=$_SESSION['ses_id'];
        $sid=$this->dec(mysqli_real_escape_string($conn,$sid),$this->iky);
        $sid=$this->enc(mysqli_real_escape_string($conn,$sid),$this->iky,'idx');
        $stmt = $conn->prepare("DELETE FROM club_follow WHERE clb_id=? AND us_id=?");
        $stmt->bind_param("ss", $clubId,$sid);
        $success=$stmt->execute();
        $stmt->close();
        return $success;
    }
    public function isFollowing($clubId){
        $conn=$this->connect();
        $clubId=$this->dec(mysqli_real_escape_string($conn,$clubId),$this->iky);
        $clubId=$this->enc(mysqli_real_escape_string($conn,$clubId),$this->iky,'idx');
        $sid=$_SESSION['ses_id'];
        $sid=$this->dec(mysqli_real_escape_string($conn,$sid),$this->iky);
        $sid=$this->enc(mysqli_real_escape_string($conn,$sid),$this->iky,'idx');
        $stmt = $conn->prepare("SELECT folo_id FROM club_follow WHERE clb_id = ? AND us_id=?");
        $stmt->bind_param("ss", $clubId,$sid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return 1;
        }else{
            return 0;
        }
    }

    public function addTeamMember($mid,$role,$cid){
        $conn=$this->connect();
        $mem_typ=$this->enc("member",$this->iky,'idx');
        
        $mid_N=$this->dec(mysqli_real_escape_string($conn,$mid),$this->iky);
        $mid=$this->sblen($mid_N,$this->iky,'idx');
        $rolle=mysqli_real_escape_string($conn,$role);
        $role=$this->enc($rolle,$this->iky,'idx');
        $cid_NL=mysqli_real_escape_string($conn,$cid);
        $cid_N=$this->dec($cid_NL,$this->iky);
        $cid=$this->sblen($cid_N,$this->iky,'idx');
        
        $stmt=$conn->prepare("INSERT INTO club_exe_team (club_id, uid, member_type,t_role)
        SELECT ?, ?, ?,?
        FROM dual
        WHERE NOT EXISTS (
            SELECT 1 
            FROM club_exe_team 
            WHERE club_id = ? AND uid = ?
    );");
        $stmt->bind_param("ssssss", $cid,$mid,$mem_typ,$role,$cid,$mid);
        $success=$stmt->execute();
        $stmt->close();
        $success=$conn->insert_id==0?"0":$success;
        
        $msg="You are now added to our team. Your role is $rolle.";
        $link="https://clubsatsrit.in/team.php?cid=$cid_NL";
        $this->notify($cid_N,$mid_N,$msg,$link,"person");


        
        $cname_N = $this->getClubName($cid,"");
        $cname_N = ucwords($cname_N);
        $cname_N = str_replace("_"," ",$cname_N);
        $text="<center><img class='logo' src='https://img.freepik.com/free-vector/onboarding-concept-illustration_114360-4120.jpg' alt='Clubs@SRIT Logo'><p>You are now added as a team member of the club.<br>Welcome to $cname_N</p></center>";
        $subj = "Added You To The Team By $cname_N";
        $msg = $text."<a href='$link' class='action-btn'>Go to Club</a>";
        $this->mail_notify($cid_N,$mid_N,$subj,$msg,"person");

        return $success;
    }
    public function updateTeamMember($mid,$role,$cid){
        $conn=$this->connect();
        $mid_N=$this->dec(mysqli_real_escape_string($conn,$mid),$this->iky);
        $mid=$this->sblen($mid_N,$this->iky,'idx');
        $rolle=mysqli_real_escape_string($conn,$role);
        $role=$this->enc($rolle,$this->iky,'idx');
        $cid_NL=mysqli_real_escape_string($conn,$cid);
        $cid_N=$this->dec($cid_NL,$this->iky);
        $cid=$this->sblen($cid_N,$this->iky,'idx');
        
        $stmt=$conn->prepare("UPDATE club_exe_team SET t_role=? WHERE club_id = ? AND uid = ?");
        $stmt->bind_param("sss", $role,$cid,$mid);
        $success=$stmt->execute();
        $stmt->close();

        
        $msg="Your role is now updated to $rolle.";
        $link="https://clubsatsrit.in/team.php?cid=$cid_NL";
        $this->notify($cid_N,$mid_N,$msg,$link,"person");


        
        $cname_N = $this->getClubName($cid,"");
        $cname_N = ucwords($cname_N);
        $cname_N = str_replace("_"," ",$cname_N);
        $text="<center><img class='logo' src='https://img.freepik.com/premium-vector/brainstorming-with-creative-team-isolated-cartoon-vector-illustrations_107173-22684.jpg' alt='Clubs@SRIT Logo'><p>Your roll as a team member of the club is now updated to $rolle.</center>";
        $subj = "Added You To The Team By $cname_N";
        $msg = $text."<a href='$link' class='action-btn'>Go to Club</a>";
        $this->mail_notify($cid_N,$mid_N,$subj,$msg,"person");

        return $success;
    }
    public function deleteTM($mid,$cid,$exid){
        $conn=$this->connect();
        $exid=$this->dec(mysqli_real_escape_string($conn,$exid),$this->iky);
        $mid_N=$this->dec(mysqli_real_escape_string($conn,$mid),$this->iky);
        $mid=$this->sblen($mid_N,$this->iky,'idx');
        $cid_NL=mysqli_real_escape_string($conn,$cid);
        $cid_N=$this->dec($cid_NL,$this->iky);
        $cid=$this->sblen($cid_N,$this->iky,'idx');
        
        $stmt=$conn->prepare("DELETE FROM club_exe_team WHERE cet_id=? AND club_id = ? AND uid = ?");
        $stmt->bind_param("sss", $exid,$cid,$mid);
        $success=$stmt->execute();
        $stmt->close();
        $msg="You are now removed as team member.";
        $link="";
        $this->notify($cid_N,$mid_N,$msg,$link,"person");

        
        $cname_N = $this->getClubName($cid,"");
        $cname_N = ucwords($cname_N);
        $cname_N = str_replace("_"," ",$cname_N);
        $text="<center><img class='logo' src='https://img.freepik.com/free-vector/standup-meeting-concept-illustration_114360-7056.jpg' alt='Clubs@SRIT Logo'><p>You are now removed as a team member of the club.</p></center>";
        $subj = "Removed You From Team By $cname_N";
        $msg = $text."<a href='$link' class='action-btn'>Go to Club</a>";
        $this->mail_notify($cid_N,$mid_N,$subj,$msg,"person");

        return $success;
    }
    public function getTeam($cid){
        $conn=$this->connect();
        $cid=$this->dec(mysqli_real_escape_string($conn,$cid),$this->iky);
        $cid=$this->sblen($cid,$this->iky,'idx');
        $stmt = $conn->prepare("SELECT cet_id,uid, member_type,t_role FROM club_exe_team WHERE club_id = ?");
        $stmt->bind_param("s", $cid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $rows = $result->fetch_all();
            $bigArray=[];
            foreach($rows as $row){
                $id=$this->sbldc($row['1'],$this->iky);
                $stud=$this->getStudent($id);
                $stud=json_decode($stud,true);
                $array=["name"=>$stud['name'],"usname"=>$stud['usname'],"dp"=>$stud['dp'],"exe_id"=>$this->enc($row['0'],$this->iky,'mtr'),"uid"=>$stud['uid'],"t_role"=>$row['3']==""?"":$this->dec($row['3'],$this->iky),"member_type"=>$this->dec($row['2'],$this->iky)];
                array_push($bigArray,$array);
            }
            return json_encode($bigArray);
        }else{
            return 0;
        }
    }
    private function getClubName($cid,$enc_type){
        // don't add this function in class diagram.
        $conn=$this->connect();
        $clubId=$this->sbldc(mysqli_real_escape_string($conn,$cid),$this->iky);
        if($enc_type=="enc"){
        $clubId=$this->dec(mysqli_real_escape_string($conn,$cid),$this->iky);
        }
        $stmt = $conn->prepare("SELECT cname FROM clubs WHERE cid = ?");
        $stmt->bind_param("s", $clubId);
        $stmt->execute();
        $result = $stmt->get_result();
        $array;
        if ($result->num_rows > 0) {
            return $this->sbldc($result->fetch_assoc()['cname'],$this->iky);
        }
    }
    public function addMember_mship($cid_NL,$user){
        $conn=$this->connect();
        $cid_NL=mysqli_real_escape_string($conn,$cid_NL);
        $cid_n=$this->dec($cid_NL,$this->iky);
        $cid=$this->enc($cid_n,$this->iky,"idx");
        $rollno=mysqli_real_escape_string($conn,$user);
        if($this->getStudent($rollno)!=0){
        $user=$this->sblen($rollno,$this->iky,'idx');
        $stmt=$conn->prepare("INSERT INTO membership(club_id,u_id) VALUES (?,?)");
        $stmt->bind_param("ss",$cid,$user);
        $success = $stmt->execute();
        $stmt->close();
        $stmt=$conn->prepare("SELECT uid FROM users WHERE user_name=?");
        $stmt->bind_param("s",$user);
        $stmt->execute();
        $res=$stmt->get_result()->fetch_assoc();
        $uid=$res['uid'];
        $msg="You are added as a membership member of the club.";
            $link="https://clubsatsrit.in/membership.php?cid=$cid";
            $this->notify($cid_n,$uid,$msg,$link,"person");


        
        $cname_N = $this->getClubName($cid,"enc");
        $cname_N = ucwords($cname_N);
        $cname_N = str_replace("_"," ",$cname_N);
        $text="<center><img class='logo' src='https://thumbs.dreamstime.com/b/illustration-female-hand-holding-membership-card-written-japanese-stylized-held-katakana-114459815.jpg' alt='Clubs@SRIT Logo'><p>You are now added as a membership member of the club.</p></center>";
        $subj = "Your Membership Accepted By $cname_N";
        
        $msg = $text."<a href='$link' class='action-btn'>Go to Club</a>";
        $this->mail_notify($cid_n,$uid,$subj,$msg,"person");

        return $success;
        }else{
            return "noUser";
        }
    }
    public function removeMember_mship($cid_NL,$user){
        $conn=$this->connect();
        $cid_NL=mysqli_real_escape_string($conn,$cid_NL);
        $cid_n=$this->dec($cid_NL,$this->iky);
        $cid=$this->enc($cid_n,$this->iky,"idx");
        $user=$this->sblen(mysqli_real_escape_string($conn,$user),$this->iky,'idx');
        $stmt=$conn->prepare("DELETE FROM membership WHERE club_id=? AND u_id=?");
        $stmt->bind_param("ss",$cid,$user);
        $success = $stmt->execute();
        $stmt->close();
        $stmt=$conn->prepare("SELECT uid FROM users WHERE user_name=?");
        $stmt->bind_param("s",$user);
        $stmt->execute();
        $res=$stmt->get_result()->fetch_assoc();
        $uid=$res['uid'];
        $msg="You are removed as a membership member of the club.";
            $link="https://clubsatsrit.in/membership.php?cid=$cid";
            $this->notify($cid_n,$uid,$msg,$link,"person");


            
            $cname_N = $this->getClubName($cid,"enc");
            $cname_N = ucwords($cname_N);
            $cname_N = str_replace("_"," ",$cname_N);
            $text="<center><img class='logo' src='https://thumbs.dreamstime.com/b/illustration-female-hand-holding-membership-card-written-japanese-stylized-held-katakana-114459815.jpg' alt='Clubs@SRIT Logo'><p>You are now removed as a membership member of the club.</p></center>";
            $subj = "Your Membership Removed By $cname_N";
            
            $msg = $text."<a href='$link' class='action-btn'>Go to Club</a>";
            $this->mail_notify($cid_n,$uid,$subj,$msg,"person");
        
        return $success;
    }
    public function getMshipMembers($cid_NL){
        $conn=$this->connect();
        $cid_NL=mysqli_real_escape_string($conn,$cid_NL);
        $cid_n=$this->dec($cid_NL,$this->iky);
        $cid=$this->enc($cid_n,$this->iky,"idx");
        $stmt=$conn->prepare("SELECT us.uid,us.full_name,us.user_name,us.dp FROM users us
        INNER JOIN membership ms ON us.user_name=ms.u_id WHERE ms.club_id=?");
        $stmt->bind_param("s",$cid);
        $stmt->execute();
        $result=$stmt->get_result();
        if($result->num_rows>0){
            $rows=$result->fetch_all();
            $members="";
            foreach($rows as $row){
                $uid=$this->enc($row[0],$this->iky,'idx');
                $name=$this->dec($row[1],$this->iky);
                $uname=$this->sbldc($row[2],$this->iky);
                $dp=$this->dec($row[3],$this->iky);
                $members.="
                <div class='team-member reg-user'>
                <div class='mem-details'>
                  <img
                    src='$dp'
                    alt=''
                    class='img-sq userImage'
                  />
                  <div class='mem-txt-det'>
                    <p class='mem-name'>$name</p>
                    <p class='mem-role'>$uname</p>
                  </div>
                </div>
                <div
                  class='remove-member-btn text-btn-remove'
                  data-uid='$uid' data-uname='$uname'
                >
                  Remove
                </div>
              </div>
                ";

            }
            return json_encode(["mcount"=>$result->num_rows,"membs"=>$members]);
        }else{
            return json_encode(["mcount"=>0,"membs"=>""]);
        }
    }
    public function getFollowers($cid_NL){
        $conn=$this->connect();
        $cid_NL=mysqli_real_escape_string($conn,$cid_NL);
        $cid_n=$this->dec($cid_NL,$this->iky);
        $cid=$this->enc($cid_n,$this->iky,"idx");
        $stmt=$conn->prepare("SELECT us_id FROM club_follow WHERE clb_id=?");
        $stmt->bind_param("s",$cid);
        $stmt->execute();
        $result=$stmt->get_result();
        if($result->num_rows>0){
            $rows=$result->fetch_all();
            $members="";
            foreach($rows as $row){
                $usid=$this->dec($row[0],$this->iky);
                
                $stmt=$conn->prepare("SELECT uid,full_name,user_name,dp FROM users WHERE uid=?");
                $stmt->bind_param("s",$usid);
                $stmt->execute();
                $resultt=$stmt->get_result();
                if($resultt->num_rows>0){
                    $row=$resultt->fetch_assoc();
                $uid=$this->enc($row["uid"],$this->iky,'idx');
                $name=$this->dec($row["full_name"],$this->iky);
                $uname=$this->sbldc($row["user_name"],$this->iky);
                $dp=$this->dec($row["dp"],$this->iky);
                $members.="
                <div class='team-member reg-user'>
                <div class='mem-details'>
                  <img
                    src='$dp'
                    alt=''
                    class='img-sq userImage'
                  />
                  <div class='mem-txt-det'>
                    <p class='mem-name'>$name</p>
                    <p class='mem-role'>$uname</p>
                  </div>
                </div>
                <div
                  class='remove-member-btn text-btn-remove'
                  data-uid='$uid' data-uname='$uname'
                >
                  Remove
                </div>
              </div>
                ";
                }
            }
            return json_encode(["mcount"=>$result->num_rows,"membs"=>$members]);
        }else{
            return json_encode(["mcount"=>0,"membs"=>""]);
        }
    }
}

$club= new club();

if(isset($_POST['action'])&&$_POST['action']=="setStudentAdminRole"&&isset($_POST['rollno'])){
    echo $club->getStudent($_POST['rollno']);
}

if(isset($_POST['cd'])&&$_POST['cd']=="clubDetails"&&isset($_POST['cid'])){
    $array= $club->clubDetails($_POST['cid']);
    $isFollowing =["following"=>$club->isFollowing($_POST['cid'])];
    $merged=array_merge($array,$isFollowing);
    echo json_encode($merged);
}

if(isset($_SESSION['ses_id'])){
if(isset($_POST['memAct'],$_POST['cid'],$_POST['uname'])&&$_POST['memAct']=="addMember"){
    echo $club->addMember_mship($_POST['cid'],$_POST['uname']);
}
if(isset($_POST['memAct'],$_POST['cid'],$_POST['uname'])&&$_POST['memAct']=="removeMember"){
    echo $club->removeMember_mship($_POST['cid'],$_POST['uname']);
}
if(isset($_POST['memAct'],$_POST['cid'],$_POST['uname'])&&$_POST['memAct']=="unFollow"){
    echo $club->unfollow($_POST['cid']);
}
if(isset($_POST['memAct'],$_POST['cid'])&&$_POST['memAct']=="getMsMembers"){
    echo $club->getMshipMembers($_POST['cid']);
}

if(isset($_POST['memAct'],$_POST['cid'])&&$_POST['memAct']=="getMsFollowers"){
    echo $club->getFollowers($_POST['cid']);
}
if(isset($_POST['action'])&&$_POST['action']=="createClub"){
    if(isset($_POST['name'])&&isset($_POST['about'])&&isset($_POST['img'])&&isset($_POST['teamRoles'])&&isset($_POST['stu_admin'])&&isset($_POST['ms'])&&isset($_POST['branch'])){
        echo $club->createClub($_POST['name'],$_POST['about'],$_POST['img'],$_POST['teamRoles'],$_POST['stu_admin'],$_POST['ms'],$_POST['branch']);
    }
}

if(isset($_POST['action'])&&$_POST['action']=="updateClub"){
    if(isset($_POST['cid'])&&isset($_POST['name'])&&isset($_POST['about'])&&isset($_POST['img'])&&isset($_POST['teamRoles'])&&isset($_POST['stu_admin'])&&isset($_POST['ms'])&&isset($_POST['branch'])){
        echo $club->updateClub($_POST['cid'],$_POST['name'],$_POST['about'],$_POST['img'],$_POST['teamRoles'],$_POST['stu_admin'],$_POST['ms'],$_POST['branch']);
    }
}
if(isset($_POST['remTM'])&&$_POST['remTM']=="true"&&isset($_POST['mid'],$_POST['exId'],$_POST['cid'])){
    echo $club->deleteTM($_POST['mid'],$_POST['cid'],$_POST['exId']);
}

if(isset($_POST['action'])&&$_POST['action']=="update-about"&&isset($_POST['cid'])&&isset($_POST['text'])){
    echo $club->updateAbout($_POST['cid'],$_POST['text']);
}
if(isset($_POST['roles'])&&$_POST['roles']=="true"&&isset($_POST['cid'])){
    echo $club->getRoles($_POST['cid']);
}
if(isset($_POST['follow'])&&$_POST['follow']=="true"&&isset($_POST['cid'])){
    echo $club->follow($_POST['cid']);
}
if(isset($_POST['unfollow'])&&$_POST['unfollow']=="true"&&isset($_POST['cid'])){
    echo $club->unfollow($_POST['cid']);
}
if(isset($_POST['post'])&&$_POST['post']=="true"&&isset($_POST['cid'])&&isset($_POST['txt'])&&isset($_POST['img'])){
    echo $club->postUpdate($_POST['cid'],$_POST['txt'],$_POST['img']);
}
if(isset($_POST['addTM'])&&$_POST['addTM']=="true"){
    if(isset($_POST['mid'])&&isset($_POST['rolle'])&&isset($_POST['cid'])){
        echo $club->addTeamMember($_POST['mid'],$_POST['rolle'],$_POST['cid']);
    }
}

if(isset($_POST['updateTM'])&&$_POST['updateTM']=="true"){
    if(isset($_POST['mid'])&&isset($_POST['rolle'])&&isset($_POST['cid'])){

        echo $club->updateTeamMember($_POST['mid'],$_POST['rolle'],$_POST['cid']);
    }
}

if(isset($_POST['getTeam'])&&$_POST['getTeam']=="true"){
    if(isset($_POST['cid'])){
        echo $club->getTeam($_POST['cid']);
    }
}
}