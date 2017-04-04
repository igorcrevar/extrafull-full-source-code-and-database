<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');

class MembersViewDruk extends JView
{
	function display( $tpl = null)
	{
		 $model = $this->getModel();
	 	 $rv = $model->addYour();
	   $rv = $model->loadDruks();
	   echo $rv;
	}	  
}
?>
