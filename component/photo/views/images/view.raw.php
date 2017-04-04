<?php
defined( 'CREW' ) or die( 'Restricted access' );

class PhotoViewImages extends JView
{
  function display($tpl = null)
	{		
  	  $user = &User::getInstance();
  	  if ( $user->gid < 18 ) { echo 'Niste registrovani'; return; } //privatne samo ako si registrovan
  	  $id = JRequest::getInt( 'id', 0);
  	  if ( !$id ) $id = $user->id;
		  $stat = JRequest::getInt('stat',0);
		  $cnt = JRequest::getInt('cnt',0);
		  
		  $limit =  10 + ($stat==6)*6;
		  $limit = JRequest::getInt( 'limit', $limit );
		  $limitstart	= JRequest::getInt( 'limitstart', 0 );
		  $model = $this->getModel();
		  $rows = $model->getImages( $id, $limitstart, $limit, $stat, $cnt );
		  if (!isset($rows)) return;	  
      $this->assignRef( 'imgs' , $rows['imgs'] );
		  $stat %= 100;
		  $this->assignRef( 'stat' , $stat );
		  $pagination = &JHTML::getPagination( $cnt, $limitstart, $limit, array('imgPag','id',$id,$stat,$cnt), 'ajax' );		  
	    $this->assignRef( 'pagination',$pagination );
	    if ($stat == 6)
	      	$this->setLayout('friends');
	    $first = false;
	    $this->assignRef( 'first', $first);	      	
	    parent::display();
	}
}
?>