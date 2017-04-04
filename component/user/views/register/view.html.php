<?php
defined('CREW') or die();

class UserViewRegister extends JView
{
	var $oldVals;
	var $errors = array();
	function display($tpl = null)
	{
		$document = Document::getInstance();
	 	$document->setTitle( JText::_( 'Extrafull Registracija' ) );
	 	//echo 'Registracija je trenutno onemogucena';
		parent::display($tpl);
	}
}
?>
