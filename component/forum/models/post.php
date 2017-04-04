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

class ModelForumPost extends JModel{  	
  function getMsg($id,$change){
  	$db = &Database::getInstance();
  	
  	if ($change){
  		$user = &User::getInstance();
  		$query = 'SELECT tid,subject,message FROM #__forum_posts WHERE id='.$id;
  	}
  	else{
  		$query = 'SELECT tid,subject,username,message FROM #__forum_posts WHERE id='.$id;
  	}
  	$db->setQuery( $query );
  	return $db->loadObject();
  }
  
  function getMsg2($id){
  	$db = &Database::getInstance();  	
 		$query = 'SELECT subject FROM #__forum_topics WHERE id='.$id;
  	$db->setQuery( $query );
  	return $db->loadResult();
  }  
}
?>