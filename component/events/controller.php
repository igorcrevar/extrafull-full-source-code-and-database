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

class EventsController extends JController
{
	function display()
	{	   
		 $viewName = JRequest::getCmd( 'view', 'list' );
		 $user = &User::getInstance();
		 $gid = $user->gid;
		 if ( $viewName == 'new' &&  $gid < 18 )
		 {
		 	 $this->setRedirect( 'index.php', 'Niste registrovani' );
		 	 return;
		 }
		 $doc = &Document::getInstance();
		 $viewType = $doc->getType();
		 $view = $this->getView( $viewName, $viewType );
		 $model = $this->getModel( $viewName, 'EventsModel');
		 $view->setModel( $model, true ); 
   	 $view->display();
  }		
  
  function addNew(){
  	$model = $this->getModel( 'new', 'EventsModel');
  	$year = JRequest::getInt('year',0,'POST');
  	$month = JRequest::getInt('month',0,'POST');
  	$day = JRequest::getInt('day',0,'POST');
  	$rv = $model->addEvent( $year, $month, $day);
  	if ($rv)
  	  $this->setRedirect( Basic::uriBase().'/index.php?option=events&view=list&year='.$year.'&month='.$month.'&day='.$day );
  	else 
  	  $this->setRedirect( Basic::uriBase().'/index.php?option=events&view=new', 'Greska' );
  }
  
  function AddLocation(){
  	$model = $this->getModel( 'new', 'EventsModel');
  	if ( !MyRights::isEventsModerator($user->id) ){
  		echo '<center><b>Trenutno je onemoguceno dodavanje lokacija za obicne clanove</b></center>';
  	}
  	else{
  		$rv = $model->addLocation();
  		$res = array( 'Greska!', 'Lokacija dodata!', 'Vec postoji slicna lokacija!', 'Clan moze uneti maximalno jednu lokaciju dnevno', 'Clan ne moze uneti vise od dve lokacije!' );
  		$this->setRedirect( JRoute::_('index.php?option=com_events&view=new'), $res[$rv] );  		
  	}
  }
  
  function attend(){
  	$id = JRequest::getInt('id');
    $user = &User::getInstance();
    if ($user->gid<18) return;
    $model = $this->getModel( 'event', 'EventsModel');
    $model->id = $id;
    $model->myAttend( $user->id );
    $this->loadAttends();
  }
  
  function loadAttends(){
  	$id = JRequest::getInt('id');
    $model = $this->getModel( 'event', 'EventsModel');
    $model->id = $id;
		$view = $this->getView( 'event', 'raw' );
		$view->setModel( $model, true ); 
    $view->display();		
  }
  
  function feauture(){
  	$id = JRequest::getInt('id');
    $user = &User::getInstance();
    if ( !MyRights::isEventsModerator($user->id) ) return;
    $db = &Database::getInstance();
    $db->setQuery( 'UPDATE #__events SET feautured=1-feautured WHERE id='.$id );
    $db->query();
    require_once(BASE_PATH.DS.'cache.php');
    $cache = &Cache::getInstance();
    $cache->clear( array('crewMost','getEvents') );
    $this->setRedirect( '/desavanja/event/'.$id );
  }
    
  function del(){
  	$user = &User::getInstance();
  	$model = $this->getModel( 'new', 'EventsModel');
  	$id = JRequest::getInt('id',0);
  	$type = JRequest::getCmd('what','event') == 'event' ? 1 : 2;
  	if ( MyRights::isEventsModerator($user->id) ){
  	  $model->delete($id,$type);
  	  $this->setRedirect(JRoute::_('index.php?option=com_events'),'Obrisano');  	
  	}  	
  }
  
	function delCmnt(){
  	$user = &User::getInstance();
  	if ($user->gid < 18) return;
		$id = JRequest::getInt( 'id',-1 );
		$db = &Database::getInstance();
		$cid = JRequest::getVar( 'cid', array(),'post','null' );
		if (!is_array($cid)) return;
		$cids =chop( implode(',', array_keys($cid) ));
		if ( !MyRights::isEventsModerator($user->id) ){
			$db->setQuery('SELECT user_id FROM #__events WHERE id='.$id);
			$who = intval( $db->loadResult() );
			if ($who < 30  ||  $who != $user->id) return;
		}	
		
		$query = 'DELETE FROM #__comments WHERE type=2 AND type_id='.$id.' AND id in ('.$cids.');';		
		$db->setQuery( $query );
		if ( !$db->query() ) $msg = 'Greska!';
		else {
			$msg = 'Komentari obrisani';		
		}
  	$this->setRedirect( '/desavanja/event/'.$id.'#comments', $msg );     
	}
	
	function comment(){
		$user = &User::getInstance();
  	if ($user->gid < 18) return;
		$id = JRequest::getInt('id',-1);
		$db = &Database::getInstance();
		$comment = JRequest::getString('comment','');
		if (JString::strlen($comment) < 4){
			global $mainframe;
  		$mainframe->redirect('/desavanja/event/'.$id, 'Komentar mora imati barem 4 znaka');
  	} 		
		$comment = $db->Quote( $comment );
		$time = time();
		$query = "INSERT INTO #__comments VALUES (0,2,$id,$comment,$user->id,$time);";
		$db->setQuery($query);
		$msg = 'Greska!';
		if ( $db->query() ) $msg = '';
  	$this->setRedirect( '/desavanja/event/'.$id.'#comments', $msg );     
	}  
}
