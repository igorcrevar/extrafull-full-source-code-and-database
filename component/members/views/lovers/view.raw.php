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

class MembersViewLovers extends JView
{
	function display( $tpl = null)
	{
	  	$sort = JRequest::getInt( 'sort', 0 );
	    $limitstart	= JRequest::getInt( 'limitstart', 0);  
	    $limit = 8;
		  $layout = JRequest::getVar( 'layout', 'default' );
		  $model = $this->getModel();
	    $lovs = $model->get( $sort, $limitstart, $limit );
		  $this->assignRef( 'rows', $lovs );
		  $user = &User::getInstance();
		  $uid = $user->id;
		  $this->assignRef( 'user', $uid );
		  $cnt = JRequest::getInt('cnt',0);
	    $pagination = &JHTML::getPagination( $cnt, $limitstart, $limit, array('ajaxPag','id'), 'ajax' );
			$this->assignRef( 'pagination', $pagination );		  
			
		  $this->setLayout( 'default_list' );
		  $out = parent::loadTemplate($tpl, false);
		  echo '_label.innerHTML="';
		  $out = preg_replace("/\r?\n/m", "", $out);
		  echo str_replace( '"', '\"', $out );
		  echo '"';;
		  //parent::display();
	}
}	
?>