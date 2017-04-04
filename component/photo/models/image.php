<?php
 defined( '_JEXEC' ) or die( 'Restricted access' );

class ModelPhotoImage extends JModel
{
	var $image_id;
	var $user_id = -1;
	var $image = null;
	var $privileges = 0;
		
  function getComments()
  {
  	 $query = 'SELECT a.id,a.comment,a.datetime as date,a.user_id,b.username AS user_name FROM #__photo_comments AS a JOIN #__users AS b on b.id=a.user_id where image_id='.$this->image_id.' ORDER BY a.id DESC';
  	 $rows = $this->_getList($query);
		 return $rows;
  }	

  function getVote()
  {
     if ($this->user_id < 30) return 6;
  	 $query = 'select grade from #__photo_votes where image_id='.$this->image_id.' AND user_id='.$this->user_id;
  	 $db = $this->getDBO();
  	 $db->setQuery($query);
		 $vote = $db->loadResult();
  	 if (!isset($vote)) 
  	   $vote = 6;
		 return $vote;
  }	
  
  function insertUserComment($comm)
  {
  	 $db = $this->getDBO();
  	 $status = $this->image->published == 2 ? $this->image->a_id : 1;
  	 /* $query = 'SELECT COUNT(user_id) FROM #__photo_comments WHERE image_id='.$this->image_id.' AND user_id='.$this->user_id;
  	 $db->setQuery($query);
  	 $cnt_comms = intval( $db->loadResult() );
  	 if (!isset($comm) || JString::strlen($comm)==0 || $cnt_comms >= 3 )
  	   return false; */
  	 $comm = urldecode($comm);
  	 $db = $this->getDBO();
  	 $comm = $db->Quote($comm);  	 
  	 $query = "INSERT INTO #__photo_comments (image_id,user_id,comment,datetime,published) VALUES ($this->image_id,$this->user_id,$comm,now(),$status)";
  	 $db->setQuery($query);
  	 $db->query();
  	 //povecaj broj komentara na slici
  	 $query = 'UPDATE #__photo_images SET comments=comments+1 WHERE id='.$this->image_id;
  	 $db->setQuery($query);
  	 return $db->query();
  }

  function updateViews()
  {
  	 $db = $this->getDBO();
  	 $db->setQuery('update #__photo_images set number_of_views=number_of_views+1 where id='.$this->image_id);
  	 return $db->query(null,true);
  }
    
  function vote($grade)
  {
  	 $db = $this->getDBO();
  	 $query = 'SELECT grade FROM #__photo_votes WHERE image_id='.$this->image_id.' AND user_id='.$this->user_id;  	 
  	 $db->setQuery($query);
  	 $prev = intval( $db->loadResult() );
  	 if ( $prev > 0){
  	 	   $new = $grade;  	 	 
  	 		 $grade -= $prev;
  	 		 if ($new >= 1  && $new <= 5){
 		  	 		$query = 'UPDATE #__photo_votes AS a,#__photo_images AS b SET a.grade='.$new.',b.voteSum=b.voteSum+'.$grade.' WHERE a.image_id='.$this->image_id.' AND a.user_id='.$this->user_id.' AND b.id='.$this->image_id;
	  	 	 		$db->setQuery( $query );    	 	 	
	  	 	 		$ok = $db->query();
	  	 	 }
	  	 	 else{
 		  	 		$query = 'DELETE FROM #__photo_votes WHERE image_id='.$this->image_id.' AND a.user_id='.$this->user_id;
	  	 	 		$db->setQuery( $query );    	 	 	
	  	 	 		$ok = $db->query();
	  	 	 }
  	 }
  	 else if ($grade >= 1  && $grade <= 5){
  	 	   $query = 'INSERT INTO #__photo_votes (image_id,user_id,grade) VALUES ('.$this->image_id.','.$this->user_id.','.$grade.')';
  	 	   $db->setQuery( $query );  
  	 	   $ok = $db->query();
  	 	   if ($ok){
  	 	   	$query = 'UPDATE #__photo_images SET voteSum=voteSum+'.$grade.',voteCnt=voteCnt+1 WHERE id='.$this->image_id;
  	 	   	$db->setQuery( $query );  
  	 	   	$ok = $db->query();
  	 	  }
  	 }
		 
		 if ($ok){
		 	 if ($prev) return '{ "vote":'.$grade.', "inc":0}';
		 	 else return '{ "vote":'.$grade.', "inc":1}';
		 }	  	 
 	 	 return '!';
  }
  
  function getVoteStat(){
		$vote_stat = new StdClass;
	  $vote_stat->count = intval($this->image->voteCnt);
	  $vote_stat->sum = intval($this->image->voteSum);
	  if ($vote_stat->count > 0){
	  		$vote_stat->avg = (double)$this->image->voteSum / (double)$vote_stat->count;
	  }
	  else{
	    	$vote_stat->avg = 0.0;
	  }  	
	  return $vote_stat;
  }
  
  function getVotes(){
  	$db = $this->getDBO(); 
  	$db->setQuery( 'SELECT voteSum, voteCnt FROM #__photo_images WHERE id='.$this->image_id );
  	return $db->loadObject();
  }
  
