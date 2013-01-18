<?php
include("../php/security.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../css/style.css"/>
		<link rel="stylesheet" type="text/css" href="../css/ui.css"/>
	</head>
	<body>
		<?php
			if(!$USER->isAdmin()){
				echo "<h1>You must be an admin to access this page</h1>";
				return;
			}
		?>
		<h1>Add user</h1>
		<form method="POST" action="../php/util.php?cmd=add_user">
			<table>
			<tr><td>Name:</td><td><input type="text" name="name"/></td></tr>
			<tr><td>Email:</td><td><input type="text" name="email"/></td></tr>
			<tr><td>Administrator:</td><td><input type="checkbox" name="isadmin" value="0"/></td></tr>
			<tr><td>Expires:</td><td><input type="date" name="expires"/></td></tr>
			<tr><td><input type="submit" value="Send invite" class="big_button"/></td></tr>
			</table>
		</form>
	</bodY>
</html>