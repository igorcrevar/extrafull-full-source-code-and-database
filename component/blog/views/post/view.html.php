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
class BlogViewPost extends JView
{
  function display($tpl = null)
	{		
		$id = JRequest::getInt('id',0);
		$user = &User::getInstance();
		$param = JRequest::getString('param', null);
		if ($param != null){
				$val = new stdClass();
				$param = stripslashes($param);
				$param = unserialize(urldecode($param));
				$val->subject = $param->a;
				$val->text = $param->b;
				$val->type = $param->c;
				$val->options = $param->d;
		}
	  else{	
			if ($id){
				$db = &Database::getInstance();
				$db->setQuery('SELECT type,subject,text,options FROM #__blogs WHERE id='.$id.' AND who_id='.$user->id);
				$val = $db->loadObject();
				if ($val){
					$val->type++;
				}
			}
			else{
				$val = new stdClass();
				$val->subject = '';
				$val->type = -1;
				$val->text = '';
				$val->options = 2;
			}
		}
		$this->assignRef('id',$id);
		$this->assignRef('val',$val);
		parent::display( $tpl );
  }
}  
?>