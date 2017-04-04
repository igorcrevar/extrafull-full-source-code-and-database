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
class MembersModelProfile extends JModel
{ 
	function getPrivateGalleries($id)
	{
		$query = "SELECT id,name FROM #__photo_events WHERE a_id=$id AND published>1 AND image_count>0";
	  $rows = $this->_getList( $query );
		return $rows;
	}
    function getPhotoCommentsCnt($id)
    {
       $db = $this->getDBO();
       $query = "SELECT count(*) FROM #__photo_comments WHERE user_id=$id";
       $db->setQuery($query);
       return $db->loadResult();
    }
	function userLastForumMsgs($id)
	{
		$query = 'SELECT id,SUBSTRING(message,1,70) as message FROM #__forum_posts WHERE who_id='.$id.' ORDER BY id DESC LIMIT 5';
	  $rows = $this->_getList( $query );
		return $rows;
	} 	 

	function loadLocations($id)
	{
		$query = "SELECT b.name,a.location_id AS id FROM #__members_locations AS a JOIN #__photo_locations AS b ON b.id=a.location_id WHERE a.user_id=$id ORDER BY b.name ASC";
	  $rows = $this->_getList( $query );
		return $rows;
	} 	 
	function loadFriends($id)
	{		
		$query = "SELECT a.*,b.username,count(c.userid) AS cnt FROM #__members_friends AS a JOIN #__users AS b ON a.id2=b.id LEFT JOIN #__session AS c ON c.userid=a.id2 WHERE a.id1=$id GROUP BY a.id2 ORDER BY b.username ASC";
	  $rows = $this->_getList( $query );
		return $rows;
	}
	function userLastComments($id)
	{
		$query = "SELECT a.comment,b.file_name,a.image_id,b.event_id FROM #__photo_comments AS a JOIN #__photo_images AS b ON b.id=a.image_id WHERE a.user_id=$id ORDER BY a.id DESC LIMIT 5";
	  $rows = $this->_getList( $query );
		return $rows;
  }
  function getCommentsCnt($id)
  {
  	$db = JFactory::getDBO();
  	$query="SELECT count(from_id) FROM #__members_comments WHERE who_id=$id";
  	$db->setQuery( $query );
  	return $db->loadResult();
  }
	function loadComments($id,$limitstart,$limit)
	{
		$query = "SELECT a.*,b.username FROM #__members_comments AS a LEFT JOIN #__users AS b ON b.id=a.from_id WHERE a.who_id=$id ORDER BY a.id DESC";
	  $rows = $this->_getList( $query, $limitstart,$limit );
		return $rows;
	}	
	function load($id)
	{
		$db = $this->getDBO();
		$user = &User::getInstance();
		$myid = $user->id;
		$ok = 1;
  	if ($id!=$myid && $user->gid>=18){
	    $time = time();	  
	    $query = 'REPLACE INTO #__members_visit (who_id,from_id,time) VALUES ('.$id.','.$myid.','.$time.')';
	    $cnt = $db->query($query,true);
	    if ($cnt == 1){
		    $query = "UPDATE #__fb_users SET uhits=uhits+1 WHERE userid=$id";
		    $db->setQuery($query);
		    $ok = $db->query();
	    }
	  }  	   
		$row = array();
	  if ( $ok )
		{
			//$query = "SELECT a.*,b.*,c.time AS cnt FROM #__users AS a JOIN #__fb_users AS b ON a.id=b.userid LEFT JOIN #__sessions AS c ON c.userid=a.id WHERE a.id=$id GROUP BY a.id,b.userid";
			$query = "SELECT a.*,b.*,c.time AS cnt FROM #__users AS a JOIN #__fb_users AS b ON b.userid=a.id LEFT JOIN #__sessions AS c ON c.userid=a.id WHERE a.id=$id";
			$db->setQuery($query);
			$row = $db->loadObject();
			if (!$row) {
			   return $row;
			}
			
			//lover 
			if ( $row->lover_id > 1 ){
				$db->setQuery('SELECT name FROM #__users WHERE id='.$row->lover_id);
				$lover = $db->loadResult();
				$row->lover_name = !empty($lover) ? $lover : '';
			}
			//end lover
			if ( $user->gid >= 18 ){
				$query = "SELECT vote FROM #__members_karma WHERE who_id=$id AND from_id=$myid";
				$db->setQuery($query);
				$row->myKarma = (int)$db->loadResult();
  			/*load friends*/
  			$friends = $row->params;
  			$fids = array();
  			$top = array();
  			if ($friends != ''){
  				$where = '';
  				$ids = explode(',',$friends);	
  				foreach ($ids as $fid){					
  					$fid = intval($fid);
  					if ($fid < 20) continue;
  					if ($where != '') $where .= ',';
  					$where .= $fid;
  					$fids[] = $fid;
  				}
  			}	
  			if ( count($fids) ){
  				$where = 'b.id IN ('.$where.')';
  				$query = "SELECT b.id,b.username,b.name,b.avatar,b.birthdate,b.gender,d.time AS cnt FROM #__users AS b LEFT JOIN #__sessions AS d ON b.id=d.userid WHERE $where";// GROUP BY b.id,b.username,c.avatar,c.gender";
  				$topPriv = $this->_getList( $query );
    			foreach ($fids as $value){
    				for ($i=0;$i< count($topPriv) && $value != $topPriv[$i]->id;++$i);
    				$top[] = &$topPriv[$i];
    			}
  			}
  			$row->tfriends = $top;
			}
			else{
				$row->myKarma = 0;
				$row->tfriends = array();
			}
			
		}
		return $row;
	}
		
