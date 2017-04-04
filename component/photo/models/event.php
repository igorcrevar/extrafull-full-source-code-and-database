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
 
class ModelPhotoEvent extends JModel
{  
	var $event_id;
  function getImages( $limitstart, $limit )
  {
  	 $query = 'select * from #__photo_images where event_id='.$this->event_id;
  	 $rows = $this->_getList( $query, $limitstart, $limit );
		 return $rows;
  }	
  
  function getImagesCount()
  {
  	 $query = 'select count(*) from #__photo_images where event_id='.$this->event_id;
  	 $db = $this->getDBO();
  	 $db->setQuery( $query );
		 return $db->loadResult();
  }	
  
  function getEvent()
  {
  	 $query = 'SELECT a.*, b.name AS location, c.username AS username FROM #__photo_events AS a LEFT JOIN #__photo_locations AS b ON a.l_id=b.id JOIN #__users AS c ON c.id=a.a_id WHERE a.id='.$this->event_id;
  	 $rows = $this->_getList( $query );
		 return $rows[0];
  }
  
}
?>