<?php
defined('CREW') or die('Restricted access');
// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');
$controller = new UserController();
$controller->setName('User');
$controller->execute( JRequest::getCmd('task','display'));
$controller->redirect();
?>