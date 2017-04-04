<?php
defined( 'CREW' ) or die( 'Restricted access' );

class PhotoViewImages extends JView
{
  function display($tpl = null)
	{		
  	  $user = &User::getInstance();
  	  if ( $user->gid < 18 ) { echo 'Niste registrovani'; return; } //privatne samo ako si registrovan
  	  $id = JRequest::getInt( 'id', $user->id );
		
    	$doc = &Document::getInstance();
		  $doc->addScript( JURI::base().'component/photo/pho.js' );
		  $stat = JRequest::getInt('stat',0);
		  if ($stat==1000){
		  	$this->setLayout('choose');
		  }else{
		  	$gender = JRequest::getCmd('gender','');
 	 	  	if ($gender=='male') $stat += 100;
 	 	  	else if ($gender=='female') $stat += 200;
		  	$limit =  10 + ($stat==6)*6;
		  	$limit = JRequest::getInt( 'limit', $limit );
		  	$limitstart	= 0;
		  	$model = $this->getModel();
		  	$rows = $model->getImages( $id, $limitstart, $limit, $stat );
		  	if (!isset($rows)) return;	  
		  	$this->assignRef( 'imgs' , $rows['imgs'] );
		  	$stat %= 100;
		  	$this->assignRef( 'stat' , $stat );
	      if ($stat == 6){
	      	$this->setLayout('friends');
			  	$pagination = &JHTML::getPagination( $rows['cnt'], $limitstart, $limit, array('imgPag','id',0,$stat,$rows['cnt']), 'ajax' );		  	
	      }
	      else{
	      	$pagination = &JHTML::getPagination( $rows['cnt'], $limitstart, $limit, array('imgPag','id',$id, $stat, $rows['cnt']), 'ajax' );		  	
	      }
	    	$this->assignRef( 'pagination',$pagination );
	    }
	    $first = true;
	    $this->assignRef( 'first', $first);
	    $this->assignRef( 'id', $id);
	    parent::display();	    
	}
}
?>