<?php
if(isset($_REQUEST["t"])){ //token was sent, hadle this request
	require("php/User.php");
	require("php/DB.php");
	$db = DB::inst();
	$t = $db->getModRequest($_REQUEST["t"]);
	if($t[1] != NULL){
		$user = $t[0];
		$type = $t[1];
	}else{
		echo "Unknown or expirerd token, please contact the administrator";
		return;
	}
}else{
	header("location:index.php");
}
//Conditions cleared start display of data
?>
<!DOCTYPE html>
<html>
	<head>
		<title>User modification request</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
	</head>
	<body>
		<?php
			switch($type){
				case 0:{ //User requested a password change
					//Check is this the submitted page
					if(isset($_POST["pw"])){
						$db->removeRequest($_POST["t"]);
						$db->resetPasswd($user->getID(),$_POST["pw"]);
						session_start();
						session_destroy();
						header("location:index.php");
					}
					echo "<div id=\"login\">";
					echo "<h1>Password reset</h1><br>";
					echo "<form action=\"mod.php\" method=\"POST\">";
					echo "<input type=\"hidden\" value=\"" . $_REQUEST["t"] . "\" name=\"t\"/>";
					echo "Username:<input type=\"text\" disabled value=\"" . $user->getUserName() . "\"/><br>";
					echo "Password:<input type=\"password\" name=\"pw\"/><br>";
					echo "<input class=\"big_button\" type=\"submit\" value=\"Reset Password\"/><br><br>";
					echo "</div>";
					break;
				}case 1:{
					//Check is this the submitted page
					if(isset($_POST["pw"])){
						$db->removeRequest($_POST["t"]);
						$db->resetPasswd($user->getID(),$_POST["pw"],$_POST["uname"]);
						session_start();
						session_destroy();
						header("location:index.php");
					}
					echo "<div id=\"login\" style=\"width:230px;\">";
					echo "<h1>Acount Creation</h1><br><table>";
					echo "<form action=\"mod.php\" method=\"POST\">";
					echo "<input type=\"hidden\" value=\"" . $_REQUEST["t"] . "\" name=\"t\"/>";
					echo "<tr><td>Name:</td><td><input type=\"text\" disabled value=\"" . $user->getName() . "\"/></td></tr>";
					echo "<tr><td>Email:</td><td><input type=\"text\" disabled value=\"" . $user->getEmail() . "\"/></td></td></tr>";
					echo "<tr><td>Username:</td><td><input type=\"text\" name=\"uname\"/></td></tr>";
					echo "<tr><td>Password:</td><td><input type=\"password\" name=\"pw\"/></td></tr>";
					echo "<tr><td colspan=\"2\"><input class=\"big_button\" type=\"submit\" value=\"Create Account\"/></td></tr>";
					echo "</table></div>";
					break;
				}
			}
		?>
	</body>
</html>