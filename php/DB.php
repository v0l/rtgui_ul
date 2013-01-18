<?php
require_once("cfg.php");

class DB {
	private $mysqli=0;
	static $instance = 0;
	
	public static function inst(){
		if (DB::$instance == 0){
			DB::$instance = new DB();
		}
		return DB::$instance;
	}
	public function con(){
		if(!$this->mysqli){
			$this->mysqli = new mysqli('localhost', 'root', 'root', 'rtorrent');
		}
		
		if ($this->mysqli->connect_errno) {
			printf("Connect failed: %s\n", $this->mysqli->connect_error);
			exit();
		}
		
		return $this->mysqli;
	}
	public function checkLogin($user,$pass){
		$this->mysqli = $this->con();

		$res = $this->mysqli->query("SELECT passwd FROM users WHERE u_name = '" . $user . "'");
		if($res){ 
			$row = $res->fetch_row();
			if ($row[0] == sha1($pass)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function logRequest($id,$type){
		$this->mysqli = $this->con();
		$t = sha1(time());
		$this->mysqli->query("INSERT INTO mod_request VALUES(NULL,'" . $id . "','" . $type . "','" . $t . "')");
		return $t;
	}
	
	public function removeRequest($t){
		$this->mysqli = $this->con();
		$this->mysqli->query("DELETE FROM mod_request WHERE token = '" . $t . "'");
	}
	
	public function resetPasswd($id,$pw,$uname=""){
		$this->mysqli = $this->con();
		if($uname=="")
			$q = "UPDATE users SET passwd = '" . sha1($pw) . "' WHERE id = '" . $id . "'";
		else
			$q = "UPDATE users SET passwd = '" . sha1($pw) . "', u_name = '" . $uname . "' WHERE id = '" . $id . "'";
		$this->mysqli->query($q);
	}
	
	public function getModRequest($token){
		$this->mysqli = $this->con();
		$res = $this->mysqli->query("SELECT users.id,mod_request.type FROM users,mod_request WHERE mod_request.token = '" . $token . "' AND mod_request.user_id = users.id");
		if($res){ 
			$row = $res->fetch_row();
			return array($this->loadUser($row[0]),$row[1]);
		}else{
			return 0;
		}
	}
	
	public function getSessions(){
		$this->mysqli = $this->con();
		$data = array();
		
		$res = $this->mysqli->query("SELECT user,time FROM session ORDER BY time DESC");
		if($res){ 
			while($row = $res->fetch_row()){
				$data[] = $row;
			}
			return $data;
		}else{
			return 0;
		}
	}
	public function getTorrentByUser($id){ 
		$this->mysqli = $this->con();
		$data = array();
		
		$res = $this->mysqli->query("SELECT hash FROM torrents WHERE user_id = '" . $id . "'");
		if($res){ 
			while($row = $res->fetch_row()){
				$data[] = $row[0];
			}
			return $data;
		}else{
			return 0;
		}
	}
	public function loadUser($id){
		$this->mysqli = $this->con();

		$res = $this->mysqli->query("SELECT id,name,u_name,email,is_admin,due_date,status FROM users WHERE id = '" . $id . "'");
		if($res){ 
			return new User($res->fetch_row());
		}else{
			return -1;
		}
	}
	public function getUserID($name){
		$this->mysqli = $this->con();

		$res = $this->mysqli->query("SELECT id FROM users WHERE u_name = '" . $name . "'");
		if($res){ 
			$row = $res->fetch_row();
			return $row[0];
		}else{
			return -1;
		}
	}
	public function loadUsers(){
		$this->mysqli = $this->con();
		$data = array();
		$res = $this->mysqli->query("SELECT `users`.`id`, `users`.`name`, `users`.`u_name`, `users`.`email`, `users`.`is_admin`, `users`.`due_date`, `users`.`status`, COUNT(`torrents`.`id`) AS `torrent_count` FROM `users` LEFT JOIN `torrents` ON `users`.`id` = `torrents`.`user_id` GROUP BY `users`.`id`");
		if($res){ 
			while($row = $res->fetch_row())
				$data[] =  new User($row,true);
		}else{
			return -1;
		}
		return $data;
	}
	public function checkSession(){
		global $EXPIRE;
		$this->mysqli = $this->con();

		$res = $this->mysqli->query("SELECT user,time FROM session WHERE session_id = '" . $_SESSION["log_in"] . "'");
		if($res){ 
			$row = $res->fetch_row();
			if(time() - strtotime($row[1]) > $EXPIRE ){
				return -1;
			}else{
				return $row[0];
			}
		}else{
			return -1;
		}
	}
	
	public function insertSession($id){
		$this->mysqli = $this->con();

		$res = $this->mysqli->query("INSERT INTO session VALUES(NULL,'" . $this->getUserID($id) . "',NOW(),'" . $_SESSION["log_in"] . "')");
		return $res;
	}
	public function insertTorrent($hash,$id){
		$this->mysqli = $this->con();

		$res = $this->mysqli->query("INSERT INTO torrents VALUES(NULL,'" . $id . "','" . $hash . "','')");
		return $res;
	}
	public function insertUser($name,$uname,$email,$expire,$isadmin=0){
		$this->mysqli = $this->con();
		$this->mysqli->query("INSERT INTO users VALUES(NULL,'" . $name . "','" . $uname . "','','" . $email . "','" . $isadmin . "','" . $expire . "','0')");
		return $this->mysqli->insert_id;
	}
	public function deleteSession(){
		$this->mysqli = $this->con();
		$this->mysqli->query("DELETE FROM session WHERE session_id = '" . $_SESSION["log_in"] . "'");
	}
	public function deleteTorrent($hash){
		$this->mysqli = $this->con();
		$this->mysqli->query("DELETE FROM torrents WHERE hash = '" . $hash . "'");
	}
	public function updateSession($name){
		$this->mysqli = $this->con();

		$res = $this->mysqli->query("UPDATE session SET time=NOW() WHERE user = '" . $name . "'");
		return $res;
	}
}
?>