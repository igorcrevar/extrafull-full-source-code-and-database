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
 
class MembersModelRequest extends JModel
{  	
	function loadFriends()
	{
		$user = &User::getInstance();
		$id = $user->id;
		$query = "SELECT a.id2,b.name,b.username FROM #__members_friends AS a JOIN #__users AS b ON a.id2=b.id WHERE a.id1=$id ORDER BY b.username ASC";
	  $rows = $this->_getList( $query );
		return $rows;
	}
	
	function loadLover(){
		$user = &User::getInstance();
		$id = $user->id;
		$query = "SELECT b.love,a.id,a.name FROM #__fb_users AS b JOIN #__users AS a ON b.lover_id=a.id WHERE userid=$id";
	  $db = $this->getDBO();
	  $db->setQuery($query);
		return $db->loadObject();
	}
			
	function deleteFriend($id2){
		$db = $this->getDBO();
		$user = &User::getInstance();
	  if ( $user->gid < 18 )
		  return 0;
		$id1 = $user->id;
		$query = "SELECT lover_id FROM #__fb_users WHERE userid=$id1";
		$db->setQuery( $query );
		$lovers = intval( $db->loadResult() ) == $id2;
		if ( $lovers ) return 666;
		$query = "DELETE FROM #__members_friends WHERE (id1=$id1 AND id2=$id2) OR (id1=$id2 AND id2=$id1);";
		$db->setQuery( $query );
		if (!$db->query()) return 0;
    $query = "UPDATE #__fb_users SET friends=friends-1 WHERE (userid=$id1 OR userid=$id2)";
	  $db->setQuery( $query );
		$db->query();
		
		$query = "SELECT params,id FROM #__users WHERE id=$id1 OR id=$id2";
		$rows = $this->_getList($query);		
		for ($i=0;$i < count($rows);++$i){
			 $row = &$rows[$i];
		 	 if ($row->id == $id1) $row->id2 = $id2; 
		 	 else $row->id2 = $id1;
			 $friends = $row->params;
			 $ids = explode(',',$friends);
			 $newFriends = '';	
			 $change  = false;
			 for ($j=0;$j< count($ids);++$j){
			 	 $id = $ids[$j];
			 	 if ($row->id2 != $id){
			 	 	 if ($newFriends != '') 
			 	 	   $newFriends .= ',';
			 	   $newFriends .= $id;
			 	 }
			 	 else $change = true;
			 }		
			 if ($change){
			   $query = "UPDATE #__users SET params='$newFriends' WHERE id=$row->id";
				 $db->setQuery( $query );
				 $db->query();				 
			 }
		} 
		return 1;
	}
	
	function deleteLover(){
		$user = &User::getInstance();
		$uid = $user->id;
		if ( $uid < 18 ) return 666; //non authorized
		$db = $this->getDBO();
		$query = "SELECT lover_id FROM #__fb_users WHERE userid=$uid";
		$db->setQuery( $query );
		if ( ! ($id = intval($db->loadResult()) ) ) return 0;		
		$query = "DELETE FROM #__lovers WHERE id1=$id OR id1=$uid";
		$db->setQuery( $query );
		if ( $db->query() ){
			$query = "UPDATE #__fb_users SET lover_id=1 WHERE userid IN ($id,$uid)";
			$db->setQuery( $query );
			$db->query();
			$query = "DELETE FROM #__votes WHERE type=2 AND type_id IN ($id,$uid)";
			$db->setQuery( $query );
			$db->query();
			return 1;
		}
		return 0;
	}	
	
