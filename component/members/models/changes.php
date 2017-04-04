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
class MembersModelChanges extends JModel
{ 
  function getAll($limitstart,$limit){
  	 $query = 'SELECT b.*,a.username FROM #__users AS a JOIN #__changes AS b ON b.who_id=a.id ORDER BY b.time DESC';
  	 $rows = $this->_getList($query,$limitstart,$limit);
  	 return $rows;
  }

  function get($limitstart,$limit, $area = 3){
  	 if ( $area == 3 ){
  	 	  $query = 'SELECT b.id AS tag,b.event_id,3 AS what,b.private AS who_id,b.time,b.file_name,a.username,a.avatar FROM #__photo_images AS b JOIN #__users AS a ON b.private=a.id WHERE b.private>30 ORDER BY b.id DESC';
  	 }
  	 else{
  	 		$query = 'SELECT b.*,a.username,a.avatar FROM #__users AS a JOIN #__changes AS b ON b.who_id=a.id WHERE what='.$area.' ORDER BY b.time DESC';
  	 }
  	 $rows = $this->_getList($query,$limitstart,$limit);
  	 return $rows;
  }

  function getMyAll($id,$limitstart,$limit){  	 
  	 $query = 'SELECT b.*,a.username FROM #__members_friends AS c LEFT JOIN #__users AS a ON a.id=c.id2 AND c.id1='.$id.' JOIN #__changes AS b ON b.who_id=a.id ORDER BY b.time DESC';
  	 return $this->_getList($query,$limitstart,$limit);
  }

  function getMy($id,$limitstart,$limit, $area){  	
  	 if ( $area == 3 ){
  	 	  $query = 'SELECT b.id AS tag,b.event_id,3 AS what,b.private AS who_id,b.time,b.file_name,a.username FROM #__members_friends AS c JOIN #__photo_images AS b ON c.id1='.$id.' AND c.id2=b.private JOIN #__users AS a ON c.id2=a.id WHERE b.private>30 ORDER BY b.id DESC';
  	 }
  	 else{ 
  	 		$query = 'SELECT b.*,a.username,a.avatar FROM #__members_friends AS c LEFT JOIN #__users AS a ON a.id=c.id2 AND c.id1='.$id.' JOIN #__changes AS b ON b.who_id=a.id WHERE what='.$area.' ORDER BY b.time DESC';
  	 	}
  	 return $this->_getList($query,$limitstart,$limit);
  }

}
?>