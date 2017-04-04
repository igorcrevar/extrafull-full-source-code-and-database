<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');

class PhotoViewImageUpload extends JView
{
  function display($tpl = null)
	{		
 	    $mypath = Basic::uriBase().'/component/photo/views/';
 	    $private = JRequest::getInt('private', 0);
 	    $id = JRequest::getInt('id',-1);
 	    $change = JRequest::getCmd('change',null);
 	    $user = & JFactory::getUser(); 
 	    $model = $this->getModel();
		  if ( $user->gid == 21  && $private == 0) //Javna galerija samo od Publishera
		  {
		  	$model->private = 0;
		  	if ( $id > 0  && $change == null){
		      $document = JFactory::getDocument();
		      //$document->addScript( $mypath.'si.files.js' );
		      //$document->addStyleSheet( $mypath.'si.files.css' );
		      $this->setLayout('default_upload');
		  	}		  	
		  	else {
		  		$events = $model->getEvents(); 	    
		  		if ($change != null ) {
						$event = $model->getEvent($id);
					}
		  		else {
						$event = null;
					}
					$list = $model->getCatLoc($event);
		      $this->assignRef( 'event', $event);
		      $this->assignRef( 'list', $list);		 
 	    		$this->assignRef( 'events', $events);
		  	}
		  }
		  else
		  {
		  	$model->private = 1;
		  	$private = 1;
		  	if ( $id > 0  && $change == null){
		  		$this->setLayout('default_upload');
		  	}else{
		  		$events = $model->getEvents(); 	    
		  		if ($change != null ) $event = $model->getEvent($id);
		  		else $event = null;
		      $this->assignRef( 'event', $event);
 	    		$this->assignRef( 'events', $events);		  		
		  	}		  	
		  }	
		  $this->assignRef( 'event_id', $id);
		  $this->assignRef( 'private', $private);
		  parent::display( $tpl );
  }
}  
?>