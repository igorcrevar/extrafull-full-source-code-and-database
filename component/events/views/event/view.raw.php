<?php
defined( 'CREW' ) or die( 'Restricted access' );

class EventsViewEvent extends JView
{
	function display( $tpl = null)
	{
		$model = $this->getModel();
		$attends = $model->whoAttend();		
	  $this->assignRef( 'attend', $attends );
	  $this->setLayout( 'default_attend' );
		parent::display( $tpl );
	}	  
}
?>
