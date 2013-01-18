<?php
include("security.php");

$upload_dir = $_INSTALL . "tmp/";
$allowed_ext = array('torrent');

if(array_key_exists('torrents',$_FILES) && $_FILES['torrents']['error'] == 0 ){
	
	$torrent = $_FILES['torrents'];

	if(!in_array(get_extension($torrent['name']),$allowed_ext)){
		exit_status('Invaild file type for ' + $torrent['name']);
	}
	
	if(move_uploaded_file($torrent['tmp_name'], $upload_dir . $torrent['name'])){
		$response = do_xmlrpc(xmlrpc_encode_request("load_start",array($upload_dir . $torrent['name'])));
		$hash = getHashTiedTo($torrent['name']);
		if($hash)
			$db->insertTorrent($hash,$USER->getID());
		exit_status('1');
	}else{
		exit_status("Upload failed for file: " + $torrent['name']); 
	}
	
}
exit_status('Unknown error');

function exit_status($str){
	echo json_encode(array('status'=>$str));
	exit;
}

function get_extension($file_name){
	$ext = explode('.', $file_name);
	$ext = array_pop($ext);
	return strtolower($ext);
}
?>