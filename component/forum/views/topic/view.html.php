<?php
defined( 'CREW' ) or die( 'Restricted access' );

class ForumViewTopic extends JView
{
	var $id;
  function display($tpl = null)
	{		
		 $model = $this->getModel();
		 $model->tid = $this->id;
		 $model->get();
		 $rights = $model->rights();
		 if ( $model->topic  &&  $rights > 0 ){ //pravo pristupa za gledanje
	 		  $limit = 10;
				$limitstart = JRequest::getInt('limitstart',0);
   		  $posts = $model->getPosts($limitstart,$limit);
   		  $cnt = $model->getCnt();
			  $pag = JHTML::getPagination( $cnt, $limitstart, $limit, null, 'html' );
		    $this->assignRef( 'pag',	$pag );  		 		
		 		$this->assignRef('topic',$model->topic);
		 		$this->assignRef('isMod',$model->isMod);
		 		$this->assignRef('posts',$posts);
		 		$this->assignRef('rights',$rights);
  	 		parent::display( $tpl );
  	 }
	}
}
?>