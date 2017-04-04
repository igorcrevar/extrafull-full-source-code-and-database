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
 
class MembersModelUserList extends JModel
{  
	function get($limitstart,$limit)
	{		  
		  $username = JRequest::getString( 'username', '' );		  
		  $birth_year = JRequest::getInt( 'birth_year', 0 );
		  $birth_option = JRequest::getInt( 'birth_option', 0 ); //= > <
		  $picture = JRequest::getInt( 'picture', 0 );
		  $sort = JRequest::getInt( 'sort', 0 );
		  $location = JRequest::getString( 'location', '' ); 
		  $photo_locations = JRequest::getInt( 'photo_locations' );
		  $name = JRequest::getString('namesur', '');
		  $gender = JRequest::getInt( 'gender', -1 );
		  $love = JRequest::getInt( 'love', -1 );		  
		  $db = $this->getDBO();		  
		  $baned = JRequest::getInt('baned',0);
		  
		  $cond = '';
		  switch ( $sort )
		  {
		  	case 0:
		  	  $order = ' ORDER BY u.id DESC';  
		  	  break;		  	
		  	case 1:
		  	  $order = ' ORDER BY u.birthdate';  
		  	  break;
		  	case 2:
		  	  $order = ' ORDER BY u.lastvisitdate DESC';  
		  	  break;
		  	default:
		  	  $order = ' ORDER BY u.username';
		  }
		  if ( JString::strlen($username) >= 3 )
		  {
		  	$username = $db->getEscaped($username);
		  	$cond = "u.username LIKE '%$username%' AND "; 
		  }
		  if ( JString::strlen($name) >= 3 )
		  {
		  	$name = $db->getEscaped($name);
		  	$cond .= "u.name LIKE '%$name%' AND "; 
		  }
		  if ( isset($gender)  &&  $gender >= 1 )
		  {
		  	 if ( $gender == 2 )
		  	 	 $cond .= 'u.gender=2 AND ';
		  	 else	  
		  	   $cond .= 'u.gender=1 AND ';
		  }		  
		  if ( $birth_year != 0 )
		  {
		  	  switch ($birth_option)
		  	  {
		  	  	case 1:$tmp = '<';break;
		  	  	case 2:$tmp = '>';break;
		  	  	default:$tmp = '=';
		  	  }
		  	  $cond .= "YEAR(u.birthdate) $tmp $birth_year AND ";
		  }
		  if ( $picture == 1 )
		  {
		  	$cond .= 'LENGTH(u.avatar)>0 AND ';
		  }
		  if ( JString::strlen($location) >= 3 )
		  {
        $location = $db->getEscaped($location);
		  	$cond .= "b.location LIKE '%$location%' AND ";
		  }
			//novo muzika
			$music = isset($_REQUEST['music']) ? $_REQUEST['music'] : null;			
			$music_val = 0;	
			if ( is_array( $music) ){
				 foreach ($music as $one){			 	 
			 		 $one = intval($one);
			 	 	if (is_int($one)){
			 	 		 $music_val += $one;
			 	 	}
			 	}
			}	
			else if (isset($music)){
				 $music_val = intval($music);
			}
			if ($love>=0){
				$cond .= 'u.love='.$love.' AND ';
			}
			if ($music_val > 0){
				$cond .= '(b.music & '.$music_val.')>0 AND ';
			}	  
			if ($baned) $cond .= 'u.block=1 AND ';
			//
		  if ( isset($photo_locations) && $photo_locations >= 1 )
		  {
		  	$cond2 = "JOIN #__members_locations AS ml ON ml.user_id=u.id AND ml.location_id=$photo_locations";
		  }
		  else $cond2 = '';
		  if ( $cond != '' )
		    $cond = 'WHERE '.JString::substr( $cond, 0, JString::strlen($cond)-5 );		 
  	  $query = "SELECT count(*) FROM #__users AS u JOIN #__fb_users AS b ON b.userid=u.id $cond2 $cond";
		  $db->setQuery( $query );
  	  $list = array();
		  $list['count'] = $db->loadResult();
		  $query = "SELECT u.id,u.username,u.name,u.lastvisitDate,u.avatar,u.gender,u.birthdate,b.location FROM #__users AS u JOIN #__fb_users AS b ON b.userid=u.id $cond2 $cond $order"; 
		  $list['users'] = $this->_getList( $query, $limitstart, $limit );
		  return $list;
	}  
	
