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
class JText{
	protected static $items = array();
	
	public static function parseIni($f_name = ''){
		 global $mainframe;
		 $lang = $mainframe->getLanguage();
		 if ($fname == ''){
		 	 $path = BASE_PATH.DS.'langs'.DS.$lang.DS.$lang.'.ini'; 
		 }
		 else{
		 	 $path = BASE_PATH.DS.'langs'.DS.$lang.DS.$lang.'.'.$f_name.'.ini'; 
		 }
		 $fh = @fopen( $path, 'r');
		 $contents = @fread($fh, filesize($path));
		 @fclose($fh);
		 $values = explode("\n",$contents);
		 foreach ($values as $vals){
		 	  list($key,$val) = explode('=',$vals,2);
		 	  $key = strtoupper(trim($key));
		 	  $bk = ord($key[0]);
		 	  if ($bk < ord('A') || $bk > ord('Z')) continue;  
		 	  $val = trim($val);
		 	  self::$items[$key] = $val;
		 }
	}
	
	public static function _($key){
		global $text;
    	if (!isset($text[$key])){
      		return $key;
    	}
		return $text[$key]; //strtoupper($key)];
	}
		
}
?>