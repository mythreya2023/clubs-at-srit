<?php
class comn_funcs{
    private $host; private $dbuname;
    private $dbpwd; private $dbname;
    private function connect (){
       $oopconn=new mysqli( $this->host="localhost",
       $this->dbuname="u667736305_sritclubs",$this->dbpwd="Sritclubs.123",
        $this->dbname="u667736305_sritclubs");
        return $oopconn;
    }
    protected function dateFormater($date,$type){
        $dateString = $date; // Or "02-03-2024"
        if($type=="write"){
        // Split the date string into day, month, and year components
        $dateComponents = explode("-", $dateString);
        
        // Ensure two-digit formatting for day and month
        $day = str_pad($dateComponents[0], 2, "0", STR_PAD_LEFT);
        $month = str_pad($dateComponents[1], 2, "0", STR_PAD_LEFT);
        $year = strlen($dateComponents[2]) === 2 ? "20" . $dateComponents[2] : $dateComponents[2];
        
        // Format the date
        $dateString = $day . "-" . $month . "-" . $year;
        }
        // Convert the date string to a timestamp
        $timestamp = strtotime($dateString);
        // $timestamp=time();

        // Format the timestamp to the desired format

        $formattedDate = $type=="write"?date("Y-m-d", $timestamp):date("d-m-Y", $timestamp);

        return $formattedDate;
    }
public function extract_Clg_Mail($email) {
    // Regular expression to match the email pattern
    $pattern = '/(\d{2})4g(1a|5a)(\d{2})(\d{2})@srit\.ac\.in/';
    $email=strtolower($email);
    // Perform the regular expression match
    if (preg_match($pattern, $email, $matches)) {
        // Extracting the components
        $yearOfJoining = $matches[1];
        $entryType = $matches[2] == '1a' ? 'Regular' : 'Lateral Entry';
        $branchCode = $matches[3];
        $rollno=$matches[4];

        // Returning the extracted information
        return [
            'year' => $yearOfJoining,
            'type' => $entryType,
            'branch' => $branchCode,
            'rollno'=>$rollno
        ];
    } else {
        return false;
    }
}
    // below are the keys for encryption
    protected $iky="rmdA3S0Cquwosown,z;osn%caqoqnfczdnuvaksdnfakjn";
    protected $mky="rmdA3S0Cquwoown,z;osn%caqoqnfcdniuvaksdnfakjn";
    protected $strec="rmdA3S0Cquwoown,z;osn%caqoqncqdniuvstraksdnfakjn";

