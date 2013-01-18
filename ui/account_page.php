<?php
include("../php/security.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Acount Information</title>
		<link rel="stylesheet" type="text/css" href="../css/style.css"/>
		<link rel="stylesheet" type="text/css" href="../css/ui.css"/>
	</head>
	<body>
		<h1>Account Information</h1>
		<table class="details" >
			<thead>
				<tr></tr>
			</thead>
			<tbody>
				<tr><td class="r"><b>Name:</b></td><td class="l"><?php echo $USER->getName(); ?></td></tr>
				<tr><td class="r"><b>Username:</b></td><td class="l"><?php echo $USER->getUserName(); ?></td></tr>
				<tr><td class="r"><b>Email:</b></td><td class="l"><?php echo $USER->getEmail(); ?></td></tr>
				<tr><td class="r"><b>Account expires:</b></td><td class="l"><?php echo date('M jS Y',strtotime($USER->getExpiry())); ?></td></tr>
				<tr><td class="r"><b>Account status:</b></td><td class="l"><?php 
				switch($USER->getStatus()){
					case 0:{
						echo "OK";
						break;
					}case 1:{
						echo "Expirerd";
						break;
					}case 2:{
						echo "Disabeled";
						break;
					}default:
						echo "Unknown";
				}
				?></td></tr>
			</tbody>
		</table><br>
		<a class="big_button" href="../php/util.php?cmd=reset_pw">Reset Password</a>
		<a class="big_button" href="../php/util.php?cmd=logout" onClick="window.location = 'index.php';">Logout</a>
	</bodY>
</html>