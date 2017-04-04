<?php
defined('CREW') or die;

class UserViewReset extends JView
{
	function display($tpl = null)
	{
		$layout = JRequest::getCmd( 'layout', 'default' );		
	  $this->setLayout( $layout ); 
		parent::display($tpl);
	}
}
