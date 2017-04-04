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

class ForumViewPosts extends JView
{
	var $id;
  function display($tpl = null)
	{		
		 $user = &User::getInstance();
		 if ( $user->gid >= 18 ){
			 	$model = $this->getModel();
			 	$tmp = JRequest::getInt('id');
			 	$model->userId = $tmp ? $tmp : $user->id;
			 	$model->id = $this->id;
	 		 	$limit = 10;
		 		$limitstart = JRequest::getInt('limitstart',0);
   		  $posts = $model->getPosts($limitstart,$limit);
   		  $cnt = $model->getPostsCnt();
			  $pag = JHTML::getPagination( $cnt, $limitstart, $limit, null, 'html' );
		    $this->assignRef( 'pag',	$pag );  		 		
		 		$this->assignRef( 'posts',$posts);
		 		$this->assignRef( 'what', $model->title );
  	 		parent::display( $tpl );
  	 }
	}
}
?>