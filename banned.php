<?php
defined( 'CREW' ) or die( 'Restricted access' );

function getIPBans(){
	$db = &Database::getInstance();
	$ntime = time() - 3600*24*4; //4 dana je validno
	$db->setQuery( 'SELECT * FROM #__banned_ip WHERE time>='.$ntime );
	$rows = $db->loadResultArray();
	return count($rows) ? $rows : array();
}