	function comment( $id )
	{
		$db = $this->getDBO();
		$user = &JFactory::getUser();
		$comment = JRequest::getString('comment','');
		if ( $user->gid < 18 || !isset($comment) || JString::strlen($comment) < 3 || JString::strlen($comment) > 600 )
		  return 0;
		$myid = $user->id;
		$comment = $db->Quote($comment);
		$query = "INSERT INTO #__members_comments (who_id,from_id,comment,date) values ($id,$myid,$comment,now())";		
		$db->setQuery( $query );
		return $db->query();
	}
	
	function deleteComment()
	{
		$db = $this->getDBO();
		$user = &JFactory::getUser();
		$cid = JRequest::getInt('c_id');
		if ( $user->gid < 18 )
		  return 0;
		$myid = $user->id;
		$query = "DELETE FROM #__members_comments WHERE id=$cid AND (who_id=$myid OR from_id=$myid)";
		$db->setQuery( $query );
		return $db->query();
	}
	
	function privateMsg()
	{
		 $db = $this->getDBO();
		 $user = &JFactory::getUser();
		 if ( $user->gid < 18 )
		  return 0;
		 $from = $user->id;
		 $who = JRequest::getInt('id',0);
		 $subject = JRequest::getString('subject', '');
		 $text = JRequest::getString('text', '');
		 if (JString::strlen($subject) < 2 || JString::strlen($text) < 3 ||
		 JString::strlen($subject) > 200 || JString::strlen($text) > intval($user->id == 68) * 3000 + 3000){
		 	 return 0;
		 }
		 $subject = $db->Quote($subject);
		 $text = $db->Quote($text);
		 $query = "INSERT INTO #__jim (who_id,from_id,outbox,date,readstate,subject,message) VALUES ($who,$from,1,now(),0,$subject,$text)";
		 $db->setQuery( $query );
		 return $db->query();
	}
	
	function addKarma($who_id,$vote){
		$user = &JFactory::getUser();
		$abs = abs($vote);
		if ( $user->gid < 18 || ($abs != 1 && $abs != 2))
		  return 0;
		$from_id = $user->id;
		$db = $this->getDBO();		
		$query = "SELECT vote FROM #__members_karma WHERE who_id=$who_id AND from_id=$from_id"; 
		$db->setQuery($query);
		$old = (int)$db->loadResult();
		if (isset($old) && abs($old)==1)
		{
		  $_v= -$old;
		  $vote = -($old*2);
		  $query = "UPDATE #__members_karma SET vote=$_v WHERE who_id=$who_id AND from_id=$from_id;";		  
		  $new = 0;
		}
		else{ 			
		  $query = "INSERT INTO #__members_karma (who_id,from_id,vote) VALUES ($who_id,$from_id,$vote);";
		  $new = 1;
		}  
		$db->setQuery($query);  
		if ($db->query()){
		   $query = "UPDATE #__fb_users SET karma=karma+$vote,karma_time=karma_time+$new WHERE userid=$who_id";
		   $db->setQuery($query);
		   if ($db->query()){			
		   	  return '{ "vote":'.$vote.', "inc":'.$new.'}';
		   }
		}		
		return null; 
	}	
	
	function loadDescs($id,$friends){
		$query = 'SELECT a.*,b.username FROM #__members_descs AS a JOIN #__users AS b ON b.id=a.from_id WHERE a.who_id='.$id;		
		$rows = $this->_getList($query);
		//echo str_replace('#__','jos_',$query);
		$res = array();
		if (count($rows)){
			 foreach ($rows as $row){
			 	  if (in_array($row->from_id,$friends)){
			 	  	  $res[] = $row;
			 	  }
			 }
		}
		return $res;
	}
	
	function writeDesc($myid,$hisid,$txt){
		$db = $this->getDBO();
		$txt = $db->getEscaped($txt);
		$query = 'REPLACE INTO #__members_descs SET who_id='.$hisid.',from_id='.$myid.',txt=\''.$txt.'\'';
		$db->setQuery($query);
		if ($db->query()){
			$tm = time();
			$user = &User::getInstance();
			$query = 'REPLACE INTO #__changes SET who_id='.$hisid.',what=4,time='.$tm.',tag=\' opisan od '.JHTML::profileLink($myid,$user->username).' kao : '.$txt.'\'';
			$db->setQuery($query);
			$db->query();
		}
	}
}
?>