<?php
/*=============================================================================
|| ##################################################################
||	Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||	Contact			: crewce@hotmail.com
||
|| ##################################################################
=============================================================================*/
 defined( 'CREW' ) or die( 'Restricted access' );
 
class ModelPhotoEvents extends JModel
{  	
	var $l_id;
	var $c_id;
	var $a_id;
	var $published;
	function init($a,$b,$c,$d=1){
		$this->c_id=$a;
		$this->a_id=$b;
		$this->l_id=$c;
		$this->published=$d;
	}
  function getEvents( $day, $month, $year, $limitstart, $limit )
  {
  	 $db = $this->getDBO();
  	 $tmp = "event.published=$this->published";
  	 if ( $day > 0 )
  	   $tmp .= " AND event.date='$year-$month-$day'";
  	 else if ( $month > 0 )
  	   $tmp .= " AND event.date>='$year-$month-1' AND event.date<='$year-$month-31'";
  	 else  if ( $year > 0 )
  	 	 $tmp .= " AND YEAR(event.date)=$year";  	 	 
  	 if ( $this->l_id > 0 )
  	   $tmp .= " AND event.l_id=$this->l_id";
  	 if ( $this->c_id > 0 )
  	   $tmp .= " AND event.c_id=$this->c_id";
  	 if ( $this->a_id > 0 )
  	   $tmp .= " AND event.a_id=$this->a_id";  
  	 $query = "SELECT count(*) FROM #__photo_events AS event WHERE $tmp";
  	 $db->setQuery( $query );	 
  	 $list = array();
  	 $list['cnt'] = $db->loadResult();	 
		 $query = "SELECT event.*,location.name AS location,cat.name AS category FROM #__photo_events AS event JOIN #__photo_locations AS location ON location.id=event.l_id JOIN #__photo_categories AS cat ON cat.id=event.c_id WHERE $tmp ORDER BY event.date DESC,event.name ASC";
  	 //$query = "SELECT img.file_name AS most_file_name,event.*,location.name AS location,cat.name AS category FROM #__photo_events AS event JOIN #__photo_locations AS location ON location.id=event.l_id JOIN #__photo_categories AS cat ON cat.id=event.c_id JOIN #__photo_images AS img ON img.id=(SELECT tmp.id FROM #__photo_images AS tmp WHERE tmp.event_id=event.id order by number_of_views DESC LIMIT 1)".
  	 " WHERE $tmp ORDER BY event.date DESC,event.name ASC";
  	 $list['rows'] = $this->_getList( $query, $limitstart, $limit );  	 
  	 return $list;
  }	
  
  function getDaysWithEvent( $month, $year )
  {
  	 $tmp = "WHERE a.date>='$year-$month-1' AND a.date<='$year-$month-31' AND a.published=$this->published";
  	 if ( $this->l_id > 0 )
  	   $tmp .= " AND a.l_id=$this->l_id";
  	 if ( $this->c_id > 0 )
  	   $tmp .= " AND a.c_id=$this->c_id";
   	 if ( $this->a_id > 0 )
  	   $tmp .= " AND a.a_id=$this->a_id";    
  	 $query = "SELECT DAY(a.date) AS day,b.name FROM #__photo_events AS a JOIN #__photo_locations AS b ON b.id=a.l_id $tmp";
  	 return $query;
  }
  
  function getMaxDate( $c_id, $l_id, $a_id )
  {
  	 $tmp = "WHERE published=1";
  	 if ( $l_id > 0 )
  	   $tmp .= " AND l_id=$l_id";
  	 if ( $c_id > 0 )
  	   $tmp .= " AND c_id=$c_id";	
  	 if ( $a_id > 0 )
  	   $tmp .= " AND a_id=$a_id";  	     
  	 $db = $this->getDBO();  	   	 
  	 $query = "SELECT DATE( MAX( date ) ) FROM #__photo_events $tmp";
  	 $db->setQuery( $query );  
  	 return $db->loadResult();  	 
  }
  
  function getLocations()
  {
  	$query = "SELECT * FROM #__photo_locations  WHERE hasPictures=1 ORDER BY address ASC,name ASC";
  	//echo str_replace('#__','jos_',$query);
  	$rows = $this->_getList($query);
   	$lists = array(new stdClass());
   	$lists[0]->id = 0;
   	$lists[0]->name = 'Sve lokacije'; 
    $lists[0]->address = 'All';
   	if ( isset($rows) )
   	  $lists = array_merge( $lists, $rows );
  	return $lists;

  } 
   
  function getCategories()
  {
  	 $query = "SELECT * FROM #__photo_categories WHERE p_id=$this->c_id OR id=$this->c_id ORDER BY p_id ASC,name ASC";
  	 return $this->_getList($query);
  }
  
  function getAuthors()
  {
  	$query = "SELECT id,name FROM #__users WHERE gid=21";
  	$rows = $this->_getList($query);
  	$lists = array(new stdClass());
   	$lists[0]->id = 0;
   	$lists[0]->name = 'Svi autori';
   	if ( isset($rows) )
   	  $lists = array_merge( $lists, $rows );
  	return $lists;
  }
  
}
?>