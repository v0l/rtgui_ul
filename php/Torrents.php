<?php
// Torrent manager
include("functions.php");

class Torrents {
	private $data = array();
	private $load_time = 0;
	
	public function __construct($user){
		$start = microtime(true);
		$data = get_full_list('main');
		foreach($data as $torrent){
			if($user->ownsTorrent($torrent['hash']) || ($user->isAdmin() && isset($_SESSION["show_all"]))){
				$this->data[] = new Torrent($torrent,$user->getUserName());
			}
		}
		$this->load_time = microtime(true) - $start;
	}
	
	public function getTorrents(){
		return $this->data;
	}
	public function getStats(){
		return "Load time: " . $this->load_time;
	}
}
?>