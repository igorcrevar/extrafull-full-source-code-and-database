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
class MembersViewProfile extends JView
{
	function display( $tpl = null)
	{
		$model = $this->getModel();
		$doc = Document::getInstance();
		$doc->addScript(JURI::base().'component/members/m.js');
		
		$doc->addStyleSheet(JURI::base().'modules/my_calend.css');
		$doc->addScript(JURI::base().'modules/my_calendar.js');
		
		$limit = JRequest::getInt('limit',10);
		$id = JRequest::getInt('id',70);
		
		/* block code */
		require_once BASE_PATH.DS.'block.php';
		if ( isUserBlockedBy($id, true ) ){
			return;
		} 
		/* end block code */
				
	  $row = $model->load($id);
	  if ( $row != null  && $row->id > 0)
	  {
	  	$user = &User::getInstance();
	  	require_once(JPATH_BASE.DS.'editor'.DS.'editor.php');	
			$row->personalText = editor_decode($row->personalText);
	  	if ( $user->gid >= 18 ){
	    	$locations = $model->loadLocations($id);
	    	$galleries = $model->getPrivateGalleries($id);	  	
	  		$cnt = $model->getCommentsCnt($id);
			  $comments = $model->loadComments($id,0,$limit);
	  		$layout = 'default';
	  	  $this->assignRef( 'comments', $comments );
		    $this->assignRef( 'locations', $locations);
		    $this->assignRef( 'galleries',$galleries); 	    
			  $pag = &JHTML::getPagination( $cnt, 0, $limit, array('com_pag','id',$id), 'ajax' );
			  $this->assignRef('pag',$pag);
	  	}else{
	  		$layout = 'default_quest';
	  		$doc->description = $row->name.' '.htmlspecialchars(strip_tags($row->personalText)).' '.$doc->description;
	  	}
			      
	    if ( $row->avatar == null  ||  $row->avatar == '' )
	      $row->avatar = 'nophoto.jpg';
	    else
	      $row->avatar = 'l_'.$row->avatar;  
	    $this->assignRef( 'user', $row );
 	    $this->setLayout($layout);
 	    $doc->setTitle(JText::_('TITLE_PROFIL').' '.$row->username.' odnosno '.$row->name); 	    
		  parent::display( $tpl );	    
	  }  
	}	  
}
?>
