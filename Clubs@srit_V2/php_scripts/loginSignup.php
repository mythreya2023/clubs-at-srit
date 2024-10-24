<?php
include 'db_conn.php';
class User extends dbconnect{

    private function identifyEmailType($email) {
        // Pattern for student email IDs
        $studentPattern = '/^\d{2}4g(1a|5a)\d{2}\d+@srit\.ac\.in$/';
    
        // Pattern for faculty email IDs
        $facultyPattern = '/^[a-z]+\.([a-z]+)@srit\.ac\.in$/';
    
        if (preg_match($studentPattern, $email)) {
            return 1;
        } elseif (preg_match($facultyPattern, $email)) {
            return 2;
        } else {
            return 0;
        }
    }
    private function userExists($mail_id) {
        $conn=$this->connect();
        $mail = mysqli_real_escape_string($conn,$mail_id);
        $mail_id=$this->sblen($mail,$this->iky,'idx');
        $stmt = $conn->prepare("SELECT * FROM users WHERE mail_id = ?");
        $stmt->bind_param("s", $mail_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    public function login($mail_id, $pwd) {
        $conn=$this->connect();
        $mail = mysqli_real_escape_string($conn,$mail_id);
        $mail_id=$this->sblen($mail,$this->iky,'idx');
        $pwd=mysqli_real_escape_string($conn,$pwd);
        $stmt = $conn->prepare("SELECT uid, user_name, pwd FROM users WHERE mail_id = ?");
        $stmt->bind_param("s", $mail_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($pwd, $row['pwd'])) {
                $id=$this->enc($row['uid'],$this->iky,'mtr');
                session_start();
                $fac_Stu=$this->identifyEmailType($mail);
                $_SESSION['ses_id'] = $id;
                $_SESSION['us_tp'] = $fac_Stu;
                
                setcookie("_uid_", $id, time() + (3600 * 24 * 365 ), "/"); 
                setcookie("_us_tp_", $fac_Stu, time() + (3600 * 24 * 365 ), "/"); 
                
                
                return "success";
            } else {
                return "Invalid password.";
            }
        } else {
            return "No user found with that email address.";
        }
    }


    public function register($user_name, $mail_id, $pwd) {
        $conn=$this->connect();
        $email=mysqli_real_escape_string($conn,$mail_id);
        $utype=$this->identifyEmailType($email);
        if($utype!=0){
        if($this->userExists($mail_id)==0){
        $user_Name=mysqli_real_escape_string($conn,$user_name);
        $user_name=$this->enc($user_Name,$this->iky,'mtr');
        $mail_id=$this->sblen($email,$this->iky,'idx');
        $pwd=mysqli_real_escape_string($conn,$pwd);

        $branchCodes=["05"=>"CSE","33"=>"CSM","32"=>"CSD","01"=>"CIVIL","03"=>"MECH","02"=>"EEE","04"=>"ECE"];
        $extractor=$utype==1?$this->extract_Clg_Mail($email)["branch"]:"";
        $branch=$utype==1?$this->sblen($branchCodes[$extractor],$this->iky,'idx'):"";

        $parts = explode('@', $email);
        $uname = $this->sblen($parts[0],$this->iky,'idx');
        $dp=$this->enc("https://uxwing.com/wp-content/themes/uxwing/download/peoples-avatars/man-user-circle-icon.png",$this->iky,'idx');

        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (full_name, mail_id, pwd, branch, u_type,user_name,dp,send_email) VALUES (?, ?, ?, ?, ?, ?,?,1)");
        $stmt->bind_param("sssssss", $user_name, $mail_id, $hashedPwd,$branch,$utype,$uname,$dp);
        $success = $stmt->execute();
        $stmt->close();
        $this->loginMail($email,$user_Name);
        return $success;
        }else{
            return "Already Registered. Please login.";
        }
        }else{
            return "Enter Valid College Email Id.";
        }
    }
    public function forgotPwd($mail){
        $conn=$this->connect();
        $Email=mysqli_real_escape_string($conn,$mail);
        $mail=$this->sblen($Email,$this->iky,'idx');
        $stmt = $conn->prepare("SELECT uid, user_name, pwd FROM users WHERE mail_id = ?");
        $stmt->bind_param("s", $mail);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows>0){
            $row=$result->fetch_assoc();
            $usid=$this->sblen($row['uid'],$this->iky,'idx');
            setcookie("usid", $usid, time() + (30 * 60), "/"); 
            $otp = rand(100000, 999999);
            $otp_e=$this->enc($otp,$this->iky,'idx');
            $stmt = $conn->prepare("UPDATE users SET token=? WHERE mail_id = ?");
            $stmt->bind_param("ss", $otp_e,$mail);
            $success=$stmt->execute();
            if($success){
                $to = $Email; // Email address of the recipient
                $subject = "Reset your Password"; // Subject of the email
                $headers = "From: Clubs@SRIT <no-reply@clubsatsrit.in>\r\n"; // Sender's email address
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    
                $message="<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Forgot Password - Reset OTP</title>
                    <style>
                        body {
                            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                            background-color: #f2f2f2;
                            margin: 0;
                            padding: 0;
                        }
                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            padding: 20px;
                            background-color: #ffffff;
                            border-radius: 10px;
                            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
                        }
                        h1 {
                            color: #333333;
                            text-align: center;
                            font-weight: 700;
                            margin-bottom: 20px;
                        }
                        p {
                            color: #666666;
                            font-size: 16px;
                            line-height: 1.5;
                            margin-bottom: 10px;
                        }
                        .otp {
                            color: #333333;
                            font-size: 24px;
                            text-align: center;
                            margin-bottom: 20px;
                        }
                        .note {
                            color: #666666;
                            font-size: 14px;
                            text-align: center;
                        }
                        .action-btn {
                            display: block;
                            width: 100%;
                            max-width: 200px;
                            margin: 20px auto;
                            padding: 12px 20px;
                            background-color: #007bff;
                            color: #ffffff;
                            text-decoration: none;
                            text-align: center;
                            border-radius: 5px;
                            font-size: 16px;
                            transition: background-color 0.3s ease;
                        }
                        .action-btn:hover {
                            background-color: #0056b3;
                        }
                    </style>
                </head>
                <body>
                
                    <div class='container'>
                        <h1>Forgot Password - Reset OTP</h1>
                        <p>Dear User,</p>
                        <p>We received a request to reset your password. To proceed, please use the following One-Time Password (OTP):</p>
                        <p class='otp'><strong>$otp</strong></p>
                        <p class='note'>Please note that this OTP is valid for 10 minutes only.</p>
                        <a href='https://clubsatsrit.in/login?tp=forgot_pwd_otp' class='action-btn'>Reset Password</a>
                        <p>If you did not request a password reset, you can safely ignore this email.</p>
                        <p>Best regards,<br>The Clubs@SRIT Team</p>
                    </div>
                
                </body>
                </html>
                ";
    
                // Send email
                if (mail($to, $subject, $message, $headers)) {
                    return "Email sent successfully!.";
                } else {
                    return "Failed to send email.";
                }
            }
        }else{
            return "No user is registered with this mail id.";
        }
    }
    public function  validateOtp($token){
        $conn=$this->connect();
        $token=mysqli_real_escape_string($conn,$token);
        $uid=$_COOKIE['usid'];
        $uid=$this->sbldc($uid,$this->iky);
        $stmt = $conn->prepare("SELECT uid, user_name, token FROM users WHERE uid = ?");
        $stmt->bind_param("s", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows>0){
            $row=$result->fetch_assoc();
            $token_db=$this->dec($row['token'],$this->iky);
            if($token==$token_db){
                $verf=$this->enc("verfied",$this->iky,'idx');
                $stmt = $conn->prepare("UPDATE users SET token=? WHERE uid = ?");
                $stmt->bind_param("ss", $verf,$uid);
                $stmt->execute();

                return "verified";
            }else{
                return "not verified";
            }
        }else{
            return 0;
        }

    }
    public function  resetPwd($pwd){
        $conn=$this->connect();
        $pwd=mysqli_real_escape_string($conn,$pwd);
        $uid=$_COOKIE['usid'];
        $uid=$this->sbldc($uid,$this->iky);
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET pwd=? WHERE uid = ?");
        $stmt->bind_param("ss", $hashedPwd,$uid);
        $success=$stmt->execute();
        $stmt->close();

        return $success;
    }
    public function loginMail($mail,$name){
        $user=ucwords(explode(" ",$name)[0]);
        $to = $mail; // Email address of the recipient
        $subject = "Welcome To Clubs@SRIT"; // Subject of the email
        $headers = "From: Clubs@SRIT <no-reply@clubsatsrit.in>\r\n"; // Sender's email address
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $message="
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Welcome to Clubs@SRIT</title>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background-color: #f2f2f2;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
                }
                h1 {
                    color: #333333;
                    text-align: center;
                    font-weight: 700;
                    margin-bottom: 20px;
                }
                p {
                    color: #666666;
                    font-size: 16px;
                    line-height: 1.5;
                    margin-bottom: 10px;
                }
                .logo {
                    display: block;
                    margin: 0 auto;
                    max-width: 100%;
                }
                .signature {
                    color: #333333;
                    font-style: italic;
                }
            </style>
        </head>
        <body>
        
            <div class='container'>
                <h1>Welcome to Clubs@SRIT</h1>
                <img class='logo' src='https://thumbs.dreamstime.com/b/student-club-abstract-concept-vector-illustration-organization-university-interest-school-activity-program-college-238218896.jpg' alt='Clubs@SRIT Logo'>
                <p>Dear $user,</p>
                <p>Welcome to Clubs@SRIT!<br> We are thrilled to have you join our vibrant community.</p>
                <p>Explore our website to discover various clubs and exciting activities waiting for you.</p>
                <p>Best regards,</p>
                <p class='signature'>The Clubs@SRIT Team</p>
            </div>
        
        </body>
        </html>
        ";
        mail($to, $subject, $message, $headers);
    }
}


