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



class PhotoController extends JController
{
	function display()
	{	   
		$viewName = JRequest::getCmd('view', 'events'); //[A-Za-z0-9.-_]
		if ( $viewName == 'imageupload' )
		{
			 $user = User::getInstance();
  	   $access = $user->gid >= 18;
		   if ( !$access ) 
			 {
			   $this->setRedirect( 'index.php', JText::_('ALERTNOTAUTH') );
			   return;
			 }  
		}
		$doc = &Document::getInstance();
		$viewType = $doc->getType();
		$view = $this->getView( $viewName, $viewType );
		$model = $this->getModel( $viewName, 'modelphoto' ); 
		$view->setModel( $model, true ); 
   	$view->display(); 
  }

  function createEvent()
  {
  	 $private = JRequest::getInt('private', 0);
  	 $model = $this->getModel( 'imageupload', 'modelphoto' ); 
  	 $model->private = $private;
     $rv = $model->makeEvent(); 
     if ( $rv > 0 )
       $msg = '';
     else if ( $rv == -666 )
       $msg = 'Imate vec maximalan dozvoljen broj galerija!!';
     else  
       $msg = 'Greska!'; 
     $link = JRoute::_('index.php?option=com_photo&view=imageupload&private='.$private);
     $this->setRedirect( $link, $msg );     
  }
  
	function imagesUpload()
	{
		 $doc = &Document::getInstance();
		 $ajax = $doc->getType() == 'raw';
		 $viewName = 'imageupload';
  	 $private = JRequest::getInt('private', 0);
  	 $id = JRequest::getInt( 'event_id', -1, 'POST' );
		 $model =$this->getModel( $viewName, 'modelphoto' );
		 $model->private = $private;
		 $rv = $model->copyFiles($id,$ajax);
     if ( $rv > 0 )
     {
     	 if ($ajax)
      	 $msg = 'ok';
       else
      	 $msg = 'Slika je uploadovana';  
     }
     else if ( $rv == -200 )
       $msg = '<b>Greska:</b> Prevelika ili premala slika!!';
     else if ( $rv == -666 )
       $msg = '<b>Greska:</b> Imate vec maximalan dozvoljen broj slika!!';
     else  
       $msg = '<b>Greska!</b>'.$rv;

     if ( $ajax )
     {
     	 echo $msg;
     	 return;
     }
     else
     {
       $link = JRoute::_('index.php?option=com_photo&view=imageupload&private='.$private.'&id='.$id);
			 $this->setRedirect( $link, $msg );
		 }  
	}
	
	function del(){
		$model = $this->getModel( 'imageupload', 'modelphoto' );
		$user = User::getInstance();
		if ( $user->gid < 18 ) {
			$this->setRedirect( 'index.php' );
			return;
		}	
		$private = JRequest::getInt('private',0);
		$id = JRequest::getInt('id',0);
		$model->deleteEvent( $id, $user->id );
		$link = '/galerije/upload?private='.$private;
		$this->setRedirect( $link );
	}
		
	
	function change()
	{
		 $viewName = JRequest::getCmd('view', 'image');
  	 $image_id = JRequest::getInt( 'id', 0 );
  	 $comment_id = JRequest::getInt( 'cid', null );
  	 $name = JRequest::getString( 'file_name', null,'POST' );
		 $model = $this->getModel( $viewName, 'modelphoto' );
		 $model->image_id = $image_id;
		 $image = $model->loadImage();
		 if ( isset($comment_id) )
	   {
	     $rv = $model->hasPrivilege('moderate')  &&  $model->deleteComment($comment_id);	     
	     JRequest::setVar('image_id',$image_id);
	     JRequest::setVar('useraction','imageview');
	     $this->display();
	     return;
	   }  
		 else if ( $name != null )
		   $rv = $model->hasPrivilege('change')  &&  $model->changeName($name);
		 else   
		   $rv = $model->hasPrivilege('change')  &&  $model->delete();  
		 if ( !$rv )
		   $msg = '<b>Greska!!!</b>';
     $link = JRoute::_('index.php?option=com_photo&view=event&id='.$image->event_id);
		 $this->setRedirect( $link, $msg );     
	}	
	
	function updateTop(){
		$user = JFactory::getUser();
		if ($user->gid < 18) return;
		$param = JRequest::getString( 'param', '' );
		$db = &JFactory::getDBO();
		$query = 'UPDATE #__users SET params='.$db->Quote($param).' WHERE id='.$user->id;
		$db->setQuery($query);
		$db->query();
	}
	
	function delFav(){
		$user = JFactory::getUser();
		if ($user->gid >= 18){
				$id = JRequest::getInt( 'i_id', '0' );
				$db = &JFactory::getDBO();
				$query = "DELETE FROM #__photo_favourites WHERE user_id=$user->id AND image_id=$id";
				$db->setQuery($query);
				$db->query();				
		}		
    $link = JRoute::_('index.php?option=com_photo&view=images');
		$this->setRedirect( $link, '' );
	}
	
}
?>