<?php
   require_once "kobliDB.php";
   $kDB = new kobliDB("mysql","localhost","80","blogsitem","root","");
   
   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = htmlspecialchars($_POST['kAdi']);
      $mypassword = htmlspecialchars($_POST['kSifre']); 
      
      $returnValue = $kDB->valueCont("users",array("usrpr01"=>$myusername,"usrpr02"=>$mypassword));
		
      if($returnValue) {
         $_SESSION['login_user'] = $myusername;
         header("location: welcome.php");
      }else {
         $error = "Your Login Name or Password is invalid";
      }
   }
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="kutu">
		<h2>Giriş</h2>
		<form action="" method="POST" autocomplete="off">

			<div class="girisBox">
				<input type="text" autocomplete="new-name" name="kAdi" required="">
				<label>Kullanıcı Adı</label>
			</div>

			<div class="girisBox">
				<input type="password" autocomplete="new-password" name="kSifre" required="">
				<label>Şifre</label>
			</div>

			<input type="submit" name="" value="Gönder">

		</form>
	</div>
</body>
</html>