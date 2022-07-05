<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	define( 'BASE_PATH', dirname(__FILE__) );
	define( 'DS', DIRECTORY_SEPARATOR );
	define( 'CREW', 1 );
	require_once(BASE_PATH.DS.'config.php');
	require_once(BASE_PATH.DS.'database.php');
	require_once(BASE_PATH.DS.'document.php');
	require_once(BASE_PATH.DS.'session.php');
	require_once(BASE_PATH.DS.'user.php');
	include BASE_PATH.DS.'component'.DS.'user'.DS.'captcha'.DS.'securimage.php';
	$img = new Securimage();
	$img->show(); // alternate use:  $img->show('/path/to/background.jpg');	