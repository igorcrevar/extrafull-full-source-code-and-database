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

class EventsModelList extends JModel
{  	
	function loadEventsSQL(){
		$sql='SELECT DAY(a.date) AS day,CONCAT(a.location_name,\' - \',a.name) AS name FROM jos_events as a WHERE ';
		return $sql;
  }
  
  function loadEvents($date){
  	$sql = "SELECT id,name,location_name,image,date FROM jos_events WHERE date='$date' ORDER BY name";
  	$rows = $this->_getList($sql);
  	return $rows;
  }

  function loadEvents3($year,$month){
  	$date = $year.'-'.$month;
  	$sql = "SELECT id,name,location_name,image,date FROM jos_events WHERE date>='$date-1' AND date<='$date-31' ORDER BY date";
  	$rows = $this->_getList($sql);
  	return $rows;
  }
  
  function loadEvents2($old){
  	$cmp = $old == 0 ? '>=' : '<' ;
  	$asc = $old ? 'DESC' : 'ASC';
  	$date = $cmp."'".date('Y-m-d')."'";
  	$sql = "SELECT id,name,location_name,image,date FROM jos_events WHERE date$date ORDER BY date ".$asc.',name';
  	$rows = $this->_getList($sql);
  	return $rows;
  }  
}
?>	