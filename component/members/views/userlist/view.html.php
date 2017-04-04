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

class MembersViewUserList extends JView
{
	function display( $tpl = null)
	{
		  $layout = JRequest::getVar( 'layout', 'default' );
		  $model = $this->getModel();
		  $user = &User::getInstance();
		  $userId = $user->gid >=18 ? $user->id : 0;
		  $this->assignRef( 'userId', $userId );
		  if ( $layout == 'list' ){
	      $limit = 20;
		    $limitstart	= JRequest::getInt( 'limitstart', 0); 
		    $fid = JRequest::getInt('friends',0);  
		    $myfriends = $fid > 0 && $fid == $userId;
	    	$this->assignRef( 'myfriends', $myfriends ); 
		    $mutual = JRequest::getInt('mutal',0);  
	      $list = $fid ? ( $mutual ? $model->getMutual( $userId, $fid, $limitstart, $limit ) : $model->getFriends( $fid, $limitstart, $limit ) ) : $model->get( $limitstart, $limit );
	      if ( $list['count'] > 0 ){
					$pagination = JHTML::getPagination( $list['count'], $limitstart, $limit, null, 'html' );
					$this->assignRef( 'pagination', $pagination );
		      $this->assignRef( 'rows', $list['users'] );
		      $this->assignRef( 'cnt', $list['count'] );
		    }
		    else{  
		    	 $notfound = true;
		    	 $this->assignRef( 'notfound', $notfound );
		    	 $layout = 'default';
		    }
		  } 
		  else{ 
		  	$rows = $model->loadPhotoLocations();
		  	$rows = JHTML::_('select.genericlist', $rows, 'photo_locations', '', 'id', 'name', 0 );
		  	$this->assignRef( 'photo_locations',$rows );
		  }
		  $this->setLayout( $layout ); 
		  parent::display( $tpl );
	}	  
}
?>
