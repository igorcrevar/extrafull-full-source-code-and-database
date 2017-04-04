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
class EventsModelNew extends JModel
{ 
	function loadEvent($id){
		$db = $this->getDBO();
		$db->setQuery('SELECT * FROM #__events WHERE id='.$id);
		return $db->loadObject();
	} 
	function loadLocation($id){
		$db = $this->getDBO();
		$db->setQuery('SELECT * FROM #__photo_locations WHERE id='.$id);
		return $db->loadObject();
	}	
  function loadLocations(){
  	$sql='SELECT name FROM #__photo_locations ORDER BY name ASC';
  	$rows = $this->_getList($sql);
  	return $rows;
  }
  function addLocation(){
  	$db = $this->getDBO();
  	$user = &User::getInstance();
  	if ( $user->gid < 18 ) return 0;
  	$id = JRequest::getInt( 'id', 0, 'POST' );
  	$l_name = JRequest::getString( 'l_name', '', 'POST' );
	  $l_name = $db->Quote($l_name);
	  if ( !MyRights::isEventsModerator($user->id) ){
	  	$db->setQuery( 'SELECT count(id) FROM #__photo_locations WHERE user_id='.$user->id );
	  	$cnt = intval( $db->loadResult() );
	  	if ( $cnt == 2 ){
	  		return 4;
	  	}
	  }
	  //$tmpName = strtolower( preg_replace( '/[^a-zA-Z]+/', '', $l_name ) );
	  if ( JString::strlen($l_name)<2 ) return 0;
	  $l_street = JRequest::getString( 'l_street', '', 'POST' );
 		$l_street = $db->Quote( $l_street );
 		if ( $id )
 		  $sql="UPDATE #__photo_locations SET name=$l_name,address=$l_street WHERE id=$id";
 		else{ 		 
 			$hasPictures = ($user->id == 63  || $user->id == 72 ) ? 1 : 0;
 			$sql = "INSERT INTO #__photo_locations VALUES (null,$l_name,$l_street,$hasPictures,$user->id)";
 		}  
 		$db->setQuery($sql);
 		return intval( $db->query() );
  }

  function addEvent($year,$month,$day){
  	$user = &User::getInstance();
  	if ( $user->gid < 18 ) return false;
  	$db = $this->getDBO();
  	$id = JRequest::getInt('id',0,'POST');
  	$l_name = JRequest::getString( 'l_name', '','POST' );
	  $l_name = $db->Quote($l_name);
  	$name = JRequest::getString('name','','POST');
  	if ( JString::strlen($name) < 3 ) return false;
    $name = $db->Quote($name);
		$text = JRequest::getString('text','','POST');			
  	$text = $db->Quote($text); 
		$forum_lnk = JRequest::getString('forum_lnk','','POST');
  	$forum_lnk = $db->Quote($forum_lnk);

  	$hour = JRequest::getInt('hour',0,'POST');
  	$min = JRequest::getInt('min',0,'POST');   
  	$date = "'".$year.'-'.$month.'-'.$day."'";
  	$time = $hour*60+$min;
  	
  	require_once( JPATH_BASE.DS.'library'.DS.'image.php' );
  	$imgname = time().$user->id;
  	$path = JPATH_BASE.DS.'events'.DS.$imgname.'.jpg';
  	$paththumb = JPATH_BASE.DS.'events'.DS.$imgname.'t.jpg';
  	$image = new Image( $path, 'image' );
  	if ( $image->isOk() ){
  		//$image->thumb( 120, 90, $paththumb );
  		$size = $image->calc( 100, 75 );
  		$image->thumb( $size['w'], $size['h'], $paththumb );
  		$image->shrink( 400 );
  		$image->save( $path );
  		$image->destroy();
  	}
  	else{
  		$imgname = '';
  	}
  	
  	if ( $id > 0 ){
  		if ( !empty($imgname) ){
  	  	$this->deleteImage( '#__events', $id );
  	  	$imgname = ",image='$imgname'";
  	  }
 	  	$sql = "UPDATE #__events SET name=$name,text=$text,location_name=$l_name,date=$date,forum_link=$forum_lnk $imgname WHERE id=$id";
 	  	if ( !MyRights::isEventsModerator($user->id) ){
 	  		 $sql .= ' AND user_id='.$user->id;
 	  	}
  	}
  	else{
  	  $sql = "INSERT INTO #__events VALUES (null,$name,$text,$l_name,$date,$time,$forum_lnk,$user->id,'$imgname',0)";
  	}
  	$db->setQuery($sql);
  	return $db->query();
  }
  
  function deleteImage($table, $id){
  	$db = $this->getDBO();
  	$query = "SELECT image FROM $table WHERE id=$id";
  	$db->setQuery( $query );
  	$imagename = $db->loadResult();
  	if ( !empty($imagename) ){
  		$path = JPATH_BASE.DS.'events'.DS.$imagename.'.jpg';
	  	$paththumb = JPATH_BASE.DS.'events'.DS.$imagename.'t.jpg';
	  	unlink( $path );
	  	unlink( $paththumb );
  	}
  }
  
  
  function delete($id, $type){
  	$db = $this->getDBO();
  	if ( $type == 1 ){
  		 $table='#__events'; 
  		 $db->setQuery( 'DELETE FROM #__events_attend WHERE event_id='.$id );
  		 $db->query();
  		 $db->setQuery( 'DELETE FROM #__comments WHERE type=2 AND type_id='.$id );
  		 $db->query();
  	}else{
  		 $table = '#__photo_locations';
  	}
  	$this->deleteImage( $table, $id );
  	$query = "DELETE FROM $table WHERE id=$id";
  	$db->setQuery($query);
  	return $db->query();
  }
}
?>	