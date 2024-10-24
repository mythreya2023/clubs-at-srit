<?php
include 'db_conn.php';
class User extends dbconnect{
    public function updateDP($img){
        $conn=$this->connect();
        $uid=$_SESSION['ses_id'];
        $uid=$this->dec(mysqli_real_escape_string($conn,$uid),$this->iky);
        $img=$this->enc(mysqli_real_escape_string($conn,$img),$this->iky,'mtr');
        $stmt=$conn->prepare("UPDATE users SET dp=? WHERE uid=?");
        $stmt->bind_param("ss", $img,$uid);
        $success=$stmt->execute();
        $stmt->close();
        return $success;
    }
}
$user= new User();
if(isset($_FILES['image'],$_POST['createType'],$_POST['preImg'])) {
    
    $uploadFolder = 'images/';
    if($_POST['createType']=="club"||$_POST['createType']=="event"){
        $uploadFolder='pics/club_eve_img/';
    }elseif($_POST['createType']=="post"){
        $uploadFolder='pics/post_img/';
    }elseif($_POST['createType']=="profile_pic"){
        $uploadFolder='pics/profile_dp/';
    }
    $filename = $_FILES['image']['name'];
    $tempname = $_FILES['image']['tmp_name'];
    $filetype = $_FILES['image']['type'];

    $path=$uploadFolder.$filename;
    move_uploaded_file($tempname, "../".$path);

    if($_POST['preImg']!=""){
    $substr="https://";
    if(strpos($_POST['preImg'],$substr)===false){
        $fileToDelete = "../".$_POST['preImg']; // Replace this with the path to your image file
        if($fileToDelete!=""){
            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
            } 
        }
    }
    }

    $newImg="https://clubsatsrit.in/".$path;

    if($_POST['createType']=="profile_pic"){ 
        if($user->updateDP($newImg)){
            echo $newImg;
        }else{
            $fileToDelete = "../".$path; // Replace this with the path to your image file
            if($fileToDelete!=""){
                if (file_exists($fileToDelete)) {
                    unlink($fileToDelete);
                } 
            }
        }
    }else{
        echo $newImg;
    }
    // echo $_POST['pid'];
    // echo 'Image uploaded successfully';
}
?>
