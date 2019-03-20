<?php
   require_once "kobliDB.php";
   $kDB = new kobliDB("mysql","localhost","80","blogsitem","root","");
   session_start();
   
   $user_check = $_SESSION['login_user'];
   
   $ses_sql = $kDB->valueCont("users",array("usrpr01"=>$user_check));
   
   $login_session = "";
   if(!isset($_SESSION['login_user']) || !$ses_sql){
      header("location:login.php");
      die();
   }else{
      $login_session = $user_check;
   }
?>