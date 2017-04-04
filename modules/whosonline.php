<?php
defined('CREW') or die('Restricted access');

echo '<div id="bottommodules">';
WhoisonlineHelper::show();
echo '</div>';

class WhoisonlineHelper
{
	public static function birthLast(){
		 global $mainframe;
		 $db = &Database::getInstance();
		 $date = date('m-d');
		 $query = "SELECT id,username,YEAR(birthdate) as year FROM #__users WHERE substr(birthdate,6,5)='$date'";
		 $db->setQuery($query);
		 $birth = $db->loadObjectList();
		 
	 	 $output2 = '';
		 if ( count($birth) )
		 {
		 	 $year = date('Y');
		 	 foreach ($birth as $row)
		 	 {
		 	 	 $tmp = $year - $row->year;
		 	 	 if ( $tmp > 0 )
		 	 	 {
		 	 	 	 if ($output2 != '') $output2 .= ', ';
		 	 	 	 $output2 .= JHTML::profileLink( $row->id, $row->username.'('.$tmp.')' );
				 }  
		 	 }		 	 
		 	 $output2 = '<img src="'.$mainframe->getImageDir().'birth.gif"/> '.BIRTHDAY_USERS.$output2.'<br/>';		 	 
		 }
	
	 	 return $output2;
	}
	
	public static function load()
	{		
	   $db		=& Database::getInstance();
		 
		 $query = 'SELECT userid AS id,username,time FROM #__sessions ORDER BY username ASC';
		 $db->setQuery($query);
		 $rows = $db->loadObjectList();
		 $guestCnt = 0;
		 //$old = time() - 22 * 60;
		 for ($i = count($rows) - 1; $i >= 0; --$i)
		 {
		 		$row = &$rows[$i];
		 		/*if ( $row->time < $old ){
		 			array_splice($rows, $i, 1);		 			
		 		}
				else if ($row->id == 0){
					$guestCnt ++;
					array_splice($rows, $i, 1);
				}*/
				if ($row->id == 0){
					$guestCnt++;
					array_splice($rows, $i, 1);
				}
		 }
		 $output1 = '';
		 if ( count($rows) ){
		 	 $old = '';
			 foreach ($rows as $row){
			 	 if ($old == $row->username) continue;
			 	 $old = $row->username;
			 	 if ($output1 != '') $output1 .= ', ';			 	 
				 $output1 .= JHTML::profileLink( $row->id, $row->username);
			 }
		 }		 
		 $output1 = sprintf(ONLINE_USERS,$guestCnt, count($rows ) ).': '.$output1;

 		 $cache = &Cache::getInstance();
 		 $cache->setLifeTime(60*61);
 		 $output2 = $cache->call(array('WhoisonlineHelper','birthLast') );
 		 
 		 $query = "SELECT id,username FROM #__users ORDER BY id DESC LIMIT 10";
		 $db->setQuery($query);
		 $reg = $db->loadObjectList();
		 $output3 = '';
		 if ( count($reg) ){
	 	 		foreach ($reg as $row)
	 	 		{
	 	 			if ($output3 != '') $output3 .= ', ';
 	 	   		$output3 .= JHTML::profileLink( $row->id, $row->username );
	 	 		}
	 	 }
	 	 $output3 = NEWEST_USERS.$output3;
		 echo $output1.'<br>'.$output2.$output3.'<br />';
		 return count($rows);
	}

	public static function show(){
	  $usersCnt = WhoisonlineHelper::load();
		list( $uCnt,$uDate ) = explode( '-', file_get_contents( BASE_PATH.DS.'max.frk' ), 2 );
		if ($uCnt < $usersCnt )
		{			
			$uDate = date("Y-m-d H:i");
			$uCnt = $usersCnt;
			file_put_contents( BASE_PATH.DS.'max.frk', $uCnt.'-'.$uDate );
		}
			
		$uDate = JHTML::date( $uDate );
		echo sprintf( MAX_ONLINE, $uCnt, $uDate ).'<br>';
	}
}
