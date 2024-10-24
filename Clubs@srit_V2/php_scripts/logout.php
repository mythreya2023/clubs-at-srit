<?php
session_start();
$_SESSION = array();
setcookie('_uid_',$ugdae,time()-8600,'/');
setcookie('_us_tp_',$urdi,time()-8600,'/');
session_destroy();
  header('Location: ../home'); 