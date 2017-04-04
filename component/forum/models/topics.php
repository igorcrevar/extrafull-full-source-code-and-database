<?php
/*=============================================================================
|| ##################################################################
||	 Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||
|| ##################################################################
=============================================================================*/
 defined( 'CREW' ) or die( 'Restricted access' );

class ModelForumTopics extends JModel
{  
	var $cid;
	var $cat;
  function get($limitstart,$limit)
  {  	
  	 $query = 'SELECT * FROM #__forum_topics WHERE cid='.$this->cid.' ORDER by sticky DESC,time DESC';
		 $rows =  $this->_getList( $query, $limitstart, $limit );
		 return $rows;
  }	
  
  function getCnt(){
  	 $query = 'SELECT count(*) FROM #__forum_topics WHERE cid='.$this->cid;
  	 $db = &Database::getInstance();
  	 $db->setQuery( $query );
  	 return  $db->loadResult();
  }
  function loadCat(){
  	 $query = 'SELECT name,`group`,moderators,numTopics FROM #__forum_cats WHERE id='.$this->cid;
  	 $db = &Database::getInstance();
  	 $db->setQuery( $query );
  	 $this->cat = $db->loadObject();
  	 $this->cat->id = $this->cid;
  }
  function rights(){
  	if ($this->cat->group == 0 ){
  		$user = &User::getInstance();
  	  return 1 + intval($user->gid >= 18);
  	}
  	else{
			return 0;
  	}  	
  }
}
?>