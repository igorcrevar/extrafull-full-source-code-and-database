<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );

class MyRights{
	public static function canChangeEvents(){
		$user = &JFactory::getUser();
		return $user->gid >= 18;
	}
  
	public static function isEventsModerator($id){
		$arr = array(68,72);
		return in_array($id,$arr);
	}
}