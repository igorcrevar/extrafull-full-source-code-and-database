<?php
defined( 'CREW' ) or die( 'Restricted access' );

class ForumViewCats extends JView
{
	var $id;
  function display($tpl = null)
	{		
		 $model = $this->getModel();
		 $cats = $model->get();
		 if (count($cats)){
		 		$this->assignRef('cats',$cats);
		 		parent::display( $tpl );
		 }
	}
}
?>