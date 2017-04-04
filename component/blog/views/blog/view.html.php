<?php
/*=============================================================================
|| ##################################################################
||	Igor Crevar Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||	Contact			: crewce@hotmail.com
||
||	- Extrafull and all of its source code and files are protected by Copyright Laws.
||
||	- You can not use any of the code without Igor Crevar agreement
||
||	- You may also not remove this copyright screen which shows the copyright information and credits for Extrafull (Igor Crevar).
||
||	- Igor Crevar Extrafull is NOT a FREE software
||
|| ##################################################################
=============================================================================*/
defined( 'CREW' ) or die( 'Restricted access' );

class BlogViewBlog extends JView
{
  function display($tpl = null)
	{		
		  global $mainframe;
		  $doc = &Document::getInstance();
		  $model = $this->getModel();
		  $id	= JRequest::getInt( 'id' );
		  $model->id = $id;		  
		  $blog = $model->get();
		  if (!isset($blog)) return;	  
		  $limit =  8;
		  $limitstart	= JRequest::getInt( 'limitstart', 0 );
		  $doc->setDescription('Extrafull - '.$blog->username.' : '.substr($blog->text,0,140));
		  $doc->setTitle('Extrafull - blog by '.$blog->username.' : '.$blog->subject);
		  $doc->keywords = 'blog,'.$blog->username.','.$doc->keywords;
		  $pag = &JHTML::getPagination( $blog->comments, $limitstart, $limit, null, 'html' );
		  $comments = $model->getComments($limitstart, $limit);
		  $this->assignRef( 'blog' , $blog );
		  $this->assignRef( 'pag',$pag );
		  $this->assignRef( 'comments', $comments);
		  $this->assignRef( 'privileges', $model->privileges);
	    parent::display( $tpl );
  }
}  
?>