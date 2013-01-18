<?php
//	Login gateway for the site

if(isset($_POST["uname"]) && isset($_POST["pw"])){
	//Check the login
	require("php/DB.php");
	$db = DB::inst();
	
	if($db->checkLogin($_POST["uname"],$_POST["pw"])){
		session_start();
		$_SESSION["log_in"] = sha1($_POST["uname"]);
		
		//Clear old sessions first
		$db->deleteSession();
		$db->insertSession($_POST["uname"]);
		header("location:index.php");
	}else{
		$wrong = true;
	}
}
?>
<html>
	<head>
		<title>rtGui Login</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
	</head>
	
	<body style="background:#EEE;">
		<div id="login">
			<center>
				<h1>rt<span class=green_logo>gui</span><span class="blue_logo">_ul</span></h1><br>
				<?php echo isset($wrong) ? "<font style=\"color:#F00;font-size:12px;\">Invalid Login</font>" : ""; ?>
				<form action="login.php" method="POST">
					Username:<br>
					<input type="text" name="uname" value="<?php echo isset($_POST["uname"]) ? $_POST["uname"] : ""; ?>"/><br>
					Password:<br>
					<input type="password" name="pw" /><br>
					<br><input type="submit" value="Login" />
				</form>
			</center>
		</div>
	</body>
</html>