$user = new User();

if(isset($_POST['LoS']) && $_POST['LoS']=="l"){
if (isset($_POST['mail_id']) && isset($_POST['pwd'])) {
    $email = $_POST['mail_id'];
    $password = $_POST['pwd'];
     echo $user->login($email, $password);
} else {
    echo "Email and password are required.";
}
}

if(isset($_POST['LoS']) && $_POST['LoS']=="s"){
if (isset($_POST['user_name'], $_POST['mail_id'], $_POST['pwd'])) {
    $user_name = $_POST['user_name'];
    $mail_id = $_POST['mail_id'];
    $pwd = $_POST['pwd'];

    echo $user->register($user_name, $mail_id, $pwd);
} else {
    echo "All fields are required.";
}
}
if(isset($_POST['fog_pwd'],$_POST['mail_id']) && $_POST['fog_pwd']=="true"){
    echo $user->forgotPwd($_POST['mail_id']);
}

if(isset($_POST['fog_pwd_otp'],$_POST['otp']) && $_POST['fog_pwd_otp']=="true"){
    echo $user->validateOtp($_POST['otp']);
}
if(isset($_POST['reset_pwd_'],$_POST['pwd']) && $_POST['reset_pwd_']=="true"){
    echo $user->resetPwd($_POST['pwd']);
}