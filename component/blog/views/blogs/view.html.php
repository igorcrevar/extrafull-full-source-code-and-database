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
class BlogViewBlogs extends JView
{
  function display($tpl = null)
	{		
		  $model = $this->getModel();
		  $limit = 14;
		  $limitstart	= JRequest::getInt( 'limitstart', 0 );  
		  $who_id = JRequest::getInt( 'id',0 );
		  $type = JRequest::getInt( 'type',0 );
		  $year = JRequest::getInt( 'year', 0, 'GET' );
		  $month = JRequest::getInt( 'month', 0, 'GET' );
		  $day = JRequest::getInt( 'day', 0, 'GET' );

		  $model->init($type,$who_id,$day,$month,$year);
		  $rows = $model->get( $limitstart,$limit );  
		  $pagination = &JHTML::getPagination( $rows['cnt'], $limitstart, $limit, null, 'html' );
	    $this->assignRef( 'rows'  , $rows['rows'] );
	    $this->assignRef( 'pagination',	$pagination );  

	    $this->assignRef( 'year', $year );
	    $this->assignRef( 'month', $month );
      $this->assignRef( 'day', $day );
      $lnk = Basic::uriBase().'/blog'; 
      if ($who_id > 0) $lnk .= '/'.$who_id;
      $lnk = $lnk.'?';
      $this->assignRef( 'lnk', $lnk );
      $this->assignRef( 'type', $type );

	 		require_once(BASE_PATH.DS.'modules'.DS.'my_calendar.php');
		  $calendar = new My_Calendar();
		  $calendar->init( 'ludlo',null,$year,$month,$day,false);
		  $calendar->sql = $model->getDaysWithEvent($month,$year);
		  $this->assignRef( 'calendar',	$calendar );  
	    parent::display( $tpl );
  }
}  
?>