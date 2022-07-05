<?php
defined('CREW') or die();

require_once(BASE_PATH.DS.'library'.DS.'filterinput.php');
$GLOBALS['_JREQUEST'] = array();

define( 'JREQUEST_NOTRIM'   , 1 );
define( 'JREQUEST_ALLOWRAW' , 2 );
define( 'JREQUEST_ALLOWHTML', 4 );

function jimport($param){
	
}

class JRoute{
	public static function _($route){
		if ($route == 'index.php') return Basic::routerBase();
		$route = substr($route,10);
		$queries = explode('&',$route);
		foreach ($queries as $rquery){
			 list($key,$val) = explode('=',$rquery);
			 $query[$key] = $val; 
		}
		//$query['option'] = substr($query['option'],4);
		if (!isset($query['view'])) {
			$query['view'] = '';
		}
		if (!isset($query['option'])) {
			$query['option'] = '';
		}
		switch ( substr($query['option'], 4, 3) ){
			case 'pho':			
				if ($query['view'] == 'image')
				{
					$tmp = 'slika';
				}
				else if ($query['view'] == 'event'){
					$tmp = 'galerija';
				}
				else if ($query['view'] == 'images'){
					$tmp = 'slike';
				}
				else if ($query['view'] == 'imageupload'){
					$tmp = 'galerije/upload';
				}
				else{
					$tmp = 'galerije';
				}
				unset($query['view']);
				break;
			case 'mem':
				$tmp2 = substr($query['view'],0,3);
				if ($tmp2 == 'pro')
				{
					$tmp = 'profil';
					unset($query['view']);
				}
				else if ($tmp2 == 'use' || $tmp2=='' || !isset($tmp2) ){
					$tmp = 'clanovi';
					unset($query['view']);
				}
				else if ($tmp2 == 'fri'){
					$tmp = 'clan/prijatelji';
					unset($query['view']);
				}
				else if ($tmp2 == 'myp'){
					$tmp = 'clan/mojprofil';
					unset($query['view']);
				}
				else {
					$tmp = 'clan';
				}			
				break;
			case 'fir':$tmp = 'forum';break;
			case 'eve':
				$tmp = 'desavanja';
				break;			
			case 'jim':$tmp = 'poruke';break;
			case 'use':$tmp= 'korisnik';break;
			default:$tmp = '';break;
		}		
		$component = preg_replace('/[^A-Z0-9]/i', '', $query['option']);
		$component = str_replace('com','',$component);
		unset($query['option']);
		if ( empty($query) ){
			return Basic::routerBase().'/'.$tmp;
		}
		$path = BASE_PATH.DS.'component'.DS.$component.DS.'router.php';
		if (file_exists($path)){
			require_once $path;
			$function	= $component.'BuildRoute';
			$parts		= $function($query);
			//mozda za da prebacim UTF-8 u ASCII?
			//$parts = $this->_encodeSegments($parts);
			if ( count($parts) ){
				$tmp  .= '/'.join('/', $parts);
			}
		}
		if (count($query) > 0 ){
			$i = 0;
			foreach ($query as $key => $val){
				$tmp .= (($i == 0)? '?' : '&').$key.'='.$val;
				$i = 1;
			}
		}
		return Basic::routerBase().'/'.$tmp;
	}
}

class JURI{
	public static function base(){
		return Basic::uriBase().'/';
	}
}

class JFactory{
	public static function &getDBO(){
		return Database::getInstance();
	}
	public static function &getUser(){
		return User::getInstance();
	}
	public static function &getSession(){
		return Session::getInstance();
	}	
	public static function &getDocument(){
		return Document::getInstance();
	}	
}

class JRequest
{
	public static function getMethod()
	{
		$method = strtoupper( $_SERVER['REQUEST_METHOD'] );
		return $method;
	}

