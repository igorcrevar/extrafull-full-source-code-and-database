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
	  $day=JRequest::getInt('day',0); 
	  $month=JRequest::getInt('month');
	  $year=JRequest::getInt('year',0);
	  $old = JRequest::getCmd('old','yes') == 'yes' ? 1 : 0;
	  if ($day && $month && $year){
	  	$date = $year.'-'.$month.'-'.$day;
	  	$rows = $model->loadEvents($date);
	  	$old = -1;
	  	$this->assignRef( 'old', $old);
	  	$this->assignRef( 'rows', $rows );
	  }
	  else{ 
	  	$rows = $model->loadEvents2($old);
	  	$this->assignRef( 'old', $old);
	  	$this->assignRef( 'rows', $rows );
	  }
	  $format = 'raw';
	  $this->assignRef( 'format', $format );
		parent::display( $tpl );
	}	  
}
?>
