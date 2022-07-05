<?php
/*=============================================================================
|| ##################################################################
||	Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||
|| ##################################################################
|| FILE VERSION: 56.VII.2022 15:51
=============================================================================*/
defined('CREW') or die();

class Session{
	/*
CREATE TABLE `sessions` (
  `id` varchar(32) NOT NULL default '',
  `time` int(10) unsigned,
  `first_access` int(10) unsigned,
  `data` text,
  `userid` int(11) DEFAULT '0',
  `username` varchar(40) DEFAULT '',
  `params` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `userid` ( `userid` ),
  INDEX (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin
	*/
	public $id;
	public $time;
	public $first_access;
	public $userId;
	public $username;
	public $params;
	public $isNewSession;
	public $hasCanged;
	public $killSameUserSessions;
	protected static $session = null;

	public static function &getInstance(){
  		if (self::$session == null){
			self::$session = new Session();
		}
		return self::$session;
	}

	public function setFromDb($id, $val) {
		$this->isNewSession = $val == null;
		$this->id = $id;
		if (!$this->isNewSession){
			$this->time = $val->time;
			$this->first_access = $val->first_access;
			$this->userId = $val->userid;
			$this->username = $val->username;
			$this->params = $val->params;
		}
		else{
			$this->username = '';
			$this->userId = 0;
			$this->params = 0;
			$this->time = $this->first_access = time();
		}
	}

	public function setUserData($row){
		$_SESSION['id'] = $row->id;
		$_SESSION['name'] = $row->name;
		$_SESSION['username'] = $row->username;
		$_SESSION['gid'] = $row->gid;
		$_SESSION['block'] = $row->block;
		$_SESSION['lastvisitDate'] = $row->lastvisitDate;
		$_SESSION['params'] = $row->params;
		$_SESSION['updatesTimer'] = JHTML::date2Timestamp($row->lastvisitDate);
		$this->userId = $row->id;
		$this->username = $row->username;
		$this->params = $row->block;//TBD jos parametara
		$this->hasCanged = true;
		$this->killSameUserSessions = true;
	}

	public function setIfNotSet($param, $value){
		if (!isset($_SESSION[$param])){
			$this->set($param, $value);
		}
	}

	public function set($param, $value){
		if ($value == null){
			unset($_SESSION[$param]);
		}
		else{
			$_SESSION[$param] = $value;
		}
		$this->hasCanged = true;
	}

  	public function get($param, $def = null){
  		return isset($_SESSION[$param]) ? $_SESSION[$param] : $def;
  	}

	public function destroy() {
		session_destroy();
	}
}

class MySessionHandler implements SessionHandlerInterface{
	private $db;

    public function open($savePath, $sessionName) {
		$this->db = Database::getInstance();
        return true; // Database::getInstance() will fail if connection is not possible
    }

	public function close() {
        return true;
    }

    public function read($id) {
		$sql = 'SELECT * FROM #__sessions WHERE id='.$this->db->Quote($id);
		$this->db->setQuery( $sql );
		$val = $this->db->loadObject();
		if ( $this->db->isError() ){
			return false;
		}
	    $session = Session::getInstance();
		$session->setFromDb($id, $val);
		if (!$val) {
			return "";
		}
		return $val->data;
    }

    public function write($id, $data){
		$session = Session::getInstance();
		$time = time();
		if (!$session->isNewSession  && !$session->hasCanged){
			//$doc = &Document::getInstance();
			//$this->userId == 0 ||
			if ( $time - $session->time < MIN_TIME ){//  && $doc->getType() != 'html') ){
			 	return true;
			}
		}

		// kill all sessions already belong to user with $session->userId
		if ( $session->killSameUserSessions ) {
			$sql = 'DELETE FROM #__sessions WHERE userid='.$session->userId;
			$this->db->setQuery( $sql );
			$this->db->query();
		}

	  	$id = $this->db->Quote($id);
		$data = $this->db->Quote( $data );
		$username = $this->db->Quote( $session->username );
		$userId = $session->userId;
		if (!$session->isNewSession){
			$sql = 'UPDATE #__sessions SET time='.$time.',data='.$data.',username='.$username.',userid='.$userId.',params='.$session->params.' WHERE id='.$id;
		}
		else{
			$sql = 'INSERT INTO #__sessions VALUES ('.$id.','.$time.','.$time.','.$data.','.$userId.','.$username.','.$session->params.')';
		}
		$this->db->setQuery( $sql );
		$this->hasCanged = false;
		return $this->db->query();
    }

    public function destroy($id){
		$id = $this->db->Quote($id);
		$sql = 'DELETE FROM #__sessions WHERE id='.$id;
		$this->db->setQuery( $sql );
		return $this->db->query();
    }

    public function gc($maximumLifetime){
		$doc = &Document::getInstance();
		if ($doc->getType() != 'html'){//ne brisemo sesije za raw upit
		 	return true;
		}
		$old = time() - $maximumLifetime;
		//$sql = 'UPDATE #__users AS a SET a.lastvisitDate=(SELECT b.time FROM #__sessions AS b WHERE a.id=b.userid AND b.time < '.$old.')';
		//$this->db->setQuery( $sql );
		//$this->db->query();
		$sql = 'DELETE FROM #__sessions WHERE time<'.$old;
		$this->db->setQuery( $sql );
		return $this->db->query();
    }
}

function startSession() {
	// ini_set('session.save_handler', 'user');
	ini_set('session.use_trans_sid', '0');
	ini_set('session.use_only_cookies', '1');
	ini_set("session.gc_probability", 1);
	ini_set("session.gc_divisor", 1);
	ini_set("session.gc_maxlifetime", SESSION_TIME);
	$handler = new MySessionHandler();
	session_set_save_handler($handler, true);
	register_shutdown_function('session_write_close');
	session_start();
}