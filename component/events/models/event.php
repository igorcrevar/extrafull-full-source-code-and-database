<?php
/*=============================================================================
|| ##################################################################
||	Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||
|| ##################################################################
=============================================================================*/
defined( 'CREW' ) or die( 'Restricted access' );
class EventsModelEvent extends JModel
{  	
	var $id;

  function myAttend($userid){
  	$db = &Database::getInstance();
  	$sql = "SELECT user_id FROM #__events_attend WHERE event_id=$this->id AND user_id=$userid";
  	$db->setQuery($sql);
  	if ( !intval($db->loadResult()) ){
  		$add = 1;
  	  $sql = "INSERT INTO #__events_attend VALUES($this->id,$userid)";
  	}  
  	else{
  		$add = 2;
  	  $sql = "DELETE FROM #__events_attend WHERE event_id=$this->id AND user_id=$userid";
  	}
  	$db->setQuery($sql);
  	if ( $db->query() ){
  		return $add;
  	}
 	  return 0;
  }  
	
	function whoAttend(){
		$query = 'SELECT b.id,b.username,b.gender FROM #__events_attend JOIN #__users AS b ON user_id=b.id WHERE event_id='.$this->id.' ORDER BY gender DESC';
		return $this->_getList( $query );  	
	}
  
  function loadEvent( $limit, $limitstart ){
  	$db = &Database::getInstance();
  	$sql = "SELECT a.*,b.username FROM #__events AS a LEFT JOIN #__users AS b ON b.id=a.user_id WHERE a.id=$this->id";
  	$db->setQuery( $sql );  	
  	$event = $db->loadObject();
 	  $query = 'select a.*,b.username from #__comments AS a JOIN #__users AS b ON b.id=a.who_id WHERE a.type=2 AND a.type_id='.$this->id.' ORDER BY a.id DESC';
  	$event->comms = $this->_getList( $query, $limitstart, $limit );
  	if ( !$event->comms ) $event->comms = array();
  	$sql = "SELECT count(id) FROM #__comments WHERE type=2 AND type_id=$this->id";
  	$db->setQuery( $sql );  	
  	$event->cnt = intval( $db->loadResult() );
		return $event;
  }
}
?>	