	function getFriends($fid, $limitstart, $limit){
		  $gender = JRequest::getInt( 'gender', -1 );
		  $name = JRequest::getString( 'name' );
		  $love = JRequest::getInt( 'love', -1 );		
			$db = $this->getDBO();
		  $cond1 = $gender > 0 ? 'AND u.gender='.$gender : '';
		  $cond2 = $love >= 0 ? 'AND b.love='.$love : '';
		  if ( !empty($name)  && JString::strlen($name) >= 3 ){
		  	$cond1 .= " AND u.name LIKE '%$name%'";
		  }		  

			$query = 'SELECT count(*) FROM #__members_friends';
			if ( !empty($cond1) ){
				$query .= ' JOIN #__users AS u ON id2=u.id '.$cond1;
			}
			if ( !empty($cond2) ){
				$query .= ' JOIN #__fb_users AS b ON id2=b.userid '.$cond2;
			}
			$query .= ' WHERE id1='.$fid;
			$db->setQuery( $query );
		  $list = array();
		  $list['count'] = $db->loadResult();

		  $query = "SELECT u.id,u.username,u.name,u.lastvisitDate,u.avatar,u.gender,u.birthdate,b.location FROM #__members_friends AS mf JOIN #__users AS u ON mf.id2=u.id $cond1 JOIN #__fb_users AS b ON mf.id2=b.userid $cond2 WHERE id1=$fid ORDER BY u.name ASC"; 
		  $list['users'] = $this->_getList( $query, $limitstart, $limit );
		  return $list;
	}
	
	function getMutual($uid, $fid, $limitstart, $limit ){
		  $gender = JRequest::getInt( 'gender', -1 );
		  $name = JRequest::getString( 'name' );
			$db = $this->getDBO();
		  $cond1 = $gender > 0 ? 'AND u.gender='.$gender : '';
		  if ( !empty($name)  && JString::strlen($name) >= 3 ){
		  	$cond1 .= " AND u.name LIKE '%$name%'";
		  }		  
			$query = 'SELECT count(*) FROM #__members_friends AS mf JOIN #__members_friends AS mu ON mf.id2=mu.id2 AND mu.id1='.$fid;
			if ( !empty($cond1) ){
				$query .= ' JOIN #__users AS u ON mf.id2=u.id '.$cond1;
			}
			$query .= ' WHERE mf.id1='.$uid;
			$db->setQuery( $query );
		  $list = array();
		  $list['count'] = $db->loadResult();
		  $query = "SELECT u.id,u.username,u.name,u.lastvisitDate,u.avatar,u.gender,u.birthdate,b.location FROM #__members_friends AS mf JOIN #__members_friends AS mu ON mf.id2=mu.id2 AND mu.id1=$fid JOIN #__users AS u ON mf.id2=u.id $cond1 JOIN #__fb_users AS b ON mf.id2=b.userid $cond2 WHERE mf.id1=$uid ORDER BY u.name ASC"; 
		  $list['users'] = $this->_getList( $query, $limitstart, $limit );		
		  return $list;
	}
	
	function loadPhotoLocations()
	{
		 $query = "SELECT id,name FROM #__photo_locations ORDER BY name";
		 $rows = $this->_getList( $query );
		 $row = array(new stdClass());
		 $row[0]->id = 0;
		 $row[0]->name = 'Bilo gde...';
		 $rows = array_merge($row,$rows);
		 return $rows;
	}
}
?>