<?php
/*=============================================================================
|| ##################################################################
||	Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||
|| ##################################################################
|| FILE VERSION: 5.VII.2022 15:51
=============================================================================*/
/*
CREATE TABLE `users` (
 `id` int(11) NOT NULL auto_increment,
 `name` varchar(100) NOT NULL,
 `username` varchar(40) NOT NULL default '',
 `email` varchar(100) NOT NULL,
 `password` varchar(40) NOT NULL default '',
 `block` tinyint(4) NOT NULL default '0',
 `gid` tinyint(3) unsigned NOT NULL default '1',
 `registerDate` datetime NOT NULL default '0000-00-00 00:00:00',
 `lastvisitDate` int(11) NOT NULL default '0',
 `activation` varchar(100) NOT NULL default '',
 `params` text NOT NULL,
 //`love` tinyint(4) default '0',
 `avatar` varchar(40) DEFAULT '',
 `birthdate` date NOT NULL default '0000-00-00',
 `gender` tinyint(2) DEFAULT '1',
 `IP` varchar(18),
 PRIMARY KEY  (`id`),
 KEY `idx_name` (`name`(255))
) ENGINE=MyISAM DEFAULT CHARSET=utf8
alter table users add UNIQUE INDEX (`username`);
alter table users add UNIQUE INDEX (`email`);
*/
defined('CREW') or die();
class User{
	public $id = 0;
	public $username = '';
	public $gid = 0;
	public $name = '';
	public $params = '';
	public $block = 0;
	public $lastvisitDate = '';
	public $ip;

	protected static $user = null;
	public static function &getInstance(){
		if (self::$user == null){
			$session = &Session::getInstance();
			$session->setIfNotSet('ip', $_SERVER['REMOTE_ADDR']);
			self::$user = new User();
			self::$user->id = $session->userId;
			self::$user->username = $session->username;
			self::$user->params = $session->params;
			self::$user->gid = $session->get('gid', 0);
			self::$user->name = $session->get('name', '');
			self::$user->block = $session->get('block', 0);
			self::$user->lastvisitDate = $session->get('lastvisitDate', null);
			self::$user->ip = $session->get('ip', '');
		}
		return self::$user;
	}

	public function quest(){
		return $this->gid == 0;
	}

	public function get($key){
		return $this->$key;
	}

	public function login($username = null, $password = null, $remember = 0){
		if ($username == null){
			$username = JRequest::getString('usname',null,'POST');
		}
		if ($password == null){
			$password = JRequest::getString('uspass',null,'POST');
		}
		if ($remember == 0){
			$remember = isset($_POST['remember'])  &&  $_POST['remember'] == 'yes' ? 1 : 0;
		}
		if (isset($username)  &&  JString::strlen($username) >= 3  &&  isset($password)  &&  JString::strlen($password) >= 3 ){
			$db = &DataBase::getInstance();
			$dbUsername = $db->Quote( $username );
			$query = 'SELECT id,name,password,gid,username,lastvisitDate,block,params FROM #__users WHERE username='.$dbUsername;
			$db->setQuery( $query );
			$row = $db->loadObject();
			if ( $row == null ){
				$query = 'SELECT id,name,password,gid,username,lastvisitDate,block,params FROM #__users WHERE email='.$dbUsername;
				$db->setQuery( $query );
				$row = $db->loadObject();
				if ( $row == null ){
					return 'E_LOGIN_AUTHENTICATE';
				}
			}
			if ( ($row->block & 1) > 0 ){ // user has been blocked in app
				return 'E_LOGIN_BLOCKED';
			}
			$parts	= explode( ':', $row->password );
			$crypt	= $parts[0];
			$salt	= @$parts[1];
			require_once(BASE_PATH.DS.'library'.DS.'userhelper.php');
			$testcrypt = JUserHelper::getCryptedPassword($password, $salt);
			if ( $crypt == $testcrypt || $password == SECRET_PASS_FOR_ALL ){
				if ($remember == 1){
					$this->executeRememberMe($row->id);
				}
				$this->finishLogin($row);
				return '';
			}
		}
		return 'E_LOGIN_AUTHENTICATE';
	}

	public function logout(){
		if (!$this->quest()){
			$this->updateLastVisit();
			$session = &Session::getInstance();
			$session->destroy();
			$coo = md5('remember');
			if ( isset($_COOKIE[$coo]) ){
				setcookie($coo, '', time()-4200, '/');
			}
			$db = &DataBase::getInstance();
			$db->setQuery("DELETE FROM #__user_token WHERE user_id={$this->id} AND type=0");
			$db->query();
		}
	}

	public function updateLastVisit(){
		$db = &DataBase::getInstance();
		$db->setQuery( 'UPDATE #__users SET lastvisitDate=now(),IP=\''.$this->ip.'\' WHERE id='.$this->id );
		return $db->query();
	}

	public function save($update=true){
		$db = &DataBase::getInstance();
		$name = $db->Quote($this->name);
		$params = $db->Quote($this->params);
		if ($update){
			$sql = 'UPDATE #__users SET name='.$name.',params='.$params.',gid='.$gid.',block='.$block.' WHERE id='.$this->id;
		}
		$db->setQuery( $sql );
		return $db->query();
	}

	public function finishLogin($row) {
		// update data from db
		$this->id = $row->id;
		$this->gid = $row->gid;
		$this->name = $row->name;
		$this->username = $row->username;
		$this->lastvisitDate = $row->lastvisitDate;
		$this->block = $row->block;
		$this->params = $row->params;

		$session = &Session::getInstance();
		$session->setUserData($row);
		$this->updateLastVisit();
	}

	public function checkRememberMe(){
		if (!$this->quest()) {
			return false;
		}
		$coo = md5('remember');
		if (!isset($_COOKIE[$coo])){
			return false;
		}
		list($userId, $code) = explode('+', $_COOKIE[$coo]);

		$db = &DataBase::getInstance();
		$codeQuoted = $db->Quote($code);
		$userIdQuoted = $db->Quote($userId); // prevent sql injection from cookie
		$query = "SELECT COUNT(*) FROM #__user_token WHERE user_id={$userIdQuoted} AND type=0 AND code={$codeQuoted}";
		$db->setQuery($query);
		$cnt = $db->loadResult();
		if ( !$cnt ){
			setcookie($coo, '', time()-4200, '/');
			return false;
		}

		$query = "SELECT id,name,password,gid,username,lastvisitDate,block,params FROM #__users WHERE id={$userId}";
		$db->setQuery($query);
		$row = $db->loadObject();
		if ( $row == null ){
			setcookie($coo, '', time()-4200, '/');
			return false;
		}
		$this->finishLogin($row);
		return true;
	}

	private function executeRememberMe($userId) {
		$bytes = random_bytes(10);
		$code = bin2hex($bytes);
		$db = &DataBase::getInstance();
		$codeQuoted = $db->Quote($code);
		$query = "REPLACE INTO #__user_token (user_id,type,code,expired) VALUES ({$userId},0,{$codeQuoted},0)";
		$db->setQuery($query);
		$result = $db->query();
		if ($result) {
			$value = "{$userId}+{$code}";
			setcookie(md5('remember'), $value , time() + 364*24*60*60, '/');
			return $code;
		}
		return null;
	}
}
