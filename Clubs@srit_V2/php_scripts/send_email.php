<?php
session_start();
include 'db_conn.php';
class send_email extends dbconnect{
    public function fetch_queue(){
        $conn=$this->connect();
        $stmt=$conn->prepare("SELECT e.id, e.recipient, e.subject, e.message FROM  email_queue e ;");
        if(!$res=$stmt->execute()){return 0;}
        $result=$stmt->get_result();
        if ($result->num_rows > 0) {
            $mail_log=$result->fetch_all();
            foreach($mail_log as $mail){
                $log_id = $mail[0];
                $recipient = $this->sbldc($mail[1],$this->iky);
                $subject = $this->dec($mail[2],$this->iky);
                $message = $this->dec($mail[3],$this->iky);
                $message = str_replace("\"","",$message);
                $message = str_replace("\\","",$message);
                $body = str_replace("\'","'",$message);

                $this->mailing($log_id,$subject, $body, $recipient);
            }
        }
    }
    private function mailing($id,$subject_Line, $body, $to_email){
        $to = $to_email; // Email address of the recipient
        $subject = $subject_Line; // Subject of the email
        $headers = "From: Clubs@SRIT <no-reply@clubsatsrit.in>\r\n"; // Sender's email address
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    
        $message="<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>$subject_Line</title>
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
                img {
                    display: block;
                    margin: 0 auto;
                    margin-top: 20px;
                    max-width: 100%;
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
                $body
                <p>Best regards,<br>The Clubs@SRIT Team.</p>
            </div>
        
        </body>
        </html>
        ";
    
        // Send email
        if(mail($to, $subject, $message, $headers)){
            $conn=$this->connect();
            $stmt=$conn->prepare("DELETE FROM email_queue WHERE id = ?");
            $stmt->bind_param('s',$id);
            $stmt->execute();
        }
    }
}

$send_email = new send_email();
$send_email->fetch_queue();