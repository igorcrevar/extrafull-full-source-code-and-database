<?php
// no direct access
defined('CREW') or die('Restricted access');
require_once (JPATH_COMPONENT.DS.'controller.php');
$controller = new ForumController();
$controller->setName('forum');
$controller->execute(JRequest::getVar('task', 'display', 'default', 'cmd'));
$controller->redirect();

function &getModers($txt){
  	$tmp = explode(',,,,,',$txt);
  	$moderators = array();
  	for ($i = 0; $i < count($tmp); ++$i){
  		list($id,$dm) = explode('-',$tmp[$i]);
  		$moderators[] = $id;
  	}  
  	return $moderators;		
}
?>