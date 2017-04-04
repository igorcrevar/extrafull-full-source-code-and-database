<?php
defined( 'CREW' ) or die( 'Restricted access' );

class MembersViewChanges extends JView
{
  function display($tpl = null)
	{		
		  global $mainframe;
		  $user = &User::getInstance();
		  if ($user->gid < 18) return;
		  $model = $this->getModel();
		  $limit =  40;
		  $limitstart	= JRequest::getInt( 'limitstart', 0 );
		  $friends	= JRequest::getInt( 'friends', 0 );
		  $area = JRequest::getInt( 'area', 0 );
		  if ($limitstart > $limit * 20) $limitstart = 0;
		  if ($friends){
		  	if ( in_array($area, array(2,3)) ){
		  		$rows = $model->getMy($user->id, $limitstart,$limit,$area);
		  	}
		  	else{
		   		$rows = $model->getMyAll($user->id,$limitstart,$limit);
		   	}		   
		  } 
		  else{
		  	if ( in_array($area, array(2,3)) ){
		  		$rows = $model->get($limitstart,$limit,$area);
		  	}
		  	else{
		   		$rows = $model->getAll($limitstart,$limit);
		   	}
		  }
		  $cnt = $limit * 20; 
		 	$pag = &JHTML::getPagination( $cnt, $limitstart, $limit, null, 'html' );
		  $this->assignRef('area',$area);	
		  $this->assignRef('pag',$pag);	
		  $this->assignRef('rows',$rows);	
		  $this->assignRef('friends',$friends);	
	    parent::display( $tpl );
  }
}  
?>