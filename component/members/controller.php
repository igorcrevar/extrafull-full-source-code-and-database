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


class MembersController extends JController
{
	function display()
	{	   
		 $viewName = JRequest::getCmd('view','userlist');
		 $user = & User::getInstance();
		 if ( $user->gid < 18 && $viewName != 'profile' && $viewName != 'lovers' )
		 {
		 	 $this->setRedirect( 'index.php','Niste registrovani' );
		 	 return;
		 }
		 $doc = &Document::getInstance();
		 $viewType = $doc->getType();
		 $view = $this->getView( $viewName, $viewType );
		 $model = $this->getModel( $viewName, 'MembersModel');
		 $view->setModel( $model, true ); 
   	 $view->display();
  }		
  
  function upload()
  {
 	 	 $user = & JFactory::getUser();
		 if ( $user->gid < 18 )
		 {
		 	 $this->setRedirect( 'index.php','Niste registrovani' );
		 	 return;
		 }
		 $viewName = 'myprofile';
		 $model = $this->getModel( $viewName, 'MembersModel');
		 if ( isProfileMod( $user->id ) ){
		 		$id = JRequest::getInt( 'id', $user->id );
		 }
		 else{
		 		$id = $user->id;
		 }		 
		 $model->id = $id;
  	 $segment = JRequest::getCmd( 'segment', 'profile' );
		 switch ($segment)
		 {
		 	 case 'profile': if ( JRequest::getMethod() == 'POST' ){ $rv = $model->update_profile(); }
		 	 									break;
		 	 case 'picture': $rv = $model->update_picture(); break;
		 	 case 'ban': if ( $id != $user->id ) $rv = $model->update_ban(); break;
		 	 case 'username': $rv = $model->update_username(); break;
		 	 case 'account': if ( JRequest::getMethod() == 'POST' ){
		 	 									 $rv = $model->update_account();
		 	 								 }
		 	 								 break;
		 	 case 'locations': $rv = $model->update_locations();break;
		 	 case 'is': if ( JRequest::getMethod() == 'POST' ){ $rv = $model->update_is(); }
		 }
  	 $usid = ($id != $user->id) ? '&id='.$id : '';
		 $this->setRedirect( 'index.php?option=com_members&view=myprofile'.$usid, $rv );		  
  }
  
  function comment()
  {
  	 $id = JRequest::getInt('id');  
		/* block code */
		require_once BASE_PATH.DS.'block.php';
		if ( isUserBlockedBy($id, false ) ){
			echo JText::_('BLOCKUSER_MSG');
			return;
		} 
		/* end block code */  	  	 	 
  	 $viewName = 'profile';
		 $model = $this->getModel( $viewName, 'MembersModel');
		 if ( $model->comment( $id ) )
		 {		 	 
		   $user = &User::getInstance();
		   echo '<b>'.JHTML::profileLink($user->id,$user->username);
			 echo ' '.JHTML::_('date',time());
			 echo '</b><br>';
			 echo JRequest::getString('comment');
			 echo '<br/>';			    	   
		 }  
  }
  
  function deleteComment()
  {
  	 $viewName = 'profile';
		 $model = $this->getModel( $viewName, 'MembersModel');
		 if ( $model->deleteComment() )
		 {		 	 
		   $view = $this->getView( 'profile', 'raw' );
		   $model = $this->getModel( 'profile', 'MembersModel');
		   $view->setModel( $model, true ); 
   	   $view->display();
		 } 
  }
  
  function privateMsg()
  {
  	 $id = JRequest::getInt('id');  	 
		/* block code */
		require_once BASE_PATH.DS.'block.php';
		if ( isUserBlockedBy($id, false ) ){
			echo JText::_('BLOCKUSER_MSG');
			return;
		} 
		/* end block code */  	  	 
  	 $viewName = 'profile';
		 $model = $this->getModel( $viewName, 'MembersModel');
		 $rv = $model->privateMsg( $id );
		 if ($rv)
		   $rv = 'Privatna poruka je poslata!';
		 else
		   $rv = 'Greska!!'; 
		 echo $rv; 
  }

  function block(){
  	require_once BASE_PATH.DS.'block.php';
  	$user = &User::getInstance();
  	if ( $user->gid < 18 ) return;
  	$who_id = JRequest::getInt('id',0);
  	$isUpdate = JRequest::getInt('update',0);
  	if ( !$isUpdate ){
  		$isUpdate = intval(UserBlocks::isBlock($who_id)) + 1;
  		$ok = true;
  	}else{
  		$ok = $isUpdate == 1 ? UserBlocks::addBlock($who_id) : UserBlocks::removeBlock($who_id);
  		if ( $ok ) $isUpdate = 3 - $isUpdate;
  	}
  	//echo '_label.innerHTML="';
  	if ( !$ok ) echo JText::_('DBERROR').'<br />';
  	UserBlocks::show( $who_id, $isUpdate, false );
  	//echo '";';
  }
    
