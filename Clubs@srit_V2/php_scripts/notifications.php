<?php
include 'db_conn.php';
class notify extends dbconnect{
   public function getNotifies(){
    $conn=$this->connect();
    $uid=$_SESSION['ses_id'];
    $uid=$this->dec($uid,$this->iky);
    $uid=$this->enc($uid,$this->iky,'idx');
    $stmt=$conn->prepare("SELECT na.nid,na.msg,na.act_Link,cb.cname,cb.cpic,na.timestamp
    FROM notifies na 
    INNER JOIN clubs cb ON na.sndr_id = cb.cid 
    WHERE na.rec_id = ? ORDER BY na.timestamp DESC");
    
    $stmt->bind_param("s",$uid);
    $stmt->execute();
    $result=$stmt->get_result();
    if($result->num_rows>0){
        $notifies=$result->fetch_All();
        $nfc="";
        foreach($notifies as $notify){
            $nid=$this->enc($notify[0],$this->iky,'idx');
            $msg=$this->dec($notify[1],$this->iky);
            $link=$this->dec($notify[2],$this->iky);
            $cname=$this->sbldc($notify[3],$this->iky);
            $cpic=$this->dec($notify[4],$this->iky);
            $time=explode(" ago",$this->timeAgo($notify[5]))[0];

            $nfc.="<div class='notfication'>
            <div class='notify-details' data-url='$link' data-nid='$nid'>
              <img
                src='$cpic'
                alt=''
                class='notify-img img-sq'
              />
              <div class='notify-by-box'>
                <h3 class='notify-by-txt sub-heading'>$cname</h3>
                <p class='notify-msg'>$msg
                </p>
              </div>
            </div>
            <div class='time-box'>$time</div>
          </div>";
        }
        return $nfc;
    }else{
        return 0;
    }
   }
   public function notifySeen(){
    $conn=$this->connect();
    $uid=$_SESSION['ses_id'];
    $uid=$this->dec($uid,$this->iky);
    $uid=$this->enc($uid,$this->iky,'idx');
    $stmt=$conn->prepare("UPDATE notifies SET is_notified=1
    WHERE rec_id = ? ");
    $stmt->bind_param("s",$uid);
    $stmt->execute();
   }
   public function deleteNotify(){
    if(!isset($_SESSION['del_notify'])){
        $conn=$this->connect();
        $uid=$_SESSION['ses_id'];
        $uid=$this->dec($uid,$this->iky);
        $uid=$this->enc($uid,$this->iky,'idx');
        $stmt=$conn->prepare("DELETE FROM notifies
        WHERE rec_id = ? AND timestamp < DATE_SUB(NOW(), INTERVAL 2 WEEK)");
        $stmt->bind_param("s",$uid);
        if($stmt->execute()){
            $_SESSION['del_notify']=true;
        }
    }
   }
}

$notify = new notify();

if(isset($_POST['getNotifies'])&&$_POST['getNotifies']=="true"){
    $notify->notifySeen();
    $notify->deleteNotify();
    echo $notify->getNotifies();
}


