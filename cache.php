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
class Cache{
	private $lifeTime;
	private $caching;
	private static $cache = null;
	
	public function __construct(){
		$this->lifeTime = 0;
		$this->caching = false;
	}
	
	public function setCaching($caching){
		$this->caching = $caching;
	}
	
	public function setLifeTime($lifeTime){
		$this->lifeTime = $lifeTime;
	}
	
	public static function &getInstance(){
		if (self::$cache == null){
			self::$cache = new Cache();
		}
		return self::$cache;
	}
	
	public function clear(){
		$args		= func_get_args();
		$callback	= array_shift($args);
		$tmpname = count( $args ) ? $args[0] : '';
		$tmpname = is_array($callback) ? $callback[0].$callback[1].$tmpname : $callback.$tmpname;
		$id = md5( $tmpname );		
		$path = BASE_PATH.DS.'cache'.DS.$id;
		@unlink($path);
	}
	
	public function call(){
		$args		= func_get_args();
		$callback	= array_shift($args);
		if (!$this->caching){
			return call_user_func_array( $callback, $args );
		}
		$tmpname = count( $args ) ? $args[0] : '';
		$tmpname = is_array($callback) ? $callback[0].$callback[1].$tmpname : $callback.$tmpname;
		$id = md5( $tmpname );
		$path = BASE_PATH.DS.'cache'.DS.$id;
		$fh = @fopen( $path, 'r');
		$oldTime = 0;		
		if ($fh){
			$contents = @fread( $fh, filesize($path) );
			$class = unserialize($contents);
			$oldTime = $class->oldTime;
			fclose($fh);
		}
		$newTime = time();
		if ($newTime - $this->lifeTime > $oldTime ){
			 $rows = call_user_func_array( $callback, $args );
		  /* $newTime = $newTime.'';
		   while ( strlen($newTime) < 10 ){
		   	 $newTime .= ' '.$newTime;
		   }*/
		   $fh = fopen( $path, 'w');
		   flock($fh, LOCK_EX);
		   $class = new stdClass();
		   $class->oldTime = $newTime;
		   $class->rows = $rows;
		   $contents = serialize($class);
		   fwrite($fh,$contents);
			 flock($fh, LOCK_UN);
			 fclose($fh);		   
		}
		else{
			$rows = $class->rows;
		}
			
		return $rows;
	}

}	