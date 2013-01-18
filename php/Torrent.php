<?php
/*
 * 	Torrent class
 */
$_TITLE = "Login";

class Torrent {
	private $data = array();
	private $owner = -1;
	
	/* Rows of data in $data	
	'base_filename'
    'base_path'
    'bytes_done'
    'chunk_size
    'chunks_hashed
    'complete'
    'completed_bytes' 
    'completed_chunks'
    'connection_current'
    'connection_leech'
    'connection_seed'
    'creation_date'
    'directory' 
    'down_rate' 
    'down_total'
    'free_diskspace' 
    'hash' 
    'hashing'
    'ignore_commands'
    'left_bytes'
    'local_id'
    'local_id_html'
    'max_file_size'
    'message'
    'peers_min'
    'name' 
    'peer_exchange'
    'peers_accounted' 
    'peers_complete' 
    'peers_connected'
    'peers_max' 
    'peers_not_connected' 
    'priority' 
    'priority_str' 
    'ratio' 
    'size_bytes' 
    'size_chunks' 
    'size_files' 
    'skip_rate' 
    'skip_total' 
    'state' 
    'state_changed' 
    'tied_to_file'
    'tracker_focus'
    'tracker_numwant
    'tracker_size'
    'up_rate' 
    'up_total'
    'uploads_max'
    'is_active'
    'is_hash_checked'
    'is_hash_checking
    'is_multi_file' 
    'is_open' 
    'is_private' 
    'percent_complete'
    'bytes_diff'
    'status_string'
    'filemtime'
    'tracker_url'
	*/
	
	public function __construct($data,$owner){
		$this->data = $data;
		$this->owner = $owner;
	}
	
	public function getVar($str){
		return $this->data[$str];
	}
	public function getData(){
		return $this->data;
	}
	public function getOwner(){
		return $this->owner;
	}
}