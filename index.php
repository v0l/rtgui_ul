<?php
include("php/security.php");

$execstart=$start=microtime(true);
include("php/util.php");


import_request_variables("gp","r_");

// Try using alternative XMLRPC library from http://sourceforge.net/projects/phpxmlrpc/  (see http://code.google.com/p/rtgui/issues/detail?id=19)
if(!function_exists('xml_parser_create')) {
   include("xmlrpc.inc");
   include("xmlrpc_extension_api.inc");
}

$globalstats = get_global_stats();
$rates = get_global_rates();

?> 
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<title>rtGui</title>
		<link rel="shortcut icon" href="favicon.ico" />
		<link href="css/style.css" rel="stylesheet" type="text/css" />
		<link href="css/fancybox.css" rel="stylesheet" type="text/css" />
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.fancybox.js"></script>
		<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
		<script type="text/javascript" src="js/noty/bottomLeft.js"></script>
		<script type="text/javascript" src="js/noty/default.js"></script>
		<script type="text/javascript" src="js/util.js"></script>
		<script type="text/javascript">
			$(document).ready(function() { 
				var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
				if(is_chrome){
					var n = noty({
					text	:'Auto-update has been disabled because some versions of Google Chrome experience massive performance issues',
					timeout	:5000,
					type	:'error',
					layout	:'bottomLeft'
					});
				}else{
					setInterval(updateList,5000);
				}
			});
		</script>
	</head>
	<body>
		<div id="page">
			<div id="header">
				<div class="left">
					<h1>rt<span class=green_logo>gui</span><span class="blue_logo">_ul</span><a class="fancy_show fancybox.iframe big_button" style="cursor:pointer;" href="ui/add_torrent.php">+</a></h1>
				</div>
				<div class="right">
					rTorrent <?php echo $globalstats['client_version']; ?> / libTorrent <?php echo $globalstats['library_version']; ?><br>
					<img src="icons/arrow_down.png" /><font id="ratedown"><?php echo getSize($rates[0]['ratedown']) . "/s"; ?></font> <img src="icons/arrow_up.png" /><font id="rateup"><?php echo getSize($rates[0]['rateup']) . "/s"; ?></font><br>
					<?php echo getSize($rates[0]['diskspace'])." / ".getSize(disk_total_space($downloaddir)); ?> <img src="icons/drive.png" /><br>
					<?php echo $USER->isAdmin() ? "<a href=\"ui/admin_page.php\" class=\"fancy_show_admin fancybox.iframe\">Manage <img src=\"icons/wrench.png\"/></a> | " : ""; ?>
					<a href="ui/account_page.php" class="fancy_show fancybox.iframe"><?php echo $USER->getName(); ?> <img src="icons/<?php echo $USER->isAdmin() ? "tux" : "user"; ?>.png" /></a>
				</div> 
			</div>
			<table class="torrents">
				<?php
					// LOAD ALL TORRENTS
					$T = new Torrents($USER);
					echo "<tr style=\"font-weight:bold;font-size:13px;\"><td></td><td>Name</td><td>Up</td><td>Down</td><td>Size</td><td>Upload</td><td>Download</td><td>Ratio</td></tr>";
					echo doView($T);
				?>
			</table>
			<div id="footer">
				<center>
					<?php echo $T->getStats(); ?>s<br>
					rt<span class=green_logo>gui</span><span class="blue_logo">_ul</span> / <a href="http://www.famfamfam.com/">FamFamFam</a> / <a href="http://code.google.com/p/rtgui/">rtgui</a><br>
					v0.1 by <a href="http://kharkin.ie/">Kieran Harkin</a>
				</center>
			</div>
		</div>
	</body>
</html>
