<?php
defined('CREW') or die();
/*=============================================================================
|| ##################################################################
||	Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||
|| ##################################################################
=============================================================================*/
class JString{
	public static function strlen($txt){
		return mb_strlen($txt);
	}
	
	public static function substr($str, $offset, $length = FALSE){
    if ( $length === FALSE ) {
        return mb_substr($str, $offset);
    } else {
        return mb_substr($str, $offset, $length);
    }
	}	

	public static function strpos($str, $search, $offset = FALSE){
		if(strlen($str) && strlen($search)) {
	    if ( $offset === FALSE ) {
	        return mb_strpos($str, $search);
	    } else {
	        return mb_strpos($str, $search, $offset);
	    }
		}
		else return FALSE;
	}	
	
	public static function strtolower($str){
    return mb_strtolower($str);
	}

	public static function strtoupper($str){
    return mb_strtoupper($str);
	}	
}
?>