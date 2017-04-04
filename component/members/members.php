<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_COMPONENT.DS.'controller.php');
$controller = new MembersController();
$controller->setName('Members');
$controller->execute(JRequest::getCmd('task', 'display'));
$controller->redirect();

function isProfileMod($id){
	$mods = array(68,72,8435);
	return in_array($id,$mods);
}
?>