<?php
defined( 'CREW' ) or die( 'Restricted access' );

class PhotoViewEvent extends JView
{
  function display($tpl = null)
	{		
		  global $mainframe;
		  $doc = &JFactory::getDocument();
		  $doc->addScript( Basic::uriBase().'/component/photo/pho.js' );
		  //$doc->addStyleSheet( Basic::uriBase().'/component/photo/photo.css' );
		  $model = $this->getModel();
		  $limit =  8;
		  $limitstart	= JRequest::getInt( 'limitstart', 0 );
		  $id	= JRequest::getInt( 'id' );
		  $model->event_id = $id;		  
		  $event = $model->getEvent();	
		  
		/* block code - blocks even public images - fuck you :) */
		require_once BASE_PATH.DS.'block.php';
		if ( isUserBlockedBy($event->a_id, true ) ){
			return;
		} 
		/* end block code */  
		  $imgs = $model->getImages( $limitstart, $limit );
		  $cnt = $model->getImagesCount();
		  if (!isset($event)) return;	  
 	   	$doc->description = 'Extrafull galerija slika kluba : '.$event->location;
	   	$doc->keywords = $event->location.', '.$doc->keywords;

      $pagination = &JHTML::getPagination( $cnt, $limitstart, $limit, array('galPag', 'id', $id, $cnt), 'ajax', 10 );
	    $mypath = Basic::routerBase().'/photos/'.$id.'e/';
	    $this->assignRef( 'event_id',$id );
	    $this->assignRef( 'imgs' , $imgs );
	    $this->assignRef( 'event' , $event );
	    $this->assignRef( 'pagination',$pagination );
	    $this->assignRef( 'mypath', $mypath );
	    $e_date = JHTML::_('date',$event->date,'date');
	    $this->assignRef( 'e_date',  $e_date);
      $doc = & JFactory::getDocument();
      if ($event->published==1)
      {
      	$doc->setTitle('Extrafull galerija slika : '.$event->location);
        list($day,$month,$year) = explode('.',$e_date); 
	    } 
	    else{
	      $doc->setTitle('Extrafull galerija slika za profil: '.$event->username); 
	      $user = &JFactory::getUser();
	      if ($user->gid<18) { echo 'Registruj se da bi video galeriju!!!!';return;}
	    }  

	    parent::display( $tpl );
  }
}  
?>