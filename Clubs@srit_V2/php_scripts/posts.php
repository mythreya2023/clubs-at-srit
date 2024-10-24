<?php
session_start();
include 'db_conn.php';
class post extends dbconnect{
    public function getStudent($uid){
        $conn=$this->connect();
        $uid=$this->dec(mysqli_real_escape_string($conn,$uid),$this->iky);
        $stmt = $conn->prepare("SELECT full_name,dp FROM users WHERE uid = ?");
        $stmt->bind_param("s", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return ["name"=>$this->dec($row["full_name"],$this->iky),"dp"=>$this->dec($row["dp"],$this->iky)];
        }else{
            return 0;
        }
    }
    public function postUpdate($clubId,$text,$img,$cname){
        $conn=$this->connect();
        $club_Id=$this->dec(mysqli_real_escape_string($conn,$clubId),$this->iky);
        $clubId=$this->enc(mysqli_real_escape_string($conn,$club_Id),$this->iky,'idx');
        $CName=mysqli_real_escape_string($conn,$cname);
        $cname=$this->enc($CName,$this->iky,'mtr');
        $text=mysqli_real_escape_string($conn,$text);
        $text_h=$this->highlightLinks($text);
        $text=$this->enc($text_h,$this->iky,'mtr');
        $img=$this->enc(mysqli_real_escape_string($conn,$img),$this->iky,'mtr');
        $sid=$_SESSION['ses_id'];
        $sid=$this->dec(mysqli_real_escape_string($conn,$sid),$this->iky);
        $sid=$this->enc(mysqli_real_escape_string($conn,$sid),$this->iky,'idx');
        $stmt = $conn->prepare("INSERT INTO club_posts (c_id,u_id,post_txt,post_img,cname) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $clubId,$sid,$text,$img,$cname);
        $success=$stmt->execute();
        if($success){
            $CName = ucwords($CName);
            $CName = str_replace("_"," ",$CName);
            $subj = "$CName: A new update posted!";

            $link = "https://clubsatsrit.in/club?cid=$clubId";

            $msg = "<h1>A new post From $CName</h1>".$text_h."<a href='$link' class='action-btn'>View Full Post</a>";
            $this->mail_notify($club_Id,"",$subj,$msg,"followers");
        }
        $stmt->close();
        return $success;
    }
    public function deletePost($pid,$img){
        $conn=$this->connect();
        $img=mysqli_real_escape_string($conn,$img);
        $pid=$this->dec(mysqli_real_escape_string($conn,$pid),$this->iky);
        $stmt = $conn->prepare("DELETE FROM club_posts WHERE cpid=?");
        $stmt->bind_param("s",$pid);
        $success=$stmt->execute();
        $stmt->close();
        if($success&&$img!=""){
          $fileToDelete = "../".explode("https://clubsatsrit.in/",$img)[1]; 
          if($fileToDelete!=""){
          if (file_exists($fileToDelete)) {
              unlink($fileToDelete);
          } 
          }
        }
        return $success;
    }
    public function savePost($pid){
        $conn=$this->connect();
        $pid=$this->dec(mysqli_real_escape_string($conn,$pid),$this->iky);
        $pid=$this->enc(mysqli_real_escape_string($conn,$pid),$this->iky,'idx');
        $sid=$_SESSION['ses_id'];
        $sid=$this->dec(mysqli_real_escape_string($conn,$sid),$this->iky);
        $sid=$this->enc(mysqli_real_escape_string($conn,$sid),$this->iky,'idx');
        $saved=1;
        $stmt = $conn->prepare("INSERT INTO post_interactions (post_id,us_id,saved) VALUES (?,?,?)");
        $stmt->bind_param("sss", $pid,$sid,$saved);
        $success=$stmt->execute();
        $stmt->close();
        return $success;
    }
    public function unSavePost($pid){
        $conn=$this->connect();
        $pid=$this->dec(mysqli_real_escape_string($conn,$pid),$this->iky);
        $pid=$this->enc($pid,$this->iky,'idx');
        $sid=$_SESSION['ses_id'];
        $sid=$this->dec(mysqli_real_escape_string($conn,$sid),$this->iky);
        $sid=$this->enc($sid,$this->iky,'idx');
       
        $stmt = $conn->prepare("DELETE FROM post_interactions WHERE post_id = ? AND us_id=?");
        $stmt->bind_param("ss", $pid,$sid);
        $success=$stmt->execute();
        $stmt->close();
        return $success;
    }
    public function isPostSaved($pid){
        $conn=$this->connect();
        $pid=$this->enc(mysqli_real_escape_string($conn,$pid),$this->iky,'idx');
        $sid=$_SESSION['ses_id'];
        $sid=$this->dec(mysqli_real_escape_string($conn,$sid),$this->iky);
        $sid=$this->enc($sid,$this->iky,'idx');
        $stmt = $conn->prepare("SELECT pst_id FROM post_interactions WHERE post_id=? AND us_id=? AND saved=1;");
        $stmt->bind_param("ss", $pid,$sid);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows>0){
            $row=$result->fetch_assoc();
            return $row['pst_id'];
        }else{
            return 0;
        }
    }
    public function allPosts($clubId,$offset,$cids,$pids){
        $conn=$this->connect();
        $clubId=mysqli_real_escape_string($conn,$clubId);
        $cids=mysqli_real_escape_string($conn,$cids);
        $pids=mysqli_real_escape_string($conn,$pids);
        if($clubId!=""&&$cids==""&&$pids==""){
          $clubId=$this->dec($clubId,$this->iky);
          $clubId=$this->enc($clubId,$this->iky,'idx');
          $stmt = $conn->prepare("SELECT cpid,u_id,cname,post_txt,post_img,timestamp,c_id FROM club_posts WHERE c_id = ? ORDER BY timestamp DESC LIMIT 10 OFFSET ?;");
          $stmt->bind_param("ss", $clubId,$offset);
        }elseif($clubId==""&&$cids!=""&&$pids==""){
          $cidsAll="";
          $i=1;
          $cids=explode(',', $cids);
          foreach ($cids as $value) {
              $cid=$this->enc($value,$this->iky,'idx');
              $cidsAll.= "c_id='".$cid."'";
              $cidsAll.= $i>=count($cids)? "" : " OR ";
              $i++;
          }
          $stmt = $conn->prepare("SELECT cpid,u_id,cname,post_txt,post_img,timestamp,c_id FROM club_posts WHERE $cidsAll ORDER BY timestamp DESC LIMIT 10 OFFSET ?;");
          $stmt->bind_param("s", $offset);
        }elseif($clubId==""&&$cids==""&&$pids!=""){
          $pidsAll="";
          $i=1;
          $pids=explode(',', $pids);
          foreach ($pids as $pid) {
              $pidsAll.= "cpid='".$pid."'";
              $pidsAll.= $i>=count($pids)? "" : " OR ";
              $i++;
          }
          $stmt = $conn->prepare("SELECT cpid,u_id,cname,post_txt,post_img,timestamp,c_id FROM club_posts WHERE $pidsAll ORDER BY timestamp DESC;");
        }
        if($clubId!=""||$cids!=""||$pids!=""){
        $stmt->execute();
        $result = $stmt->get_result();
        $posts="";
        if ($result->num_rows > 0) {
            $rows = $result->fetch_all();
            foreach($rows as $row){
                $isSaved=$this->isPostSaved($row[0]);
                $color = $isSaved!=0?"black":"none";
                $class = $color=="none"?"save-post-btn":"unsave-post-btn";
                $pos_interaction_id=$isSaved!=0?$isSaved:"";
                $cpid=$this->enc($row[0],$this->iky,"mtr");
                $post_user=$this->dec($row[1],$this->iky);
                $ses_usr=$this->dec($_SESSION['ses_id'],$this->iky);
                $details=$this->getStudent($row[1]);
                $name=$details['name'];
                $dp=$details['dp'];
                $cname=$this->dec($row[2],$this->iky);
                $cname=str_replace("_"," ",$cname);
                $text=$this->dec($row[3],$this->iky);
                $text=$this->replacer($text);
                // $text=$this->highlightLinks($text);
                $img=$row[4]==""?"":$this->dec($row[4],$this->iky);
                $time=$this->timeAgo($row[5]);
                $posts.="
              <div class='club-post'>
                <div class='club-post-header' style='justify-content:space-between;'>
                  <div style='display:flex;align-items:center;'>
                    <img
                      src='$dp'
                      alt=''
                      class='club-poster-img'
                    />
                    <div class='club-poster-details'>
                      <h3 class='poster-name'>$name</h3>
                      <p class='by-club-name'>$cname - $time</p>
                    </div>
                  </div>";
                  if($ses_usr==$post_user){
                    $posts.="<span class='post-menue-btn animi-btn'>
                      <img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAARUlEQVR4nO3SsQkAIAxE0T+eIfsvoO6hCOnTGES4B2lzzQe5yIEJDMAoMIAV178csBg5z1vFgKRcmWZMmT7nyjRjypQKG4MDJKaPIqRCAAAAAElFTkSuQmCC'>
                      </span>";
                  }
                $posts.="</div>";
                if($ses_usr==$post_user){
                $posts.="<div class='menu-dots' style='display:none;
                position: absolute;
                z-index:0;
                background: white;
                border: 1px solid #535353a1;
                padding: 8px;
                border-radius: 5px;
                width: fit-content;
                font-size: 14px;
                font-family: sans-serif;
                right: 18px;
                min-width: 60px;'>
                  <div class='delete-post-btn animi-btn' data-cpid='$cpid' data-spd='$isSaved' data-img='$img'>Delete</div>
                </div>";
                }
                $posts.="<div class='club-post-body'>
                  <div class='club-post-body-text'>
                    $text
                  </div>";
                  if($img!=""){$posts.="<img
                    src='$img'
                    alt=''
                    class='club-post-img'
                  />";}
                $posts.="</div>
                <div class='club-post-footer'>
                <span class='$class animi-btn' data-cpid='$cpid' data-spd='$pos_interaction_id'>
                  <svg
                    xmlns='http://www.w3.org/2000/svg'
                    width='24'
                    height='24'
                    viewBox='0 0 24 24'
                    fill='$color'
                  >
                    <path
                      d='M19 21L12 16L5 21V5C5 4.46957 5.21071 3.96086 5.58579 3.58579C5.96086 3.21071 6.46957 3 7 3H17C17.5304 3 18.0391 3.21071 18.4142 3.58579C18.7893 3.96086 19 4.46957 19 5V21Z'
                      stroke='black'
                      stroke-width='2'
                      stroke-linecap='round'
                      stroke-linejoin='round'
                    />
                  </svg>
                  </span>
                </div>
              </div>
                ";
            }
            return $posts;
        }else{
            return 0;
        }
      }else{
        return 0;
      }
    }
}

$post= new post();

if(isset($_POST['post'])&&$_POST['post']=="true"&&isset($_POST['cid'])&&isset($_POST['txt'])&&isset($_POST['img'])&&isset($_POST['cname'])){
    echo $post->postUpdate($_POST['cid'],$_POST['txt'],$_POST['img'],$_POST['cname']);
}
if(isset($_POST['post'])&&$_POST['post']=="delete"&&isset($_POST['cid'],$_POST['img'])){
    echo $post->deletePost($_POST['cid'],$_POST['img']);
}

if(isset($_POST['postAction'])&&$_POST['postAction']=="F_clubs"&&isset($_POST['cids'])&&isset($_POST['offSet'])){
  echo $post->allPosts("",$_POST['offSet'],$_POST['cids'],"");
}
if(isset($_POST['allpost'])&&$_POST['allpost']=="true"&&isset($_POST['cid'])&&isset($_POST['offSet'])){
    echo $post->allPosts($_POST['cid'],$_POST['offSet'],"","");
}
if(isset($_POST['savePost'])&&$_POST['savePost']=="true"&&isset($_POST['cpid'])){
    echo $post->savePost($_POST['cpid']);
}

if(isset($_POST['unsavePost'])&&$_POST['unsavePost']=="true"&&isset($_POST['cpid'])){
  echo $post->unSavePost($_POST['cpid']);
}