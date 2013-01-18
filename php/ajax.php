<?php
//Ajax responder, respons in bulk json responses
include("security.php");
include("util.php");
include("config.php");
include("functions.php");
import_request_variables("gp","r_");

$DATA = array();
$rates = get_global_rates();
$T = new Torrents($USER);
foreach($T->getTorrents() as $torrent){
	if(isset($_GET["q"])){
		if($_GET["q"] == 'min'){
			$tdat = $torrent->getData();
			$tdat = array('up_rate' => $tdat['up_rate'],'down_rate' => $tdat['down_rate'],'up_total' => $tdat['up_total'],'completed_bytes' => $tdat['completed_bytes'],'ratio' => $tdat['ratio'],'percent_complete' => $tdat['percent_complete']);
		}
	}else{
		$tdat = $torrent->getData();
	}
	$DATA[$torrent->getVar('hash')] = $tdat;
}
$DATA['GLOBAL'] = $rates[0];
if(isset($_GET["do_view"])){
	if(isset($_GET["q"])){
		if($_GET["q"] == "min"){
			$DATA["error"] = "You cannot request a view with update data!";
		}
	}
	
	//Make the view
	$json = doView($T);
}else{
	$json = json_encode($DATA);
}
if(count($json)<1){
	echo "NO DATA FOUND";
}else{
	echo $json;
}
?>