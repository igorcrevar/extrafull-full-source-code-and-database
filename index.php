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
error_reporting(E_ALL);
ini_set('display_errors', 1);

define( 'CREW', 1 );
define( 'BASE_PATH', dirname(__FILE__) );
define( 'DS', DIRECTORY_SEPARATOR );
ini_set('session.use_only_cookies',1);
require_once( BASE_PATH.DS.'application.php' );

startSession(); // starts session before everything
$session = Session::getInstance();
$user = User::getInstance();
/* banning */
$cache = &Cache::getInstance();
$cache->setCaching( 1 );
$cache->setLifeTime( 60 * 60 * 24 );
$bannedIPS = $cache->call( 'getIPBans' );
if ( in_array( $user->ip, $bannedIPS ) ){
	echo 'Trenutno si banovan sa sajta! Mora da si nesto mnogo zgresio. Obrati se na admin@extrafull.com ako je u pitanju greska';
	exit(0);
}
/* end banning */
$user->checkRemember(); //uloguje me ako treba

$component = JRequest::getCmd('option', null);
if ($component != null){
	$component = str_replace('com_','',$component);	//JOOMLA :)
	switch ($component){
		case 'photo': case 'members' : case 'user' : case 'events' : case 'jim': case 'rss': case 'blog': case 'votes':
			break;
		default: $component = 'default';
	}
}
else{
	$segments = Basic::getSEFParams();
  	switch ($segments[0]){
  	case 'galerija':
  		$component = 'photo';
  		$segments[0] = 'event';
  	  	break;
  	case 'galerije':
  		$component = 'photo';
  		if ( isset($segments[1]) &&  $segments[1] === 'upload' ){
  			array_shift($segments);
  			$segments[0] = 'imageupload';
  		}
  		else{
  			$segments[0] = 'events';
		}
		break;
  	case 'slika':
  		$component = 'photo';
  		$segments[0] = 'image';
  	  	break;
  	case 'slike':
  		$component = 'photo';
  		$segments[0] = 'images';
  	  	break;
  	case 'profil':
  		$component = 'members';
  		$segments[0] = 'profile';
  	  	break;
  	case 'poruke':
  		$component = 'jim';
  		unset( $segments );
  		break;
  	case 'clanovi':
  	   	$component = 'members';
  		$segments[0] = 'userlist';
  	   	break;
  	case 'clan':
		array_shift($segments);
		$task = $segments[0];
		if ( !isset($task) ) $task = 'mojprofil';
		$component = 'members';
		$array = array('prijatelji'=>'friends','mojprofil'=>'myprofile','lista' => 'userlist');
		$segments[0] = $array[ $task ];
  		break;
    case 'korisnik':
		$user = &User::getInstance();
		$task = count($segments) >= 2 ? $segments[1] : '';
		if ($task == 'pjavi'){
			$rv = $user->login();
			$mainframe->redirect( Basic::uriBase(), $rv );
		}
		else if ($task == 'logout'){
			$user->logout();
			$mainframe->redirect( Basic::uriBase() );
		}
		else if ($task == 'kontakt'){
			$component = 'contact';
			unset($segments);
		}
		else{
			$component = 'user';
			JRequest::setVar('view','register');
		}
		break;
    case 'desavanja':
		$component = 'events';
		array_shift($segments);
		break;
    case 'forum':
		$component = 'forum';
		unset($segments);
		break;
    case 'blog':
		$component = 'blog';
		array_shift($segments);
		break;
    default:
		$component = 'default';
		unset( $segments );
  }
}

$mainframe->loadLanguage($component); //ucitavam php language fajlove
 /* zbog stare kompatibilnosti*/
define('_JEXEC',1);
define('JPATH_BASE', BASE_PATH);
define('JPATH_ROOT', BASE_PATH);
define('JPATH_COMPONENT',BASE_PATH.DS.'component'.DS.$component);
if ( isset($segments)  &&  count($segments) > 0 ){
	$path = BASE_PATH.DS.'component'.DS.$component.DS.'router.php';
	if ( file_exists($path) ){
		require_once $path;
		$function =  $component.'ParseRoute';
		$vars =  $function($segments);
		foreach ($vars as $key => $val){
			JRequest::setVar($key,$val);
		}
	}
}
$doc = &Document::getInstance();
if ($doc->getType() == 'html'){
	ob_start();
	require_once( BASE_PATH.DS.'component'.DS.$component.DS.$component.'.php' );
	$componentEcho = ob_get_contents();
	ob_end_clean();
	if (!isset($doc->title)){
		$title = $text['TITLE'];
		if (isset($text['TITLE_'.$component])){
			$title = $text['TITLE_'.$component];
		}
		$doc->setTitle($title);
	}
	require_once(BASE_PATH.DS.'template'.DS.$mainframe->getTemplate().DS.'index.php');
}
else{
	require_once( BASE_PATH.DS.'component'.DS.$component.DS.$component.'.php' );
}
?>