  function loadImage()
  {
 	 	 $query = 'SELECT a.*, b.date, b.options,b.published,b.a_id,b.l_id FROM #__photo_images AS a JOIN #__photo_events AS b ON a.event_id=b.id WHERE a.id='.$this->image_id;
 	 	 $db = $this->getDBO(); 
 	 	 $db->setQuery($query);
 	 	 $this->image = $db->loadObject(); 	 	 
 	 	 $user = &User::getInstance();
		 if ($user->gid < 18) $this->user_id = -1;
		 else $this->user_id = $user->id; 	 	
		 
		 //calculate privileges		 
		 $moderators = array(68,72,98,3036);
     $rv = 0;
  	 switch ($this->image->options){
  			case 0: 
					if ( in_array($this->user_id, $moderators)  || ($this->image->a_id == $this->user_id && $this->image->published > 1) )
						$rv = 12;
					else if ( $this->image->a_id == $this->user_id  )  			
  				  $rv = 4;
  				else
  				  $rv = 0;  
  				break;
  			case 1:
  				if ( in_array($this->user_id, $moderators)  || ($this->image->a_id == $this->user_id && $this->image->published > 1) )
						$rv = 15;
					else if ( $this->image->a_id == $this->user_id  )  			
  				  $rv = 7;
  			  else if ($this->user_id > 0){
  			  	$query = 'SELECT id2 FROM #__members_friends WHERE status=1 AND id1='.$this->image->a_id.' AND id2='.$this->user_id;
  			  	$db->setQuery($query);  			  	
  			  	$tmp = $db->loadResult();
  			    if (isset($tmp)  &&  $tmp == $this->user_id)
  			    	$rv = 3;
  			    else
  			      $rv = 0;
  			  }  
  			  else
  			    $rv = 0;
  			  break;
				case 2:
					if ( in_array($this->user_id, $moderators)  || ($this->image->a_id == $this->user_id && $this->image->published > 1) )
						$rv = 15;
					else if ( $this->image->a_id == $this->user_id  )  			
  				  $rv = 7;
  			  else if ( $this->user_id > 0 )
  			    $rv = 3;
  			  else
  			    $rv = 1;  
  			  break;  			  
  		}	
  		$this->privileges = $rv;
  		//echo $this->privileges;
  		return $this->image;
  }
  
  //1 = read
  //2 = write
  //4 = moderate
  function hasPrivilege($privilege)
  {      	  
  	  if ($privilege == 'moderate')
  	  	$tmp = 8;
  	  else if ($privilege == 'change')
  	  	$tmp = 4;
  	  else if ($privilege == 'write')
  	    $tmp = 2;
  	  else if ($privilege == 'read')
  	    $tmp = 1;
      return ($this->privileges & $tmp) > 0;
  }
  
  function deleteComment($id)
  {  	 
  	 $db = $this->getDBO();
  	 $query = 'DELETE FROM #__photo_comments WHERE id='.$id;
  	 if ( !$this->hasPrivilege('moderate') ){
  	 	 $query .= ' AND user_id='.$this->user_id;
  	 }
   	 $db->setQuery( $query );
 	   if ($db->query()){
     	 $db->setQuery( 'UPDATE #__photo_images SET comments=comments-1 WHERE id='.$this->image_id );
       return $db->query();
 	   }	 
     return false;
  }
  
  function delete()
  {  	 
  	 $db = $this->getDBO();
  	 $db->setQuery( 'DELETE FROM #__photo_votes WHERE image_id='.$this->image_id );
  	 $db->query();
  	 $db->setQuery( 'DELETE FROM #__photo_favourites WHERE image_id='.$this->image_id );
  	 $db->query();
 	 	 $db->setQuery( 'DELETE FROM #__photo_comments WHERE image_id='.$this->image_id );
		 if ($db->query())
		 {
		 	  $query = 'SELECT file_name,event_id FROM #__photo_images WHERE id='.$this->image_id;
  	    $rows = $this->_getList($query);
  	    if ( !isset($rows) ) return false;
  	    $row = $rows[0];
  	    $path = JPATH_ROOT.DS.'photos'.DS.$row->event_id.'e'.DS;  	    
  	    $a =  $path.'b_'.$row->file_name;
			  @unlink($path.'b_'.$row->file_name);
		 	  @unlink($path.$row->file_name);
		 	  $db->setQuery( 'DELETE FROM #__photo_images WHERE id='.$this->image_id );
		 	  $db->query();
		 	  $db->setQuery( 'UPDATE #__photo_events SET image_count=image_count-1 WHERE id='.$row->event_id );
		 	  return $db->query();
		 }
		 return false;
  }
  
  function changeName($name)
  {
  	 $db = $this->getDBO();
  	 $name = "'".$db->getEscaped($name)."'";
  	 $query = 'UPDATE #__photo_images SET name='.$name.' WHERE id='.$this->image_id;
  	 $db->setQuery( $query );
		 return $db->query();
  }
  
  function favourite()
  {
  	 $db = $this->getDBO();
  	 $query = 'INSERT INTO #__photo_favourites (image_id,user_id) VALUES ('.$this->image_id.','.$this->user_id.')';
  	 $db->setQuery( $query );
		 return $db->query();
  }
  
  function isFavourite()
  {
  	 $db = $this->getDBO();
  	 $query = 'SELECT user_id FROM #__photo_favourites WHERE image_id='.$this->image_id.' AND user_id='.$this->user_id;
  	 $db->setQuery( $query );
		 $tmp = $db->loadResult();  	
		 return isset($tmp) && $tmp == $this->user_id;
  }
  
  function loadLocation()
  {
  	$db = $this->getDBO();
  	$query = 'SELECT name FROM #__photo_locations WHERE id='.$this->image->l_id;
  	$db->setQuery($query);
  	return $db->loadResult();
  }
  
  function loadUserName()
  {
  	$db = $this->getDBO();
  	$query = 'SELECT username FROM #__users WHERE id='.$this->image->a_id;
  	$db->setQuery($query);
  	return $db->loadResult();
  }  

}
?>  