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
		<h1>Admin Page <a class="med_button" href="add_user.php">+</a></h1>
		<table style="width:890px;">
				<tr style="font-weight:bold;"><td>ID</td><td>Username</td><td>Name</td><td>Email</td><td>Expires</td><td>Torrents</td><td>Total Size</td><td>Control</td></tr>
				<?php
					$d = $db->loadUsers();
					$odd = 0;
					
					foreach($d as $u){
						if($odd)
							$col = " style=\"background-color:#F0FAFF;\"";
						else
							$col = "";
						echo "<tr>" . 
							"<td" . $col . ">" . $u->getID() . "</td>" . 
							"<td" . $col . ">" . $u->getUserName() . "</td>" . 
							"<td" . $col . ">" . $u->getName() . "</td>" . 
							"<td" . $col . ">" . $u->getEmail() . "</td>" . 
							"<td" . $col . ">" . date('d/m/Y',strtotime($u->getExpiry())) . "</td>" . 
							"<td" . $col . ">" . $u->getTorrentCount() . "</td>" . 
							"<td" . $col . ">" . $u->getDataSize() . "</td>" . 
							"<td" . $col . "></td></tr>";
						$odd = ($odd==1 ? 0 : 1);
					}
				?>
		</table><br>
	</bodY>
</html>