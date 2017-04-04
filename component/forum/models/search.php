<?php
/*=============================================================================
|| ##################################################################
||	Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||
|| ##################################################################
=============================================================================*/
 defined( 'CREW' ) or die( 'Restricted access' );

class ModelForumSearch extends JModel{  	
	var $ids = array();
	var $cnt = 0;
	var $MAXIMUM = 100;
	function init($sentence,$cid){
		$words = $this->str_word_count_utf8($sentence);
		$queryParam = '';
		foreach ($words as $word){
			if ( JString::strlen($word) < 3 ) continue;
			$queryParam .= '%'.$word.'%';
		}
		$db = &Database::getInstance();
		$queryParam = $db->Quote($queryParam);
  	$query = 'SELECT MIN(id) FROM #__forum_posts WHERE ';
  	$query .= 'subject LIKE '.$queryParam.' OR message LIKE '.$queryParam.' GROUP BY tid';
  	$db->setQuery($query);
  	$this->ids = $db->loadResultArray();
		if ( count($this->ids) > $this->MAXIMUM ){
			$this->ids = array_splice( $this->ids, $this->MAXIMUM );
		}
  	$this->cnt = count($this->ids);
	}
	
	function initWithIds($ids){
		$this->ids = array();
		$i = 0;
		foreach ($ids as $id){
			$id = intval($id);
			if ( $id ){
				if ( ++$i > $this->MAXIMUM ) break;
				$this->ids[] = $id;
			}
		}
		$this->cnt = count($this->ids);
	}
	
 	function str_word_count_utf8($string, $format = 0){
  	$WORD_COUNT_MASK =  "/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u";
    preg_match_all($WORD_COUNT_MASK, $string, $matches, PREG_OFFSET_CAPTURE);
    $result = array();
    foreach ($matches[0] as $match) {
        $result[$match[1]] = $match[0];
    }
    return $result;
  }	
    
  function getPosts($limitstart,$limit){
  	if ( $this->cnt == 0 || $this->cnt <= $limitstart) return array();
  	$ids = array();  	
  	for ($i = $limitstart; $i < $limitstart+$limit; ++$i ){
  		if ( $i >= $this->cnt ) break;
  		$ids[] = $this->ids[$i];
  	} 
  	$query = 'SELECT a.*,b.subject AS topic_subject,b.cid,d.name AS cat_name,c.name FROM #__forum_posts AS a JOIN #__forum_topics AS b ON a.tid=b.id JOIN #__forum_cats AS d ON b.cid=d.id LEFT JOIN #__users AS c ON a.who_id=c.id WHERE a.id IN ('.join(',',$ids).')';
  	return $this->_getList($query);
  }
}
?>