  function addKarma(){
    $who_id = JRequest::getInt('id',-1,'POST');
    $vote = JRequest::getInt('vote',-1,'POST');
  	$viewName = 'profile';
		$model = $this->getModel( $viewName, 'MembersModel');
		$rv = $model->addKarma($who_id,$vote);
		if ($rv==null)
		{ 
		  echo 'Greska!';
		}  
		else {
			echo $rv;
	 	}	
  }
  
  function pending()
  {
  	 $do = JRequest::getCmd( 'do', 'accept' );
		 $request_id = JRequest::getInt( 'id', 0 );
		 $model = $this->getModel( 'request', 'MembersModel' );
		 $rv = $model->pending($request_id, $do);
		 if ( !is_string($rv) ) $rv = '';
		 $this->setRedirect( 'index.php?option=members&view=request', $rv );
  }
  
  function request(){
  	 $id = JRequest::getInt('id');
  	 $type = JRequest::getString( 'type', 1 );
  	 $type_id = JRequest::getString( 'type_id', 1 );
  	 $desc = JRequest::getString( 'desc', '' );
  	 if ( !empty($desc) ){
  	 	 if ( $type == 2 ){
  	 	 	//TODO
  	 	 }
  	 }
		 $model = $this->getModel( 'request', 'MembersModel' );
		 $rv = $model->request( $id, $desc, $type, $type_id );
		 
		 if ( $rv < 0 ){
		 	  if ( $rv == -1 ){
		 	  	if ($type == 2) $rv = 'Vec je u vezi ili si ti u vezi';
		 	  	else if ($type==1) $rv = 'Vec ste prijatelji';
		 	  }else $rv = 'Zahtev je vec poslat!'; 
		 } 
		 else if ($rv){
		 		$rv = 'Zahtev je poslat!'; 
		 }
		 else
		   $rv = 'Greska!!';
		 if ( $type == 2 ){
		 		$this->setRedirect( 'index.php?option=members&view=request', $rv );
		 }  
		 else{
		 		echo $rv;		 
		 }
  }  
  
  function friendRequest(){ $this->request(); }
  
  function unlover(){
  	 $model = $this->getModel( 'request', 'MembersModel' );
		 $rv = $model->deleteLover();
		 echo "alert('Raskinuto!')";
  }
  
  function unfriend()
  {
  	 $id = JRequest::getInt('id');
		 $model = $this->getModel( 'request', 'MembersModel' );
		 $rv = $model->deleteFriend($id);
		 if ( $rv == 666 ) $rv = 'Jos ste u vezi. Prvo to sredi!';
		 else $rv = '';
		 $this->setRedirect( 'index.php?option=members&view=request', $rv );
  }
  
  function myVisit()
  {
  	 $user = &JFactory::getUser();
  	 $id = $user->id;
  	 if ( $user->gid >= 18 )
  	 {
	  	  $l_id = JRequest::getInt('l_id');
	  	  $event_id = JRequest::getInt('e_id');
  	 	  $db = &JFactory::getDBO();
  	 	  if (JRequest::getCmd('what',null)==null)
  	 	    $query = "INSERT INTO #__members_locations (location_id,user_id) VALUES ($l_id,$id)";
  	 	  else  
  	 	    $query = "DELETE FROM #__members_locations WHERE location_id=$l_id AND user_id=$id";
  	 	  $db->setQuery($query);
  	 	  if ($db->query())
  	 	  {
  			  $this->setRedirect( 'index.php?option=com_photo&view=event&id='.$event_id );  
  			  return;
  			} 
  	 }
  	 $this->setRedirect( 'index.php', 'Greska!' );
  }

  function clearCmnts()
  {
  	 $user = &User::getInstance();
  	 $id = $user->id;
  	 if ( $user->gid >= 18 ){
  	 	  $db = &Database::getInstance();
  	 	  $db->setQuery( 'DELETE FROM #__members_comments WHERE who_id='.$user->id );
  	 	  $db->query();
  	 	  $this->setRedirect( '/profil/'.$user->id, 'Svi komentari su obrisani' );
  	 }
  }

	function ip(){
		 $user = &User::getInstance();
		 if ($user->gid < 18  ||  !isProfileMod($user->id) ) return;
		 $db = &Database::getInstance();
		 $ip = JRequest::getString('ip','');
		 if ( !empty($ip) ){
			$cache = &Cache::getInstance();
			$bannedIPS = $cache->clear( 'getIPBans' ); 		 
		 	$ip = $db->Quote($ip);
		 	$time = time();
  	 	$db->setQuery( "INSERT INTO #__banned_ip VALUES($ip,$time,$user->id)" );
  	 	$db->query();
  	}
 		$view = $this->getView( 'ips', 'html' );
   	$view->display(); 	
	}

}
?>