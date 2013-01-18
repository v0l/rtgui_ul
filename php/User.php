<?php

class User {
	private $id;
	private $name;
	private $uname;
	private $email;
	private $is_admin;
	private $due_date;
	private $status;
	private $tcount;
	private $dataSize;
	private $torrents;
	
	public function __construct($data,$ex=false){
		//name,u_name,email,is_admin,due_date,status
		$this->id = $data[0];
		$this->name = $data[1];
		$this->uname = $data[2];
		$this->email = $data[3];
		$this->is_admin = $data[4];
		$this->due_date = $data[5];
		$this->status = $data[6];
		
		//Load extra fields for admin page
		if($ex){
			$this->tcount = $data[7];
		}
	}
	public function getID(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getUserName(){
		return $this->uname;
	}
	public function getEmail(){
		return $this->email;
	}
	public function isAdmin(){
		return $this->is_admin;
	}
	public function getExpiry(){
		return $this->due_date;
	}
	public function getStatus(){
		return $this->status;
	}
	public function getTorrentCount(){
		return $this->tcount;
	}
	public function getDataSize(){
		return $this->dataSize;
	}
	public function setTorrents($data){
		$this->torrents = $data;
	}
	public function ownsTorrent($hash){
		return in_array($hash,$this->torrents);
	}
}
?>