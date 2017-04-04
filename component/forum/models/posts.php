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

class ModelForumPosts extends JModel{  	
	var $id;
	var $title;
	var $userId;
	function getMy(){
		$this->title = 'Moje poruke na temama';
  	return 'SELECT a.*,c.name,c.gender,c.birthdate FROM #__forum_posts AS a JOIN #__users AS c ON a.who_id=c.id WHERE a.who_id='.$this->userId.' ORDER BY a.id DESC';
	}	
 function getMyCnt(){
 	 return 'SELECT count(*) FROM #__forum_posts WHERE who_id='.$this->userId;
  }
	function getMyStarted(){
		$this->title = 'Moje zapocete teme';
  	return 'SELECT a.*,c.name,c.gender,c.birthdate FROM #__forum_posts AS a JOIN #__users AS c ON a.who_id=c.id WHERE a.who_id='.$this->userId.' AND a.id=a.tid ORDER BY a.id DESC';
	}	
 function getMyStartedCnt(){
 	 return 'SELECT count(*) FROM #__forum_posts WHERE who_id='.$this->userId.' AND id=tid';
  }
  
  function getPosts($limitstart,$limit){  
  	switch ($this->id){
  		case 1: $query = $this->getMy(); break;
  		default: $query = $this->getMyStarted();
  	}	
  	return $this->_getList($query, $limitstart,$limit);
  }
  function getPostsCnt(){  
  	switch ($this->id){
  		case 1: $query = $this->getMyCnt(); break;
  		default: $query = $this->getMyStartedCnt();
  	}	
  	$this->_db->setQuery($query);
  	return intval($this->_db->loadResult());
  }
}
?>