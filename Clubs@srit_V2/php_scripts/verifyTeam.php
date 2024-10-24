<?php
include 'db_conn.php';  // connect to database
class verifyTeam extends dbconnect{
  public function verifyTeam(){
    $conn=$this->connect();
    // now i need to verify if the session ses_id is same with any one of uid
    $clubId=$_GET['cid'];
    $sid=$_SESSION['ses_id'];
    $sid=$this->dec(mysqli_real_escape_string($conn,$sid),$this->iky);
    $clubId=$this->dec(mysqli_real_escape_string($conn,$clubId),$this->iky);
    $clubId=$this->sblen($clubId,$this->iky,'idx');
    $stmt = $conn->prepare("SELECT uid,t_role,member_type FROM club_exe_team WHERE club_id = ?");
    $stmt->bind_param("s", $clubId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $rows = $result->fetch_all();
        $array1=["isMemb"=>0,"mt"=>""];
        foreach($rows as $row){
          if($this->sbldc($row[0],$this->iky)==$sid){
          $array1=["isMemb"=>1,"t_role"=>$this->dec($row[1],$this->iky),"mt"=>$this->dec($row[2],$this->iky)];
          }
        }
        return $array1;
    }else{
        return 0;
    }
  }
  public function getPerson($uid){
    $conn=$this->connect();
    $uid=$this->dec(mysqli_real_escape_string($conn,$uid),$this->iky);
    $stmt = $conn->prepare("SELECT full_name,uid, user_name,dp FROM users WHERE uid = ?");
        $stmt->bind_param("s", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ["name"=>$this->dec($row['full_name'],$this->iky),"uid"=>$row["uid"],"user_name"=>$this->sbldc($row['user_name'],$this->iky),"dp"=>$this->dec($row['dp'],$this->iky)];
        }else{
          return 0;
        }
  }
}
$v=new verifyTeam();
if(isset($_SESSION['ses_id'])){
$team=$v->verifyTeam();
}else{
  $team=["isMemb"=>0,"mt"=>""];
}

?>