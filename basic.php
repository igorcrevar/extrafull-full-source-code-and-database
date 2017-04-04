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

class Basic{
	protected static $routeBase = null;
	protected static $uriBase = null;
	
	public static function routerBase(){
		if (self::$routeBase == null){
			if (strpos(php_sapi_name(), 'cgi') !== false && !empty($_SERVER['REQUEST_URI'])) {
				//Apache CGI
				self::$routeBase =  rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			} else {
				//Others
				self::$routeBase =  rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
			}
		}
		return self::$routeBase;
	}
	
	public static function uriBase(){		
		if (self::$uriBase == null){
	// Determine if the request was over SSL (HTTPS)
				if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) {
					$https = 's://';
				} else {
					$https = '://';
				}
				$s_name = $_SERVER['HTTP_HOST'];
				if (!empty ($_SERVER['PHP_SELF']) && !empty ($_SERVER['REQUEST_URI'])) {
					//Apache					
					$uriBase = 'http' . $https . $s_name . rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
				}
				else{
					//IIS
					$uriBase = 'http' . $https . $s_name.rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
				}
				// Now we need to clean what we got since we can't trust the server var
				$uriBase = urldecode($uriBase);
				$uriBase = str_replace('"', '&quot;',$uriBase);
				$uriBase = str_replace('<', '&lt;',$uriBase);
				$uriBase = str_replace('>', '&gt;',$uriBase);
				$uriBase = preg_replace('/eval\((.*)\)/', '', $uriBase);
				$uriBase = preg_replace('/[\\\"\\\'][\\s]*javascript:(.*)[\\\"\\\']/', '""', $uriBase);				
				self::$uriBase = $uriBase;
		}
		return self::$uriBase;
	}
	
	public static function requestURI( $without = array() ){
		 $rv = $_SERVER['REQUEST_URI'];
		 $pos = strpos($rv, '?');
		 if ( $pos !== false ){
			 $params = explode('&', substr($rv, $pos + 1) );
		 	 $rv = substr( $rv, 0, $pos + 1);
			 foreach ($params as $param){			 	
			 	 list($k,$v) = explode('=',$param);
			 	 if ( !in_array($k, $without) ){
			 	 	  $rv .= $param.'&';
			 	 }
			 } 
		 }	 
		 $last = $rv[ strlen($rv) - 1 ];
		 if ( $last == '&'  ||  $last == '?' ){
		 	  $rv = substr($rv, 0, strlen($rv) - 1);
		 }
		 return $rv;
	}
	
	public static function getVar($type, $paramName, $def, $inputType){
		switch ($inputType){
			case 'POST':
			  $input = &$_POST;
			  break;
			case 'GET':
			  $input = &$_GET;
			  break;
			default:
			  $input = &$_REQUEST;
		}
		$tmp = $input[$paramName];
		switch ($input){
			case 0: //int
			  return ( isset($tmp)  &&  intval($tmp) === $tmp ) ? intval($tmp) : $def;
			case 1: //string
			  return isset($tmp) ? htmlspecialchars($tmp) : $def;
			case 2: //cmd  
				return preg_replace('/[^A-Z0-9_\.-]/i', '', isset($tmp) ? $tmp : $def );
		  case 3: //int array		
		  	return self::stringToArrayInt($tmp);
		}
	}
	public function intFromHTTP($paramName, $def = 0, $type = ''){
		return self::getVar(0,$paramName, $def, $type);
	}
	
	public static function intArrayFromHTTP($paramName, $def = null, $type = ''){
			return self::getVar(3,$paramName, $def, $type);
	}
	
	public static function stringToArrayInt($str){
		$all = explode(',',$str);
		$rv = array();
		for ($i = 0; $i < count($all); ++$i ){
			$tmp = $all[$i];
			if ( $tmp != null  &&  intval($tmp) === $tmp ){
				 $rv[] = $tmp;
			}
		}
		return $rv;
	}

	public static function stringFromHTTP($paramName, $def = '', $type = ''){
		 return self::getVar(1,$paramName, $def, $type);
	}
	
	public static function cmdFromHTTP($paramName, $def = '', $type = ''){
		return self::getVar(2,$paramName, $def, $type);
	}	
		
	public static function getSEFParams(){
		 $i = 0;
		 $uri = $_SERVER['REQUEST_URI']; //samo apache
		 $base = self::routerBase();
		 $uri = substr( $uri, strlen($base) );
		 if ($uri[0] == '/') {
		 	  $uri = substr( $uri, 1 );
		 }		 
		 $pos = strpos($uri, '?');
		 if ($pos !== false){
				if ($uri[$pos-1] == '/'){
					--$pos;
				}		 	
		 		$uri = substr($uri, 0, $pos );
		 }
		 $uri = strtolower($uri);
		 return explode( '/', $uri);
	}
	
	public static function intFromString($str, $def = 0){
		return ($str == null || intval($str) !== $str) ? $def : intval($str);
	}
	
	public static function createInput($type, $name, $value, $str = '', $table = true){
		$rv = '';
		if ($table) $rv .= '<tr><td>';
		$rv .= $str;
		if ($table) $rv .= '</td><td>';
		switch ($type){
			case 'text': 
				$rv .= '<input type="text" name="'.$name.'" value="'.$value.'" />';
				break;
			case 'submit': case 'button': 
			 $rv .= '<input type="'.$type.'" name="'.$name.'" value="'.$value.'" />';
				break;
		}	
		if ($table) $rv .= '</td></tr>';
		return $rv;
	}
	
	
}
?>