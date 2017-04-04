<?php

class PhotoViewEvents extends JView
{
  function display($tpl = null)
	{		
		  $model = $this->getModel();
		  $limit = 8;
		  $limitstart	= JRequest::getInt( 'limitstart', 0 );  
		  $a_id = JRequest::getInt( 'a_id' );
		  $c_id = JRequest::getInt( 'c_id' );
		  $l_id = JRequest::getInt( 'l_id' );
		  $year = JRequest::getInt( 'year', 0, 'GET' );
		  $month = JRequest::getInt( 'month', 0, 'GET' );
		  $day = JRequest::getInt( 'day', 0, 'GET' );

		  if (!$year)
		  {
		  	$date = $model->getMaxDate( $c_id, $l_id, $a_id );
		  	if (!$date) {
		  	  $date = date( 'Y-m' );
				}
		  	list( $year, $month) = explode( '-', $date );
		  }
		  $year = (int)$year;
		  $month = (int)$month;
		  if ( $year > 2015) $year = 2015;
		  else if ( $year < 2000 ) $year = 2000;
		  $old_month = $month;
		  $model->init($c_id,$a_id,$l_id);
		  $events = $model->getEvents( $day, $month, $year, $limitstart,$limit );  
		  $pagination = &JHTML::getPagination( $events['cnt'], $limitstart, $limit, null, 'html' );
	    $this->assignRef( 'rows'  , $events['rows'] );
	    $this->assignRef( 'pagination',	$pagination );  

	    		  
	    if ( $month <= 0 ) $month = (int)date('n');

	    $loc_list = $model->getLocations();
	    $cat_list = $model->getCategories( $c_id );
      $authors = $model->getAuthors();
      
			$this->assignRef( 'authors', $authors );
		  $this->assignRef( 'categories', $cat_list );
      $this->assignRef( 'locations', $loc_list );	      
	    $this->assignRef( 'year', $year );
	    $this->assignRef( 'month', $month );
      $this->assignRef( 'day', $day ); 
	    $this->assignRef( 'c_id', $c_id );
	    $this->assignRef( 'l_id', $l_id );
	    $this->assignRef( 'a_id', $a_id );	          

	 		require_once(BASE_PATH.DS.'modules'.DS.'my_calendar.php');
		  $calendar = new My_Calendar();
		  $calendar->init( 'ludlo',null,$year,$month,$day,false);
		  $calendar->sql = $model->getDaysWithEvent($month,$year);
		  $this->assignRef( 'calendar',	$calendar );  
	    parent::display( $tpl );
  }
}  
?>