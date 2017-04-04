<?php
/*=============================================================================
|| ##################################################################
||	Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||
|| ##################################################################
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
	protected $db;
	public $id;
	public $time;
	public $first_access;
	public $data;
	public $userId;
	public $username;
	public $params;
	public $newSession;
	public $hasCanged;
		
	public function __construct(){
  	ini_set('session.save_handler', 'user');
		ini_set("session.gc_probability",1);
		ini_set("session.gc_divisor",1);
		//ini_set("session.gc_maxlifetime",SESSION_TIME);    
		$this->db = Database::getInstance();
		session_set_save_handler(
												 array(&$this, 'open'),
                         array(&$this, 'close'),
                         array(&$this, 'read'),
                         array(&$this, 'write'),
                         array(&$this, 'destroy'),
                         array(&$this, 'gc')
                         );		 
    //resava probleme sa PHP version >= 5
		register_shutdown_function('session_write_close');
		session_start();  
	/*	if (REGENERATE){
			session_regenerate_id();
		} */
		$this->hasCanged = false;
	}
	
	protected static $session = null;
	
	public static function &getInstance(){
  	if (self::$session == null){
			self::$session = new Session();
		}
		return self::$session;
	}
	
	public function open(){	 	 
		return true; 
	}
  
  public function close(){
  	return true;
	}

 	public function read($id) {
    $sql = 'SELECT * FROM #__sessions WHERE id='.$this->db->Quote($id);    
    $this->db->setQuery( $sql );
    $val = $this->db->loadObject();
    if ( $this->db->isError() ){
    	return false;
    }
    $this->newSession = $val == null;
    if (!$this->newSession){
    	$this->id = $val->id;
    	$this->time = $val->time;
    	$this->first_access = $val->first_access;
    	$this->data = unserialize( $val->data );
    	$this->userId = $val->userid;
    	$this->username = $val->username;
    	$this->params = $val->params;
    }
    else{
    	$this->id = $id;
    	$this->username = '';
    	$this->userId = $this->params = 0;
    	$this->data = new User();
    	$this->data->ip = $_SERVER['REMOTE_ADDR'];
    	$this->time = $this->first_access = time();
    }
 		return $val;
  }	
  
  public function &getData(){
  	 return $this->data;
  }
    
  public function setData($row){
  	$this->data->id = $row->id;
  	$this->data->name = $row->name;
  	$this->data->username = $row->username;
  	$this->data->gid = $row->gid;
  	$this->data->block = $row->block;
  	$this->data->lastvisitDate = $row->lastvisitDate;
  	$this->data->params = $row->params;
  	$this->userId = $row->id;
  	$this->username = $row->username;
  	$this->params = $row->block;//TBD jos parametara
  	$this->hasCanged = true;
  }
  
  public function set($param,$value){
  	if ($value == null){
  		unset( $this->data->$param );
    } 
    else{
  		$this->data->$param = $value;
  	}
  	$this->hasCanged = true;
  }
  
  public function get($param,$def = null){
  	
  	return isset($this->data->$param) ? $this->data->$param : $def;
  }
  
	public function write($id, $data){
		$time = time();
		if (!$this->newSession  &&  !$this->hasCanged){
			//$doc = &Document::getInstance();
			//$this->userId == 0 || 
			if ( $time-$this->time < MIN_TIME ){//  && $doc->getType() != 'html') ){
			 	return true;
			}
		}
	  $id = $this->db->Quote($id);	  
		$data = $this->db->Quote( serialize($this->data) );
		$username = $this->db->Quote( $this->username );
		$userId = $this->userId;		
		if (!$this->newSession){			
			$sql = 'UPDATE #__sessions SET time='.$time.',data='.$data.',username='.$username.',userid='.$userId.',params='.$this->params.' WHERE id='.$id;
		}
		else{
			$sql = 'INSERT INTO #__sessions VALUES ('.$id.','.$time.','.$time.','.$data.','.$userId.','.$username.','.$this->params.')';
		}		
		$this->db->setQuery( $sql );
		$this->hasCanged = false;
		return $this->db->query();
	}  
	
	public function destroy($id) {
		 $id = $this->db->Quote($id);
     $sql = 'DELETE FROM #__sessions WHERE id='.$id;
     $this->db->setQuery( $sql );
     return $this->db->query();
  }

	public function destroyAllUser($id) {
		 $this->hasCanged = true;
     $sql = 'DELETE FROM #__sessions WHERE userid='.$id;
     $this->db->setQuery( $sql );
     return $this->db->query();
  }
  
  public function gc($max) {
 	  $old = time() - SESSION_TIME;
		$doc = &Document::getInstance();
		if ($doc->getType() != 'html'){//ne brisemo sesije za raw upit
		 	return true;
		} 	  
 	  //$sql = 'UPDATE #__users AS a SET a.lastvisitDate=(SELECT b.time FROM #__sessions AS b WHERE a.id=b.userid AND b.time < '.$old.')';
 	  //$this->db->setQuery( $sql );
 	  //$this->db->query(); 
 		$sql = 'DELETE FROM #__sessions WHERE time<'.$old;
		$this->db->setQuery( $sql ); 		
    return $this->db->query();
  }
  	
}
?>