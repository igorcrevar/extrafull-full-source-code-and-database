<?php
defined( 'CREW' ) or die( 'Restricted access' );

class MembersViewMyProfile extends JView
{
	function display( $tpl = null)
	{
		$doc =& JFactory::getDocument();
		$doc->addScript(JURI::base().'component/members/m.js');		
		$model = $this->getModel();
 		$user = & JFactory::getUser();
		if ( isProfileMod( $user->id ) ){
		 	 $id = JRequest::getInt( 'id', $user->id );
		 	 // ne moze samog sebe banovati niti obrisati
		 	 if ( $id != $user->id ){
		 	  	$this->assignRef( 'id', $id );
		 	 }
		}
		else{
			 $id = $user->id;
		}
		$model->id = $id;
    $list = $model->load();
	  $this->assignRef( 'list', $list );
    $beholders = $model->lastBeholders();
	  $this->assignRef( 'beholders', $beholders );
    $locs = $model->loadLocs();    
	  $this->assignRef( 'locs', $locs );
 	  $layout = 'default';
		parent::display( $tpl );
	}	  
}
?>
