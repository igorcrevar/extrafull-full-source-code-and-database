<?php
/*=============================================================================
|| ##################################################################
||	Igor Crevar Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||	Contact			: crewce@hotmail.com
||
||	- Extrafull and all of its source code and files are protected by Copyright Laws.
||
||	- You can not use any of the code without Igor Crevar agreement
||
||	- You may also not remove this copyright screen which shows the copyright information and credits for Extrafull (Igor Crevar).
||
||	- Igor Crevar Extrafull is NOT a FREE software
||
|| ##################################################################
=============================================================================*/
defined( 'CREW' ) or die( 'Restricted access' );

class EventsViewList extends JView
{
	function display( $tpl = null)
	{
		$model = $this->getModel();
	  $task = JRequest::getCmd('task');
	  $day = JRequest::getInt('day',0); 
	  $month = JRequest::getInt('month',0);
	  $year = JRequest::getInt('year',0);
	  $old = JRequest::getCmd('old','no') == 'yes' ? 1 : 0;
	 	require_once(BASE_PATH.DS.'modules'.DS.'my_calendar.php');
	  if ($month && $year){
	  	if ( $day ){
	  		$date = $year.'-'.$month.'-'.$day;
	  		$rows = $model->loadEvents($date);
	  	}
	  	else{
	  		$rows = $model->loadEvents3($year,$month);
	  	}
	  	$this->assignRef( 'rows', $rows );
	  }
	  else{ 
	  	$rows = $model->loadEvents2( $old );
	  	$this->assignRef( 'rows', $rows );
	  }
		$cal = new My_Calendar();
	  if ( !$year || !$month ) list($year,$month,$tmp) = explode('-',date('Y-n-j'));
	  $link='index.php?option=com_events&view=list';
	  $cal->init($link, $link, $year,$month,$day,false);
	  $cal->raw=false;
	  $cal->sql=$model->loadEventsSQL().$cal->getWhere();
	  $this->assignRef( 'cal', $cal );
	  $format = 'html';
	  $this->assignRef( 'format', $format );	  
		parent::display( $tpl );
	}	  
}
?>
