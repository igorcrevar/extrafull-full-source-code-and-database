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
 
class ModelBlogBlogs extends JModel
{  	
	var $who_id;
	var $day;
	var $month;
	var $year;
	var $type;
	function init($type = 0,$who=0,$day=0,$month=0,$year=0){
		$this->day = $day;
		$this->month = $month < 10 ? '0'.$month : $month;
		$this->year = $year < 10 ? '0'.$year : $year;
		$this->who_id=$who;
		$this->type = $type - 1;
	}
  function get( $limitstart, $limit )
  {
  	 $db = $this->getDBO();
 		 $list = array();
  	 $tmp = '';
  	 if ($this->who_id != 0){
  	 	 $tmp = ' AND a.who_id='.$this->who_id;
  	 }
  	 if ($this->type > -1){
  	 	 $tmp .= ' AND a.type='.$this->type;
  	 }
  	 if ($this->year != 0 && $this->month != 0){  	 	 
  	 	 if ($this->day != 0){
  	 	 	 $tmp .= " AND DATE(a.date)='$this->year-$this->month-$this->day'";
  	 	 }else{
  	 	   $tmp .= " AND DATE(a.date)>='$this->year-$this->month-1' AND DATE(a.date)<='$this->year-$this->month-31'";
  	 	 }
  	 	 $tmp = substr($tmp,5);
  	 	 $query = "SELECT count(*) FROM #__blogs AS a WHERE $tmp";
  		 $db->setQuery( $query );	 
	  	 $list['cnt'] = $db->loadResult();	 
  	 }
  	 else{
  	 	 $tmp = substr($tmp,5);
  	 	 $limitstart = 0;
  	 	 $limit = 14;
  	 	 $list['cnt'] = 0;	 
  	 }  	 
  	 if ($tmp != '') $tmp = 'WHERE '.$tmp;
		 $query = "SELECT a.id,a.type,a.who_id,a.date,a.subject,substr(a.text,1,150) as text,a.comments,a.vote_sum,a.vote_count,b.username FROM #__blogs AS a JOIN #__users AS b ON b.id=a.who_id $tmp ORDER BY a.id DESC"; 		
		 $list['rows'] = $this->_getList( $query, $limitstart, $limit );  	 
  	 return $list;
  }	
  
  function getDaysWithEvent()
  {
  	 if ($this->year == 0  || $this->month == 0){
  	 	 $this->year = intval(date('Y'));
  	 	 $this->month = intval(date('m'));
  	 }
  	 $tmp = "WHERE DATE(a.date)>='$this->year-$this->month-1' AND DATE(a.date)<='$this->year-$this->month-31'";
  	 if ($this->type > -1){
  	 	 $tmp .= ' AND a.type='.$this->type;
  	 }  	 
  	 if ($this->who_id != 0){
  	 	 $tmp .= ' AND a.who_id='.$this->who_id;
	  	 $query = "SELECT DAY(a.date) AS day,substr(a.subject,1,30) as name FROM #__blogs AS a $tmp";
  	 }
  	 else{
  	 	 $query = "SELECT DISTINCT DAY(a.date) AS day,b.username as name FROM #__blogs AS a JOIN #__users AS b ON b.id=a.who_id $tmp";
  	 }
  	 return $query;
  }  
}
?>