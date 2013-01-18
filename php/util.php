<?php
if(isset($_GET["cmd"])){
	require_once("security.php");
	
	switch($_GET["cmd"]){
		case 'reset_pw':{
			sendResetEmail($USER->getName(),$USER->getEmail(),$db->logRequest($USER->getID(),0));
			echo "<b>An email has been sent to " . $USER->getEmail() . ", please check your email to continue</b>";
			return;
		}case 'logout':{
			session_destroy();
			return;
		}case 'add_user':{
			sendRegistrationEmail($_POST["name"],$_POST["email"],$db->logRequest($db->insertUser($_POST["name"],'',$_POST["email"],$_POST["expires"],$_POST["isadmin"]),1));
			echo "<b>An email has been sent to " . $_POST["email"] . "</b>";
			return;
		}case 'del':{
			$hash = $_GET["hash"];
			$response = do_xmlrpc(xmlrpc_encode_request("d.erase",array("$hash")));
			$db->deleteTorrent($hash);
			echo json_encode(array("msg" => $response ? "Error" : "Torrent has been deleted","extra","extra" => array(array('type' => '1','change' => ".row_$hash"))));
			return;
		}case 'stop':{
			$hash = $_GET["hash"];
			$response = do_xmlrpc(xmlrpc_encode_request("d.stop",array("$hash")));
			$response = do_xmlrpc(xmlrpc_encode_request("d.close",array("$hash")));
			echo json_encode(array("msg" => $response ? "Error" : "Torrent stopped","extra" => array(array('type' => '0','change' => "#control_$hash",'attr' => 'src','value' => 'icons/control_play.png'),array('type' => '0','change' => "#control_" . $hash . "_href",'attr' => 'href','value' => "php/util.php?cmd=start&hash=$hash"))));
			return;
		}case 'start':{
			$hash = $_GET["hash"];
			$response = do_xmlrpc(xmlrpc_encode_request("d.open",array("$hash")));
			$response = do_xmlrpc(xmlrpc_encode_request("d.start",array("$hash")));
			echo json_encode(array("msg" => $response ? "Error" : "Torrent started","extra" => array(array('type' => '0','change' => "#control_$hash",'attr' => 'src','value' => 'icons/control_pause.png'),array('type' => '0','change' => "#control_".$hash."_href",'attr' => 'href','value' => "php/util.php?cmd=stop&hash=$hash"))));
			return;
		}case 'load_link':{
			$url = $_GET["href"];
			$response = do_xmlrpc(xmlrpc_encode_request("d.multicall",array("main","load_start=$url","d.set_custom1=$url")));
			var_dump($response);
			
			if($hash){
				$db->insertTorrent(getByCustom($url),$USER->getID());
			}
			return;
		}
	}
}

function getSize($bytes){
	$lvl = array('B','KB','MB','GB','TB');
	$v = $bytes;
	
	$x = 0;
	while($v > 1024){
		$x++;
		$v /= 1024;
	}
	return round($v,2) . ' ' . $lvl[$x];
}
function sendResetEmail($name,$email,$token){
	global $_EMAIL,$_URL;
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'To: ' . $name . ' <' . $email . '>' . "\r\n";
	$headers .= 'From: rtGui <' . $_EMAIL . '>' . "\r\n";

	$msg = "<html>
		<body style=\"margin: 20px;font-family: century gothic, sans-serif;\">
			Hi " . $name . "
			<p>
				You have requested a password change for our site, if you did not request a password change please respond to this email stating that.
			</p>
			<p>
				To change your password please click the following link and follow the instructions on screen<br>
				<a href=\"" . $_URL . "/mod.php?t=" . $token . "\">Link to reset your password</a>
			</p>
		</body>
	</html>";
	mail($email,"Password reset",$msg,$headers);
}
function sendRegistrationEmail($name,$email,$token){
	global $_EMAIL,$_URL,$_DESC;
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'To: ' . $name . ' <' . $email . '>' . "\r\n";
	$headers .= 'From: rtGui <' . $_EMAIL . '>' . "\r\n";

	$msg = "<html>
		<body style=\"margin: 20px;font-family: century gothic, sans-serif;\">
			Hi " . $name . "
			<p>
				You have been invited to join " . $_DESC  . "
			</p>
			<p>
				To Finish the creation of your account please click the following link<br>
				<a href=\"" . $_URL . "/mod.php?t=" . $token . "\">Link to create your account</a>
			</p>
		</body>
	</html>";
	mail($email,"Welcome to " . $_DESC,$msg,$headers);
}
function doView($T){
	global $USER;
	$data = "";
	foreach($T->getTorrents() as $t){
		//Draw precentage bar
		$p = $t->getVar("percent_complete");
		if($p==0){
			$col = "red";
			$p = 100;
		}elseif($p < 100){
			$col = "blue";
		}else{
			$col = "green";
		}
		$data .= "\n<tr class=\"row_" . $t->getVar("hash") . "\" ><td colspan=8><div class=\"border_bottom\" style=\"width:100%;\"><div class=\"" . $col . "\" id=\"" . $t->getVar("hash") . "_percent_complete\" style=\"width:" . $p . "%;height:2px;\"></div></div></td></tr>"
		. "\n<tr class=\"row_" . $t->getVar("hash") . "\" >"
		. "<td style=\"width:50px;\"><a id=\"control_" . $t->getVar("hash") . "_href\" class=\"doAjax\" href=\"php/util.php?cmd=" . ($t->getVar("is_active")? "stop" : "start") . "&hash=" . $t->getVar("hash") . "\"><img id=\"control_" . $t->getVar("hash") . "\" src=\"icons/control_" . ($t->getVar("is_active")? "pause" : "play") . ".png\"/></a><a class=\"doAjax\" name=\"remove_" . $t->getVar("hash") . "\" href=\"php/util.php?cmd=del&hash=" . $t->getVar("hash") . "\" ><img src=\"icons/delete.png\"/></a></td>"
		.  "<td><a class=\"fancy_show_admin fancybox.iframe\" href=\"php/dir.php?base=" . ($USER->isAdmin()? '' : $t->getOwner()) . "\">" . (strlen($t->getVar("name"))>85 ? substr($t->getVar("name"),0,80).'...' : $t->getVar("name")) . "</a></td><font style=\"text-align:right;\">"
		.  "<td id=\"" . $t->getVar("hash") . "_up_rate\" style=\"width:80px;\">" . getSize($t->getVar("up_rate")) . "/s</td>"
		.  "<td id=\"" . $t->getVar("hash") . "_down_rate\" style=\"width:80px;\">" . getSize($t->getVar("down_rate")) . "/s</td>"
		.  "<td style=\"width:80px;\">" . getSize($t->getVar("size_bytes")) . "</td>"
		.  "<td id=\"" . $t->getVar("hash") . "_up_total\" style=\"width:80px;\">" . getSize($t->getVar("up_total")) . "</td>"
		.  "<td id=\"" . $t->getVar("hash") . "_completed_bytes\" style=\"width:80px;\">" . getSize($t->getVar("completed_bytes")) . "</td>"
		.  "<td id=\"" . $t->getVar("hash") . "_ratio\">" . $t->getVar("ratio")/1000 . "</td>"
		.  "</font></tr>";
	}
	return $data;
}
?>