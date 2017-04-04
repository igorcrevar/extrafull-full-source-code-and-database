<?php
defined( 'CREW' ) or die( 'Restricted access' );

class MembersViewIps extends JView
{
  function display($tpl = null)
	{		
		  $user = &User::getInstance();
		  if ($user->gid < 18  ||  !isProfileMod($user->id) ) return;
		  $db = &Database::getInstance();
		  $ntime = time() - 3600*24*4; //4 dana je validno
		  $db->setQuery( 'DELETE FROM #__banned_ip WHERE time<'.$ntime );
		  $db->query();
		  $db->setQuery( 'SELECT b.*,u.username,u.name FROM #__banned_ip AS b JOIN #__users AS u ON b.mod_id=u.id' );
		  $ips = $db->loadObjectList();
			$this->assignRef( 'ips', $ips );
	    parent::display( $tpl );
  }
}  
?>