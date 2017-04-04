<?php
defined( 'CREW' ) or die( 'Restricted access' );

class ForumViewTopics extends JView
{
	var $id;
  function display($tpl = null)
	{				 	
		 $model = $this->getModel();
		 $model->cid = $this->id;
		 $model->loadCat();
		 $rights = $model->rights();
		 if ( $model->cat  &&  $rights > 0 ){ //pravo pristupa za gledanje
	 		  $limit = 20;
				$limitstart = JRequest::getInt('limitstart',0);
   		  $topics = $model->get($limitstart,$limit);
   		  $cnt = $model->getCnt();
			  $pag = JHTML::getPagination( $cnt, $limitstart, $limit, null, 'html' );
		    $this->assignRef( 'pag',	$pag );  		 		
		 		$this->assignRef('cat',$model->cat);
		 		$this->assignRef('topics',$topics);
		 		$this->assignRef('rights',$rights);
  	 		parent::display( $tpl );
  	 }
  }
}  
?>