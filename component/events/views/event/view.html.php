<?php
defined( 'CREW' ) or die( 'Restricted access' );

class EventsViewEvent extends JView
{
	function display( $tpl = null)
	{
		$model = $this->getModel();
		$id = JRequest::getInt('id');
		$model->id = $id;		
	  $limit =  8;
	  $limitstart	= JRequest::getInt( 'limitstart', 0 );
		$event = $model->loadEvent( $limit, $limitstart );
		if ( !count($event) ) return;
		$event->oldDate = $event->date;
		$event->date = JHTML::date( $event->date, 'date2' );
		$user = &User::getInstance();
		$moder = MyRights::isEventsModerator( $user->id );
		$right = ($user->id == $event->user_id) || $moder;
	  $doc = &Document::getInstance();
	  $doc->setDescription( 'Desavanje - '.$event->name.' u '.$event->location_name.' '.$event->date.'.'.substr( $event->text, 0, 200 ) );
	  $doc->setTitle( 'Desavanje - '.$event->name.' u '.$event->location_name.' '.$event->date );
	  $doc->keywords = "$event->name, $event->date, $event->location_name, desavanje";
	  $pag = &JHTML::getPagination( $event->cnt, $limitstart, $limit, null, 'html' );
	  $attend = $model->whoAttend();
		$this->assignRef( 'pag', $pag );
		$this->assignRef( 'right', $right );
		$this->assignRef( 'moder', $moder );
		$this->assignRef( 'attend', $attend );
	  $this->assignRef( 'event', $event );
		parent::display( $tpl );
	}	  
}
?>
