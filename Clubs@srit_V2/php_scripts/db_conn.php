<?php
include 'comn_funcs.php';
$conn=mysqli_connect("localhost","u667736305_sritclubs","Sritclubs.123","u667736305_sritclubs");
class dbconnect extends comn_funcs{
    private $host; private $dbuname;
    private $dbpwd; private $dbname;
    protected function connect (){
       $oopconn=new mysqli( $this->host="localhost",
       $this->dbuname="u667736305_sritclubs",$this->dbpwd="Sritclubs.123",
       $this->dbname="u667736305_sritclubs");
    
        if(isset($_COOKIE['_uid_'],$_COOKIE['_us_tp_'])){
            $_SESSION['ses_id']=htmlentities($_COOKIE['_uid_']);
            $_SESSION['us_tp'] =htmlentities($_COOKIE['_us_tp_']);
        }
        return $oopconn;
    }
}

?>