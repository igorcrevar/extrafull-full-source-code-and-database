<?php
// no direct access
defined('CREW') or die('Restricted access');
require_once (JPATH_COMPONENT.DS.'controller.php');
$controller = new BlogController();
$controller->setName('Blog');
$controller->execute(JRequest::getVar('task', 'display', 'default', 'cmd'));
$controller->redirect();

function isMod($id){
	$mods = array(63,68,72);
	return in_array($id,$mods);
}
?>