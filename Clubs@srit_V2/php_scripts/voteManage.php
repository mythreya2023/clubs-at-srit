<?php
include 'db_conn.php';
class Vote extends dbconnect{
    public function makeVotingLive($cid,$eid,$file_name){
        $conn=$this->connect();
        $ev_id=$this->dec(mysqli_real_escape_string($conn,$eid),$this->iky);
        $cid_N=$this->dec(mysqli_real_escape_string($conn,$cid),$this->iky);
        $eid=$this->enc($ev_id,$this->iky,'idx');
        $cid=$this->enc($cid_N,$this->iky,'idx');
        $file_name=$this->enc(mysqli_real_escape_string($conn,$file_name),$this->iky,'mtr');
        $stmt=$conn->prepare("UPDATE vote_cards SET vote_count_file=? , is_live='1' WHERE club_id=? AND eve_id=?");
        $stmt->bind_param("sss", $file_name,$cid,$eid);
        $success=$stmt->execute();
        $stmt->close();

        
        $msg="Vote for this event. Click to vote now!";
        $link="https://clubsatsrit.in/voting.php?cid=$cid&evid=$eid";

        $this->notify($cid_N,"",$msg,$link,"attended");

        $stmt=$conn->prepare("SELECT c_name,eve_name FROM events where eve_id = ?;");
        $stmt->bind_param("s",$ev_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row=$result->fetch_assoc();
            $CName = $this->dec($row['c_name'],$this->iky);
            $event_name = $this->dec($row['eve_name'],$this->iky);
            $event_name = ucwords($event_name);
            $CName = ucwords($CName);
            $CName = str_replace("_"," ",$CName);
            $text="<center><h3>Vote Now!</h3><img class='logo' src='https://www.shutterstock.com/image-vector/hand-drawn-illustration-hands-holding-600nw-2077629565.jpg' alt='Clubs@SRIT Logo'><p>Click the button below.</p></center>";
            $subj = "$CName: Allowed you to vote now!";
            
            $msg = $text."<a href='$link' class='action-btn'>Vote Now</a>";
            $this->mail_notify($cid_N,"",$subj,$msg,"attended");
        }

        return $success;
    }
    public function DeleteVoting($cid,$eid){
        $conn=$this->connect();
        $eid=$this->dec(mysqli_real_escape_string($conn,$eid),$this->iky);
        $cid=$this->dec(mysqli_real_escape_string($conn,$cid),$this->iky);
        $eid=$this->enc($eid,$this->iky,'idx');
        $cid=$this->enc($cid,$this->iky,'idx');
        $stmt=$conn->prepare("SELECT vote_cards_file, vote_count_file FROM vote_cards WHERE club_id=? AND eve_id=?");
        $stmt->bind_param("ss", $cid,$eid);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows>0){
            $row=$result->fetch_assoc();
            $cards_file=$this->dec($row['vote_cards_file'],$this->iky);
            $count_file=$this->dec($row['vote_count_file'],$this->iky);
            
            $cards_file="../".explode("https://clubsatsrit.in/",$cards_file)[1];
            $count_file="../".explode("https://clubsatsrit.in/",$count_file)[1];

            if (file_exists($cards_file)) {
                unlink($cards_file);
            }
            if (file_exists($count_file)) {
                unlink($count_file);
            } 
            
            $stmt=$conn->prepare("DELETE FROM vote_cards WHERE club_id=? AND eve_id=?");
            $stmt->bind_param("ss", $cid,$eid);
            $success=$stmt->execute();
            $stmt->close();
            return $success;
        }else{
            return 0;
        }
    }
    public function newVoting($cid,$eid,$file_name){
        $conn=$this->connect();
        $eid=$this->dec(mysqli_real_escape_string($conn,$eid),$this->iky);
        $cid=$this->dec(mysqli_real_escape_string($conn,$cid),$this->iky);
        $eid=$this->enc($eid,$this->iky,'idx');
        $cid=$this->enc($cid,$this->iky,'idx');
        $file_name=$this->enc(mysqli_real_escape_string($conn,$file_name),$this->iky,'mtr');
        $isLive=0;
        $stmt=$conn->prepare("INSERT INTO vote_cards (club_id,eve_id,vote_cards_file,is_live) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $cid,$eid,$file_name,$isLive);
        $success=$stmt->execute();
        $stmt->close();
        return $success;
    }
    public function fetch_file($cid,$eid,$file_type){
        $conn=$this->connect();
        $eid=$this->dec(mysqli_real_escape_string($conn,$eid),$this->iky);
        $cid=$this->dec(mysqli_real_escape_string($conn,$cid),$this->iky);
        $eid=$this->enc($eid,$this->iky,'idx');
        $cid=$this->enc($cid,$this->iky,'idx');
        $file_type=mysqli_real_escape_string($conn,$file_type);
        $stmt=$conn->prepare("SELECT vote_cards_file, vote_count_file, is_live FROM vote_cards WHERE club_id=? AND eve_id=?");
        $stmt->bind_param("ss", $cid,$eid);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows>0){
            $row=$result->fetch_assoc();
            $fname="";
            // if($file_type=="vote_cards"){
                $fname=$this->dec($row['vote_cards_file'],$this->iky);
            // }elseif($file_type=="vote_count"){
                $fname_vcount=$this->dec($row['vote_count_file'],$this->iky);
            // }
            $uid=$_SESSION['ses_id'];
            $uid=$this->dec($uid,$this->iky);
            $stmt=$conn->prepare("SELECT user_name FROM users WHERE uid=?");
            $stmt->bind_param("s", $uid);
            $stmt->execute();
            $rollRow = $stmt->get_result()->fetch_assoc();
            $rollno=$this->sbldc($rollRow['user_name'],$this->iky);
            $uid=$this->sblen($uid,$this->iky,'idx');
            $array=["file"=>$fname,"vfile"=>$fname_vcount,"isLive"=>$row['is_live'],"rollno"=>$rollno,"uid"=>$uid];
            if($file_type=="vote_count"){
                $array=array_merge($array,$this->getRegAttCount($eid));
            }
            return json_encode($array);
        }else{
            return 0;
        }
    }
    public function getRegAttCount($eid){
        $conn=$this->connect();
        $eid=$this->dec(mysqli_real_escape_string($conn,$eid),$this->iky);
        $eid=$this->sblen($eid,$this->iky,'idx');
        $stmt=$conn->prepare("SELECT COUNT(parti_id) as pid FROM participants WHERE eve_id=? AND status=0");
        $stmt->bind_param("s",$eid);
        $stmt->execute();
        $regCount = $stmt->get_result()->fetch_assoc()['pid'];
        $stmt=$conn->prepare("SELECT COUNT(parti_id) as pid FROM participants WHERE eve_id=? AND status=1");
        $stmt->bind_param("s",$eid);
        $stmt->execute();
        $attCount = $stmt->get_result()->fetch_assoc()['pid'];
        return ["reg"=>$regCount,"att"=>$attCount];
    }
    public function getVoters($uids){
        $conn=$this->connect();
        $uids=mysqli_real_escape_string($conn,$uids);
        $uids=explode(',', $uids);
        $i=1;
        $uidsAll="";
        foreach ($uids as $value) {
            $uid=$this->sbldc($value,$this->iky);
            $uidsAll.= "uid='".$uid."'";
            $uidsAll.= $i>=count($uids)? "" : " OR ";
            $i++;
        }
        $stmt=$conn->prepare("SELECT full_name,user_name,dp FROM users WHERE $uidsAll");
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows>0){
            $rows=$result->fetch_all();
            $usersArray=[];
            foreach($rows as $row){
                $array=["fname"=>$this->dec($row[0],$this->iky),"uname"=>$this->sbldc($row[1],$this->iky),"dp"=>$this->dec($row[2],$this->iky)];
                array_push($usersArray,$array);
            }
            return json_encode($usersArray);
        }else{
            return 0;
        }
    }
}
$vote= new Vote();

