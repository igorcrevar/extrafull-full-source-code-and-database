<?php
defined( 'CREW' ) or die( 'Restricted access' );

class PhotoViewEvent extends JView
{
  function display($tpl = null)
	{
		 $limit =  8;
		 $model = $this->getModel();
		 $limit = JRequest::getInt( 'limit', $limit);
		 $limitstart	= JRequest::getInt( 'limitstart', 0 );
		 $id	= JRequest::getInt( 'id' );
		 $model->event_id = $id;		  
	   $mypath = Basic::routerBase().'/photos/'.$id.'e/';
	   $imgs = $model->getImages( $limitstart, $limit );
		 //$cnt = $model->getImagesCount();
		 $cnt = JRequest::getInt('cnt',0);
		 $pagination = &JHTML::getPagination( $cnt, $limitstart, $limit, array('galPag', 'id', $id, $cnt), 'ajax', 10 );
		 $this->assignRef( 'mypath', $mypath );
		 $this->assignRef( 'imgs' , $imgs );
	   $this->assignRef( 'pagination',$pagination );
	   $this->setLayout( 'default_images' );
	   parent::display( $tpl );
  }	
}  