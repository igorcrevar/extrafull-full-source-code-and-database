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
 
class ModelPhotoImages extends JModel
{  
  function getImages( $myid, $limitstart, $limit,$stat, $cnt=0 )
  {
  	 $db = &$this->getDBO();
  	 if ( $stat >= 7 ){
  	 		if (!$cnt){
  	 	  	$db->setQuery( 'SELECT max(id) FROM #__photo_images' );
  	 	  	$maxid = $db->loadResult() - 200;
  	 	  }
  	 	  else{
  	 	  	$maxid = $cnt;
  	 	  }
 	 	  	$res['cnt'] = 25;
  	 }
  	 switch ( $stat){
  	 	 case 7:
  	 	 	$res['imgs'] = $this->_getList( 'SELECT a.* FROM #__photo_images AS a WHERE a.id>='.$maxid.' ORDER BY a.number_of_views DESC', $limitstart, $limit );
  	 	 	return $res;
  	 	 case 8:
  	 	 	$res['imgs'] = $this->_getList( 'SELECT a.*, IF(a.voteCnt=0,0.00,format(a.voteSum/a.voteCnt,2)) as grade FROM #__photo_images AS a  WHERE a.id>='.$maxid.' GROUP BY a.id ORDER BY grade DESC', $limitstart, $limit );
  	 	 	return $res;
  	 	 case 9:
  	 	 	$res['imgs'] = $this->_getList( 'SELECT a.* FROM #__photo_images AS a WHERE a.id>='.$maxid.' ORDER BY a.comments DESC', $limitstart, $limit );
  	 	 	return $res;
 	 	   case 10:
			  $query = "select max(a.id) from #__photo_images as a join #__photo_events as b On b.id=a.event_id group by b.a_id order by max(a.id) DESC";
        Database::getInstance()->setQuery($query, $limitstart, $limit);
				$ids = Database::getInstance()->loadResultArray();
 	 	   	$idsString = join(',', $ids);
				$query = "SELECT a.*,b.a_id,c.username FROM #__photo_images AS a JOIN #__photo_events AS b ON b.id=a.event_id AND b.published>1 JOIN #__users AS c ON b.a_id=c.id WHERE a.id in ($idsString) ORDER BY a.id DESC";
				$res['imgs'] = $this->_getList($query);
  	   	return $res;
  	 	 case 0:
  	 	  $query1 = 'SELECT a.*,b.user_id FROM #__photo_images AS a JOIN #__photo_favourites AS b ON a.id=b.image_id WHERE b.user_id='.$myid.' ORDER BY a.id DESC';
  	 	  $query2 = 'SELECT count(*) FROM #__photo_images AS a JOIN #__photo_favourites AS b ON a.id=b.image_id WHERE b.user_id='.$myid;
  	 	 break; 
 	 	   case 1:
  	 	  $query1 = 'SELECT distinct a.* FROM #__photo_images AS a JOIN #__photo_comments AS b ON a.id=b.image_id WHERE b.user_id='.$myid.' ORDER BY a.id DESC';
  	 	  $query2 = 'SELECT count(DISTINCT a.id) FROM #__photo_images AS a JOIN #__photo_comments AS b ON a.id=b.image_id WHERE b.user_id='.$myid;
  	 	 break; 
 	 	   case 2:
  	 	  $query1 = 'SELECT a.*,b.grade FROM #__photo_images AS a JOIN #__photo_votes AS b ON a.id=b.image_id WHERE b.user_id='.$myid.' ORDER BY b.grade DESC,a.id DESC';
  	 	  $query2 = 'SELECT count(*) FROM #__photo_images AS a JOIN #__photo_votes AS b ON a.id=b.image_id WHERE b.user_id='.$myid;
  	 	 break; 
 	 	   /*case 3:
 	 	    if ($gender==1) $join = 'JOIN #__fb_users AS d ON d.userid=b.a_id AND d.gender=1';
 	 	    else if ($gender==2) $join = 'JOIN #__fb_users AS d ON d.userid=b.a_id AND d.gender>1';
  	   	$query1 = "SELECT a.*,c.username,c.id AS userid FROM #__photo_images AS a JOIN #__photo_events AS b ON a.event_id=b.id AND b.published>1 $join JOIN #__users AS c ON b.a_id=c.id ORDER BY a.id DESC";
  	 	  $query2 = "SELECT count(*) FROM #__photo_images AS a JOIN #__photo_events AS b ON a.event_id=b.id AND b.published>1 $join";
  	 	  break;*/
  	 	 case 6:
  	   	$query1 = "SELECT a.id2 as id, b.username,b.avatar FROM #__members_friends AS a LEFT JOIN #__users AS b ON a.id2=b.id  WHERE a.id1=$myid AND a.status=1 ORDER BY b.username ASC";
  	 	  $query2 = "SELECT count(*) FROM #__members_friends WHERE id1=$myid AND status=1";  	 	  
		 } 
  	 $res['imgs'] = $this->_getList( $query1, $limitstart, $limit );
  	 if ( !$cnt ){
  	 		$db->setQuery( $query2 );
	   		$res['cnt'] = $db->loadResult();
	   }
	   

		 return $res;
  }	
}
?>