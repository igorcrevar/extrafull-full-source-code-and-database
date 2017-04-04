<?php
defined( 'CREW' ) or die( 'Restricted access' );

class MembersViewRequest extends JView
{
	function display( $tpl = null)
	{
		$model = $this->getModel();
	  $rows = $model->loadReqPen();
	  $friends = $model->loadFriends();	  
	  $this->assignRef( 'req', $rows['req'] );
	  $this->assignRef( 'pen', $rows['pen'] );
	  $this->assignRef( 'friends', $friends );
	  $lover = $model->loadLover();
	  $this->assignRef( 'lover', $lover );
	  $msg = JRequest::getCmd('userMsg');
	  $this->assignRef( 'msg', $msg );
 	  $layout = 'default';
		parent::display( $tpl );
	}	  
}
?>
