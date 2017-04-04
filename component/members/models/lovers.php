<?php
 defined( 'CREW' ) or die( 'Restricted access' );

class MembersModelLovers extends JModel
{
  function get($sort,$limitstart,$limit)
  {
  	$add = '';
  	switch ($sort){
  		case 0: $order = 'a.time DESC';break;
  		case 1: $order = 'a.time ASC';break;
  		default: 
  		$order = 'grade DESC';
			$add = ',IF(a.vote_count=0,0.00,a.vote_sum/a.vote_count) as grade';
  	}
		$query = "SELECT a.*$add,b.name AS name1,b.avatar AS avatar1,c.name AS name2,c.avatar AS avatar2 FROM #__lovers AS a JOIN #__users AS b ON a.id1=b.id JOIN #__users AS c ON a.id2=c.id ORDER BY $order"; 		
		return $this->_getList( $query, $limitstart, $limit );  	
  }
  
  function getCnt(){
  	$db = $this->getDBO();
  	$query = 'SELECT count(*) FROM #__lovers';
		$db->setQuery( $query );
		return $db->loadResult();
  }
  

}
?>  