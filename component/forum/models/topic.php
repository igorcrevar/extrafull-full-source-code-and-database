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

class ModelForumTopic extends JModel{  	
	var $tid;
	var $topic;
	var $isMod;
  function getPosts($limitstart,$limit){  	
  	$query = 'SELECT a.*,b.posts,b.signature,b.location,c.name,c.gender,c.birthdate FROM #__forum_posts AS a LEFT JOIN #__fb_users AS b ON b.userid=a.who_id LEFT JOIN #__users AS c ON b.userid=c.id WHERE a.tid='.$this->tid;
  	return $this->_getList($query, $limitstart,$limit);
  }
  function get(){
  	$db = &Database::getInstance();
  	$query = 'SELECT a.cid,a.subject,a.sticky,a.locked,a.replies,b.group,b.name,b.moderators FROM #__forum_topics AS a JOIN #__forum_cats AS b ON b.id=a.cid WHERE a.id='.$this->tid;
  	$db->setQuery($query);
  	$this->topic = $db->loadObject();
  	if (!$this->topic) return;
  	$this->topic->id = $this->tid;		
  }	
  function getCnt(){
  	 $query = 'SELECT count(*) FROM #__forum_posts WHERE tid='.$this->tid;
  	 $db = &Database::getInstance();
  	 $db->setQuery( $query );
  	 return  $db->loadResult();
  }
  function rights(){
 		$user = &User::getInstance();
 		$mods = getModers($this->topic->moderators);
  	$this->isMod = in_array( $user->id, $mods );
		return 1 + intval($user->gid >= 18  &&  $this->topic->locked == 0);
  }  	
}
?>