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

class EventsViewNew extends JView
{
	function display( $tpl = null)
	{
		$model = $this->getModel();
		$layout = JRequest::getCmd('layout','default');
		$id = JRequest::getInt('id',0);
		if (!MyRights::canChangeEvents()){ echo JText::_('NO_ACCESS_CANNOT_ADD'); return;}
		switch ($layout){
			case 'locations':
			if ($id>0) $loc = $model->loadLocation($id);							  
			else $loc = null;
			$this->assignRef('loc',$loc);		
			break;
			default:;			
			if ($id>0) $event = $model->loadEvent($id);							  
			else $event = null;
			$this->assignRef('event',$event);		 
			$locs = $model->loadLocations();
			$this->assignRef('locs',$locs);		
		}
		$this->setLayout($layout);
		parent::display( $tpl );
	}	  
}
?>