	public static function getVar($name, $default = null, $hash = 'default', $type = 'none', $mask = 0)
	{
		// Ensure hash and type are uppercase
		$hash = strtoupper( $hash );
		if ($hash === 'METHOD') {
			$hash = strtoupper( $_SERVER['REQUEST_METHOD'] );
		}
		$type	= strtoupper( $type );
		$sig	= $hash.$type.$mask;

		// Get the input hash
		switch ($hash)
		{
			case 'GET' :
				$input = &$_GET;
				break;
			case 'POST' :
				$input = &$_POST;
				break;
			case 'FILES' :
				$input = &$_FILES;
				break;
			case 'COOKIE' :
				$input = &$_COOKIE;
				break;
			case 'ENV'    :
				$input = &$_ENV;
				break;
			case 'SERVER'    :
				$input = &$_SERVER;
				break;
			default:
				$input = &$_REQUEST;
				$hash = 'REQUEST';
				break;
		}

		if (isset($GLOBALS['_JREQUEST'][$name]['SET.'.$hash]) && ($GLOBALS['_JREQUEST'][$name]['SET.'.$hash] === true)) {
			// Get the variable from the input hash
			$var = (isset($input[$name]) && $input[$name] !== null) ? $input[$name] : $default;
		}
		elseif (!isset($GLOBALS['_JREQUEST'][$name][$sig]))
		{
			if (isset($input[$name]) && $input[$name] !== null) {
				// Get the variable from the input hash and clean it
				$var = JRequest::_cleanVar($input[$name], $mask, $type);

				// Handle magic quotes compatability
				// changed by crew in 6.VII.2022. Hopefully this is ok
				if((function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) || 
					(ini_get('magic_quotes_sybase') && (strtolower(ini_get('magic_quotes_sybase'))!="off")) ){ 
					if (get_magic_quotes_gpc() && ($var != $default) && ($hash != 'FILES')) {
						$var = JRequest::_stripSlashesRecursive( $var );
					}
				}				

				$GLOBALS['_JREQUEST'][$name][$sig] = $var;
			}
			elseif ($default !== null) {
				// Clean the default value
				$var = JRequest::_cleanVar($default, $mask, $type);
			}
			else {
				$var = $default;
			}
		} else {
			$var = $GLOBALS['_JREQUEST'][$name][$sig];
		}

		return $var;
	}


	public static function getInt($name, $default = 0, $hash = 'default')
	{
		return JRequest::getVar($name, $default, $hash, 'int');
	}


	public static function getFloat($name, $default = 0.0, $hash = 'default')
	{
		return JRequest::getVar($name, $default, $hash, 'float');
	}


	public static function getBool($name, $default = false, $hash = 'default')
	{
		return JRequest::getVar($name, $default, $hash, 'bool');
	}

	
	public static function getWord($name, $default = '', $hash = 'default')
	{
		return JRequest::getVar($name, $default, $hash, 'word');
	}


	public static function getCmd($name, $default = '', $hash = 'default')
	{
		return JRequest::getVar($name, $default, $hash, 'cmd');
	}


	public static function getString($name, $default = '', $hash = 'default', $mask = 0)
	{
		// Cast to string, in case JREQUEST_ALLOWRAW was specified for mask
		return (string) JRequest::getVar($name, $default, $hash, 'string', $mask);
	}


	public static function setVar($name, $value = null, $hash = 'method', $overwrite = true)
	{
		//If overwrite is true, makes sure the variable hasn't been set yet
		if(!$overwrite && array_key_exists($name, $_REQUEST)) {
			return $_REQUEST[$name];
		}

		// Clean global request var
		$GLOBALS['_JREQUEST'][$name] = array();

		// Get the request hash value
		$hash = strtoupper($hash);
		if ($hash === 'METHOD') {
			$hash = strtoupper($_SERVER['REQUEST_METHOD']);
		}

		$previous	= array_key_exists($name, $_REQUEST) ? $_REQUEST[$name] : null;

		switch ($hash)
		{
			case 'GET' :
				$_GET[$name] = $value;
				$_REQUEST[$name] = $value;
				break;
			case 'POST' :
				$_POST[$name] = $value;
				$_REQUEST[$name] = $value;
				break;
			case 'COOKIE' :
				$_COOKIE[$name] = $value;
				$_REQUEST[$name] = $value;
				break;
			case 'FILES' :
				$_FILES[$name] = $value;
				break;
			case 'ENV'    :
				$_ENV['name'] = $value;
				break;
			case 'SERVER'    :
				$_SERVER['name'] = $value;
				break;
		}

		// Mark this variable as 'SET'
		$GLOBALS['_JREQUEST'][$name]['SET.'.$hash] = true;
		$GLOBALS['_JREQUEST'][$name]['SET.REQUEST'] = true;

		return $previous;
	}