if(isset($_POST['voted_user'],$_POST['uids'])&&$_POST['voted_user']=="true"){
    echo $vote->getVoters($_POST['uids']);
}
if(isset($_POST['cid'],$_POST['eid'],$_POST['fetch_file_type'])){
        echo $vote->fetch_file($_POST['cid'],$_POST['eid'],$_POST['fetch_file_type']);
}
if(isset($_POST['cid'],$_POST['eid'],$_POST['delVoting'])&&$_POST['delVoting']=="true"){
        echo $vote->DeleteVoting($_POST['cid'],$_POST['eid']);
}

// Get JSON data sent via POST
$jsonData = file_get_contents('php://input');


// Optionally, you can process or validate the data here
if(isset($_SERVER["HTTP_TYPE"])&&$_SERVER["HTTP_TYPE"]=="create"){
// Write the JSON data to a file
$file_name=generateUniqueFileName();
$path='../votings/'.$file_name;
file_put_contents($path, $jsonData);

$cid=$_SERVER["HTTP_CID"];
$eid=$_SERVER["HTTP_EID"];
$path="https://clubsatsrit.in/votings/".$file_name;

if($vote->newVoting($cid,$eid,$path)){
    echo $path;
}else{
    echo 0;
}

// sql functions.....
}
elseif(isset($_SERVER["HTTP_TYPE"])&&isset($_SERVER["HTTP_FNAME"])&&$_SERVER["HTTP_TYPE"]=="update"){
    // Write the JSON data to a file
   
        // $jsonFilePath = '../votings/'.$_SERVER["HTTP_FNAME"];
        $jsonFilePath = "../".explode("https://clubsatsrit.in/",$_SERVER["HTTP_FNAME"])[1];
        // Write the data to the file
        if (file_put_contents($jsonFilePath, $jsonData)) {
            echo json_encode(["message" => "Data successfully updated"]);
        } else {
            echo json_encode(["error" => "Failed to write to file"]);
        }
    
    
    // sql functions.....
}

elseif(isset($_SERVER["HTTP_TYPE"])&&$_SERVER["HTTP_TYPE"]=="make-live"){
    $file_name=generateUniqueFileName();
    $path='../voteCounts/'.$file_name;
    
    $jsonData = json_decode($jsonData,true);
    file_put_contents($path, $jsonData);
    
    $cid=$_SERVER["HTTP_CID"];
    $eid=$_SERVER["HTTP_EID"];
    $path_nw="https://clubsatsrit.in/voteCounts/".$file_name;
  
    echo $vote->makeVotingLive($cid,$eid,$path_nw);
    // sql functions.....
}
  
function generateUniqueFileName() {
    $text = 'file'; // Fixed text part
    $timeStamp = uniqid(); // Generate a unique ID based on the current microsecond time
    $randomPart = substr(md5(mt_rand()), 0, 5); // Generate a random alphanumeric string and take 5 characters

    return $text . '_' . $timeStamp . '_' . $randomPart . '.json'; // .ext is a placeholder for file extension
}