	function request($to_id, $desc, $type = 1, $type_id = 0 ){
		$db = $this->getDBO();
		$user = &User::getInstance();
		if ( $user->gid < 18 )
		  return 0;
		$from_id = $user->id; //ko sam
		if ( $type == 2 ){
			$db->setQuery( "SELECT count(id) FROM #__request WHERE (id1=$from_id OR (id1=$to_id AND id2=$from_id)) AND type=2" );
		}
		else{
			$db->setQuery( "SELECT count(id) FROM #__request WHERE ((id1=$from_id AND id2=$to_id) OR (id1=$to_id AND id2=$from_id)) AND type=$type" );
		}
		$cnt = intval( $db->loadResult() );
		if ( $cnt ) return -2; //vec postoji request
		
		switch ($type){
			case 2:
				$db->setQuery( "SELECT id1 FROM #__members_friends WHERE id1=$from_id AND id2=$to_id");
				if ( intval($db->loadResult()) != $from_id ){  //niste prijatelji
					return -4;
				}
				$db->setQuery( "SELECT count(userid) FROM #__fb_users WHERE userid IN ($from_id,$to_id) AND lover_id>1");
				if ( intval( $db->loadResult() ) ){  //vec je u vezi ili si ti u vezi
		  		return -1;
		  	}
		  	break;
			default:
				$db->setQuery( "SELECT id1 FROM #__members_friends WHERE id1=$from_id AND id2=$to_id");
				if ( intval($db->loadResult()) == $from_id ){  //vec ste prijatelji
		  		return -1;
		  	}
				break;
		}
		$desc = $db->Quote($desc);
		$time = time();
		$query = "INSERT INTO #__request VALUES (0,$from_id,$to_id,$desc,$type,$type_id,$time)";
		$db->setQuery( $query );		
	  return $db->query();
	}	
	
	function pending($request_id,$do){
		$db = $this->getDBO();
		$user = &User::getInstance();
		if ( $user->gid < 18 )
		  return false;
		$userid = $user->id;
		switch ($do)
		{
			case 'refuse'://odbijam
			  $db->setQuery( 'DELETE FROM #__request WHERE id='.$request_id.' AND id2='.$userid );
 				return $db->query();
 			case 'cancel'://ponistavam
			  $db->setQuery( 'DELETE FROM #__request WHERE id='.$request_id.' AND id1='.$userid );
			  return $db->query();
			default:
				$db->setQuery( 'SELECT * FROM #__request WHERE id='.$request_id );
				$req = $db->loadObject();
				if ( !isset($req) || $req->id2 != $userid ) return false; //nije tvoj request
			  $oid = $req->id1;
			  $typeid = $req->type_id;
			  if ( $req->type == 2 ){ //love			  	
					$db->setQuery( "SELECT count(lover_id) FROM #__fb_users WHERE userid IN ($userid,$oid) AND lover_id>1" );
					//ako je vec neko u vezi ne moz ponovo :) - raskini prvo vezu
					if ( intval( $db->loadResult() ) ) return 'Osoba je vec u vezi ili si ti u vezi';
					$db->setQuery( "SELECT id2 FROM #__members_friends WHERE id1=$userid AND id2=$oid");
					if ( intval($db->loadResult()) != $oid ) return false; //niste prijatelji
					$db->setQuery( 'DELETE FROM #__request WHERE id='.$request_id );
			  	if ( !$db->query() ) return false;		
					$db->setQuery( 'UPDATE #__fb_users SET lover_id='.$userid.' WHERE userid='.$oid );
					if ( !$db->query() ) return false;
					$db->setQuery( 'UPDATE #__fb_users SET lover_id='.$oid.' WHERE userid='.$userid );
					if ( !$db->query() ) return false;
					$time = time();
					$db->setQuery( "INSERT INTO #__lovers VALUES ($oid,$userid,$time,0,0)" );
					if ( !$db->query() ) return false;			  	
				}
				else{ //friend
				  $db->setQuery( 'DELETE FROM #__request WHERE id='.$request_id );
				  if ( !$db->query() ) return false;				  
			  	$query = "INSERT INTO #__members_friends (id1,id2,status) VALUES ($userid,$oid,1),($oid,$userid,1)";
			  	$db->setQuery( $query );
			  	if ( !$db->query() ) return false;
			  	$query = "UPDATE #__fb_users SET friends=friends+1 WHERE userid=$userid OR userid=$oid;";
			  	$db->setQuery( $query );		
	  			return $db->query();//$db->queryBatch(true,true);		
	  		}
		}	 
	}
	
	function loadReqPen()
	{
		$user = &User::getInstance();
		$id = $user->id;
		$query = "SELECT a.*,b.username,b.name FROM #__request AS a JOIN #__users AS b ON a.id1=b.id WHERE a.id2=$id";
		$rows = array();
	  $rows['req'] = $this->_getList( $query );
	  $query = "SELECT a.*,b.username,b.name FROM #__request AS a JOIN #__users AS b ON a.id2=b.id WHERE a.id1=$id";
	  $rows['pen'] = $this->_getList( $query );	  
		return $rows;
	}
			

}	
?>	