    protected function enc($data,$key,$typ){ // normal encryption
        $tp=htmlspecialchars($typ);
        $ciphering="AES-128-CTR";
        if($tp=='idx'){$iv = '1234567891011121';}
        if($tp=='strix'){$iv = '1232567391041121';}
        elseif($tp=='mtr'){$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($ciphering));}
        $encryption = openssl_encrypt($data,$ciphering,$key,0, $iv);
        return base64_encode($encryption.'::'.$iv);
    }
    protected function dec($data,$key){ // normal decryption
        list($encrypted_data,$iv)=array_pad(explode('::',base64_decode($data),2),2,null);
        $decryption=openssl_decrypt ($encrypted_data, "AES-128-CTR",$key, 0, $iv);
        return $decryption;
    }
    protected function sblen($data,$key,$typ){ // searchable encryption
        $data=str_split($data);
        $s="";
        foreach($data as $l){
            $s.=substr($this->enc($l,$key,$typ),0,3);
        }
        return $s;
    }
    protected function sbldc($data,$key){ // searchable decryption
        $data=str_split($data,3);
        $s="";$othf="";
        if($key==$this->strec){
            $othf="9PTo6MTIzMjU2NzM5MTA0MTEyMQ==";
                 
        }elseif($key==$this->iky){
        $othf="9PTo6MTIzNDU2Nzg5MTAxMTEyMQ==";
              
        }
        foreach($data as $l){
            $d=$l.$othf;
            $s.=$this->dec($d,$key);
        }
        return $s;
    }
    
    protected function replacer($data) {
        $search = array('\r',"\'",'&amp;nbsp;','&lt;br&gt;', '&lt;div&gt;', '&lt;span&gt;', '&lt;/div&gt;', '&lt;/span&gt;');
        $replace = array('',"'",' ','<br>', '<div>', '<span>', '</div>', '</span>');
    
        return str_replace("&nbsp;",""," ",str_replace($search, $replace, $data));
    }    
    
    protected function getFeatureImage($url) {
        // Check if the URL is a YouTube link
        if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
            return $this->getYouTubeThumbnail($url);
        }

        // For other URLs, use a basic HTML scraping method
        // $html = file_get_contents($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Set a timeout
        $html = curl_exec($ch);
        curl_close($ch);

        if (!$html) {
            return null;
        }

        // Use DOMDocument to parse the HTML
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $metaTags = $doc->getElementsByTagName('meta');

        foreach ($metaTags as $meta) {
            if ($meta->getAttribute('property') === 'og:image') {
                return $meta->getAttribute('content');
            }
        }

        return null;
    }

    protected function getYouTubeThumbnail($url) {
        // Parse YouTube URL to get the video ID
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['host']) && $parsedUrl['host'] === 'youtu.be') {
            $videoId = ltrim($parsedUrl['path'], '/');
        } else {
            parse_str($parsedUrl['query'], $query);
            $videoId = $query['v'] ?? null;
        }

        return $videoId ? "https://img.youtube.com/vi/$videoId/maxresdefault.jpg" : null;
    }

    protected function highlightLinks($text) {
        $urlPattern = '/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';
        
        $pattern = $urlPattern;

        $newText = $text;

        $search = array('<div>', '<span>', '</div>', '</span>');
    
        $newText = str_replace($search,"", $newText);
        $newText = explode("<br>",$newText);

        $text = str_replace("\'","'",$text);
        foreach($newText as $ntxt){

            $pattern='/(https?:\/\/[^\s]+)/';
            preg_match_all($pattern, $ntxt, $matches);
            $links = $matches[0];
            $image = "";
            if (!empty($links)) {
                foreach ($links as $link) {
                    $imageUrl = $this->getFeatureImage($link);
                    if ($imageUrl) {
                    $image = '<img src="'.$imageUrl.'" alt="" class="club-post-img">';
                    }
                    $text = str_replace($link,"<a href='$link' target='_blank' style='line-break: anywhere;'>$link $image</a>",$text);
                }
            }
        }
        return $text;
    }
    // time ago function converts normal timestamp into something like 1hr ago or 1day ago
    protected function timeAgo($timestamp) {
        $tmz="Asia/kolkata";
        $tmz="Europe/Berlin";
        date_default_timezone_set($tmz);
        $time_ago = strtotime($timestamp);
        $current_time = time();
        $time_difference = $current_time - $time_ago;
        $seconds = $time_difference;
    
        $minutes      = round($seconds / 60);      // value 60 is seconds
        $hours        = round($seconds / 3600);    // value 3600 is 60 minutes * 60 sec
        $days         = round($seconds / 86400);   // value 86400 is 24 hours * 60 minutes * 60 sec
        $weeks        = round($seconds / 604800);  // value 604800 is 7 days * 24 hours * 60 minutes * 60 sec
        $months       = round($seconds / 2629440); // value 2629440 is ((365+365+365+365+366)/5/12) days * 24 hours * 60 minutes * 60 sec
        $years        = round($seconds / 31553280); // value 31553280 is ((365+365+365+365+366)/5) days * 24 hours * 60 minutes * 60 sec
    
        if ($seconds <= 60) {
            return "Now";
        } else if ($minutes <= 60) {
            return ($minutes == 1) ? "1 min ago" : "$minutes min ago";
        } else if ($hours <= 24) {
            return ($hours == 1) ? "1 hour ago" : "$hours hrs ago";
        } else if ($days <= 7) {
            return ($days == 1) ? "yesterday" : "$days days ago";
        } else if ($weeks <= 4.3) { // 4.3 == 52/12
            return ($weeks == 1) ? "week ago" : "$weeks weeks ago";
        } else if ($months <= 12) {
            return ($months == 1) ? "month ago" : "$months months ago";
        } else {
            return ($years == 1) ? "year ago" : "$years years ago";
        }
    }
 
    public function notify($cid,$uid,$msg,$link,$type){
        //$type will say if to send all followers or to specific person or to membership people.

        $conn=$this->connect();
        $cid=mysqli_real_escape_string($conn,$cid);
        $uid=$this->enc(mysqli_real_escape_string($conn,$uid),$this->iky,'idx');
        $msg=$this->enc(mysqli_real_escape_string($conn,$msg),$this->iky,'mtr');
        $link=$this->enc(mysqli_real_escape_string($conn,$link),$this->iky,'mtr');
        $is_notified=0;

        if($type=="followers"){
            $users=$this->getPeople($cid);
            if($users!=0){
                foreach($users as $user){    
                    $stmt=$conn->prepare("INSERT INTO notifies(sndr_id,rec_id,msg,act_Link,is_notified) VALUES (?,?,?,?,?)");
                    $stmt->bind_param("sssss",$cid,$user[0],$msg,$link,$is_notified);
                    $stmt->execute();
                }
            }
        }if($type=="members"){
            $users=$this->getMembers($cid);
            if($users!=0){
                foreach($users as $user){
                    $uid=$this->enc($user[0],$this->iky,'idx');    
                    $stmt=$conn->prepare("INSERT INTO notifies(sndr_id,rec_id,msg,act_Link,is_notified) VALUES (?,?,?,?,?)");
                    $stmt->bind_param("sssss",$cid,$uid,$msg,$link,$is_notified);
                    $stmt->execute();
                }
            }
        }if($type=="attended"){
            $users=$this->getAttendece($cid);
            if($users!=0){
                foreach($users as $user){
                    $uid=$this->dec($user[0],$this->iky);    
                    $uid=$this->enc($uid,$this->iky,'idx');  
                    $stmt=$conn->prepare("INSERT INTO notifies(sndr_id,rec_id,msg,act_Link,is_notified) VALUES (?,?,?,?,?)");
                    $stmt->bind_param("sssss",$cid,$uid,$msg,$link,$is_notified);
                    $stmt->execute();
                }
            }
        }elseif($type=="person"){
            $stmt=$conn->prepare("INSERT INTO notifies(sndr_id,rec_id,msg,act_Link,is_notified) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss",$cid,$uid,$msg,$link,$is_notified);
            $stmt->execute();
        }
   }
   public function getPeople($cid){
        $conn=$this->connect();
        $cid=mysqli_real_escape_string($conn,$cid);
        $cid=$this->enc(mysqli_real_escape_string($conn,$cid),$this->iky,'idx');
        $stmt = $conn->prepare("SELECT us_id FROM club_follow WHERE clb_id=?");
        $stmt->bind_param("s",$cid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_all();
        }else{
            return 0;
        }
   }
   public function getAttendece($eid){
        $conn=$this->connect();
        $eid=mysqli_real_escape_string($conn,$eid);
        $eid=$this->enc(mysqli_real_escape_string($conn,$eid),$this->iky,'idx');
        $stmt = $conn->prepare("SELECT u_id FROM participants WHERE eve_id=?");
        $stmt->bind_param("s",$eid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_all();
        }else{
            return 0;
        }
   }
   public function getMembers($cid){
        $conn=$this->connect();
        $cid=mysqli_real_escape_string($conn,$cid);
        $cid=$this->enc(mysqli_real_escape_string($conn,$cid),$this->iky,'idx');
        $stmt = $conn->prepare("SELECT us.uid, us.mail_id FROM users us INNER JOIN membership ms ON us.user_name=ms.u_id WHERE ms.club_id=?");
        $stmt->bind_param("s",$cid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_all();
        }else{
            return 0;
        }
   }

   public function mail_notify($cid,$uid,$subj,$msg,$type){
        //$type will say if to send all followers or to specific person or to membership people.
        
        $conn=$this->connect();
        $cid=mysqli_real_escape_string($conn,$cid);
        $uid=mysqli_real_escape_string($conn,$uid);
        $subj=$this->enc(mysqli_real_escape_string($conn,$subj),$this->iky,'idx');
        $msg=$this->enc(mysqli_real_escape_string($conn,$msg),$this->iky,'mtr');

        if($type=="followers"){
            $users=$this->getPeople($cid);
            if($users!=0){
                foreach($users as $user){    
                    $uid=$this->dec($user[0],$this->iky);  
                    $stmt=$conn->prepare("SELECT mail_id FROM users where uid = ? AND send_email = 1;");
                    $stmt->bind_param("s",$uid);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $user_email=$result->fetch_assoc()['mail_id'];
                        $stmt=$conn->prepare("INSERT INTO email_queue(recipient,subject,message) VALUES (?,?,?);");
                        $stmt->bind_param("sss",$user_email,$subj,$msg);
                        $stmt->execute();
                    }
                }
            }
        }if($type=="members"){
            $users=$this->getMembers($cid);
            if($users!=0){
                foreach($users as $user){
                    $user_email=$user[0];
                    $stmt=$conn->prepare("INSERT INTO email_queue(recipient,subject,message) VALUES (?,?,?);");
                    $stmt->bind_param("sss",$user_email,$subj,$msg);
                    $stmt->execute();
                }
            }
        }if($type=="attended"){
            $users=$this->getAttendece($cid);
            if($users!=0){
                foreach($users as $user){
                    $uid=$this->dec($user[0],$this->iky);  
                    $stmt=$conn->prepare("SELECT mail_id FROM users where uid = ?;");
                    $stmt->bind_param("s",$uid);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $user_email=$result->fetch_assoc()['mail_id'];
                    $stmt=$conn->prepare("INSERT INTO email_queue(recipient,subject,message) VALUES (?,?,?);");
                    $stmt->bind_param("sss",$user_email,$subj,$msg);
                    $stmt->execute();
                }
            }
        }elseif($type=="person"){
            // this is for notifying like if a club added this person as a executive team member or not like that. the message of this notification should be same.

            $stmt=$conn->prepare("SELECT mail_id FROM users where uid = ?;");
            $stmt->bind_param("s",$uid);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_email=$result->fetch_assoc()['mail_id'];
            $stmt=$conn->prepare("INSERT INTO email_queue(recipient,subject,message) VALUES (?,?,?);");
            $stmt->bind_param("sss",$user_email,$subj,$msg);
            $stmt->execute();
            
        }
   }

}
