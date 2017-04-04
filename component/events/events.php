<?php
// no direct access
require_once(JPATH_BASE.DS.'rights.php');
defined('CREW') or die('Restricted access');
$doc = &Document::getInstance();
$doc->addScript(JURI::base().'component/events/js.js');
$doc->addStyleSheet(JURI::base().'component/events/css.css');
require_once (JPATH_COMPONENT.DS.'controller.php');
$controller = new EventsController();
$controller->setName('Events');
$controller->execute(JRequest::getCmd('task', 'display'));
$controller->redirect();
?>