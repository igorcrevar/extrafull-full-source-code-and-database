<?php
/*=============================================================================
|| ##################################################################
||	Igor Crevar Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||	Contact			: crewce@hotmail.com
||
||	- Extrafull and all of its source code and files are protected by Copyright Laws.
||
||	- You can not use any of the code without Igor Crevar agreement
||
||	- You may also not remove this copyright screen which shows the copyright information and credits for Extrafull (Igor Crevar).
||
||	- Igor Crevar Extrafull is NOT a FREE software
||
|| ##################################################################
=============================================================================*/
 defined( 'CREW' ) or die( 'Restricted access' );

class ModelBlogBlog extends JModel
{  
	var $id;
	var $privileges;
  function getComments( $limitstart, $limit )
  {
  	 $query = 'select a.*,b.username from #__comments AS a JOIN #__users AS b ON b.id=a.who_id WHERE a.type=1 AND a.type_id='.$this->id.' ORDER BY a.id DESC';
  	 $rows = $this->_getList( $query, $limitstart, $limit );
		 return $rows;
  }	
  
  function get(){
  	 $query = 'select a.*,b.username from #__blogs AS a JOIN #__users AS b ON b.id=a.who_id where a.id='.$this->id;
  	 $db = $this->getDBO();
  	 $db->setQuery( $query );
		 $blog = $db->loadObject();
		 $user = &User::getInstance();
		 if ($user->gid < 18) $this->privileges = 0;
		 else{
	 	 		switch ($blog->options){
  				case 0: 
  			  	$this->privileges = 0;
  			  	break;
  				case 1:  				  
						if ( isMod($user->id) || $blog->who_id == $user->id  )
						   $this->privileges = 7;
						else{
	  			  	$query = 'SELECT id2 FROM #__members_friends WHERE id1='.$blog->who_id.' AND id2='.$user->id.' AND status=1';
  				  	$db->setQuery($query);  			  	
  				  	$tmp = intval($db->loadResult());
  				  	$this->privileges = intval($tmp == $user->id)*3; //read/write
						}    
						break;
					case 2:
						if ( isMod($user->id) || $blog->who_id == $user->id  )
						   $this->privileges = 7;
					 	else{
					 		$this->privileges = 3;
					 	}
				}	 	
  		}	
		 return $blog;
  }	
  
}
?>