	public static function get($hash = 'default', $mask = 0)
	{
		$hash = strtoupper($hash);

		if ($hash === 'METHOD') {
			$hash = strtoupper( $_SERVER['REQUEST_METHOD'] );
		}

		switch ($hash)
		{
			case 'GET' :
				$input = $_GET;
				break;

			case 'POST' :
				$input = $_POST;
				break;

			case 'FILES' :
				$input = $_FILES;
				break;

			case 'COOKIE' :
				$input = $_COOKIE;
				break;

			case 'ENV'    :
				$input = &$_ENV;
				break;

			case 'SERVER'    :
				$input = &$_SERVER;
				break;

			default:
				$input = $_REQUEST;
				break;
		}

		$result = JRequest::_cleanVar($input, $mask);

		// Handle magic quotes compatability
		if (get_magic_quotes_gpc() && ($hash != 'FILES')) {
			$result = JRequest::_stripSlashesRecursive( $result );
		}

		return $result;
	}

	
	public static function set( $array, $hash = 'default', $overwrite = true )
	{
		foreach ($array as $key => $value) {
			JRequest::setVar($key, $value, $hash, $overwrite);
		}
	}

	
	public static function checkToken( $method = 'post' )
	{
		/*$token	= JUtility::getToken();
		if(!JRequest::getVar( $token, '', $method, 'alnum' )) {
			$session = JFactory::getSession();
			if($session->isNew()) {
				//Redirect to login screen
				global $mainframe;
				$return = JRoute::_('index.php');
;				$mainframe->redirect($return, JText::_('SESSION_EXPIRED'));
				$mainframe->close();
			} else {
				return false;
			}
		} else {
			return true;
		}*/
	}

	public static function clean()
	{
		JRequest::_cleanArray( $_FILES );
		JRequest::_cleanArray( $_ENV );
		JRequest::_cleanArray( $_GET );
		JRequest::_cleanArray( $_POST );
		JRequest::_cleanArray( $_COOKIE );
		JRequest::_cleanArray( $_SERVER );

		if (isset( $_SESSION )) {
			JRequest::_cleanArray( $_SESSION );
		}

		$REQUEST	= $_REQUEST;
		$GET		= $_GET;
		$POST		= $_POST;
		$COOKIE		= $_COOKIE;
		$FILES		= $_FILES;
		$ENV		= $_ENV;
		$SERVER		= $_SERVER;

		if (isset ( $_SESSION )) {
			$SESSION = $_SESSION;
		}

		foreach ($GLOBALS as $key => $value)
		{
			if ( $key != 'GLOBALS' ) {
				unset ( $GLOBALS [ $key ] );
			}
		}
		$_REQUEST	= $REQUEST;
		$_GET		= $GET;
		$_POST		= $POST;
		$_COOKIE	= $COOKIE;
		$_FILES		= $FILES;
		$_ENV 		= $ENV;
		$_SERVER 	= $SERVER;

		if (isset ( $SESSION )) {
			$_SESSION = $SESSION;
		}

		// Make sure the request hash is clean on file inclusion
		$GLOBALS['_JREQUEST'] = array();
	}

	protected static function _cleanArray( &$array, $globalise=false )
	{
		static $banned = array( '_files', '_env', '_get', '_post', '_cookie', '_server', '_session', 'globals' );

		foreach ($array as $key => $value)
		{
			// PHP GLOBALS injection bug
			$failed = in_array( strtolower( $key ), $banned );

			// PHP Zend_Hash_Del_Key_Or_Index bug
			$failed |= is_numeric( $key );
			if ($failed) {
				jexit( 'Illegal variable <b>' . implode( '</b> or <b>', $banned ) . '</b> passed to script.' );
			}
			if ($globalise) {
				$GLOBALS[$key] = $value;
			}
		}
	}

	protected static function _cleanVar($var, $mask = 0, $type=null)
	{
		// Static input filters for specific settings
		static $noHtmlFilter	= null;
		static $safeHtmlFilter	= null;

		// If the no trim flag is not set, trim the variable
		if (!($mask & 1) && is_string($var)) {
			$var = trim($var);
		}

		// Now we handle input filtering
		if ($mask & 2)
		{
			// If the allow raw flag is set, do not modify the variable
			$var = $var;
		}
		elseif ($mask & 4)
		{
			// If the allow html flag is set, apply a safe html filter to the variable
			if (is_null($safeHtmlFilter)) {
				$safeHtmlFilter = & JFilterInput::getInstance(null, null, 1, 1);
			}
			$var = $safeHtmlFilter->clean($var, $type);
		}
		else
		{
			// Since no allow flags were set, we will apply the most strict filter to the variable
			if (is_null($noHtmlFilter)) {
				$noHtmlFilter = & JFilterInput::getInstance(/* $tags, $attr, $tag_method, $attr_method, $xss_auto */);
			}
			$var = $noHtmlFilter->clean($var, $type);
		}
		return $var;
	}


	protected static function _stripSlashesRecursive( $value )
	{
		$value = is_array( $value ) ? array_map( array( 'JRequest', '_stripSlashesRecursive' ), $value ) : stripslashes( $value );
		return $value;
	}
}