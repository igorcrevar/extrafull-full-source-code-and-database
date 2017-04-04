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
class My_Calendar
{
public $mark_today=true;	
public $raw;	
protected $component_view;
public $sql;
protected $link;
protected $month;
protected $year;
protected $day;
protected $today;

public function init($comp,$link,$year=0,$month=0,$day=0,$raw=true){
	$this->raw=$raw;
	$doc = &Document::getInstance();
  $doc->addScript( Basic::uriBase().'/modules/my_calendar.js' );
	$doc->addStyleSheet( Basic::uriBase().'/modules/my_calend.css' );	
	$this->component_view = $comp;
 	if ($link == null){
	  	$link =Basic::requestURI(array('year','month','day')); //samo apache
	}	
	if ( strpos($link,'?') !== false ){
		$link .= '&';
	}
	else{
		$link .= '?';
	}
	$this->link = $link;
	$this->year=$year;
	$this->month=$month;
	$this->day=$day;	
	list($cy,$cm,$this->today)=explode('-',date('Y-n-j'));
	if (!isset($this->year) || $this->year==0){
		$this->year=$cy;
		$this->month=$cm;
	}
	if ($cm!=$this->month || $cy!=$this->year || !$this->mark_today) $this->today=0;	
}

private function tooltip($tipList,$val,$link){
	$tips = '';
	for ($i=0;$i< count($tipList);++$i){
		if ($i > 0){
			 $tips .= ',';
		}
		$tips .= str_replace( ',', '', $tipList[$i]->name );
	}	
	//$tips = addslashes( $tips );
	$tips = str_replace( array("'",'"'), '', $tips );
	echo '<a href="'.$link.'" onmouseover="showPopUp(this,\''.$tips.
	'\');" class="tooltip" onmouseout="hidePopUp(this);">';
	echo $val;	
  echo '</a>';
}

public function genLink($year,$month){
	if ($this->raw)
	  echo '<a href="#" onclick="changeMonth(\''.$this->component_view.'\','.$year.','.$month.');return false;">';
	else{		
	  echo '<a href="'.$this->link.'year='.$year.'&month='.$month.'">';
	}
}

public function getWhere(){
	return ' a.date>=\''.$this->year.'-'.$this->month.'-1\' AND a.date<=\''.$this->year.'-'.$this->month.'-31\' ';
}

public function show(){
	$db=&JFactory::getDBO();	
	$db->setQuery($this->sql);
	$rows=$db->loadObjectList();
	$days=array();
	for ($i=0; $i< count($rows);++$i){
		$row = &$rows[$i];
		$days[$row->day][] = $row;
	}
  $month_name=explode(',',CALENDAR_MONTHS);
  $weekdays=explode(',',CALENDAR_DAYS);
  $days_in_month=array(31,28,31,30,31,30,31,31,30,31,30,31);
  $num_days=$days_in_month[$this->month-1];
	if ($this->month==2 && (($this->year%4==0 && $this->year%100!=0) || ($this->year%400==0)))
	  ++$num_days;
  echo '<div id="my_calendar"><table>';
  echo  '<caption>';
  if ($this->month==1)
		self::genLink($this->year-1,12);
  else
		self::genLink($this->year,$this->month-1);
  echo '&laquo;'.KPREV.'</a>&nbsp;';
  echo $month_name[$this->month].' '.$this->year.'&nbsp;';
  if ($this->month==12)
		self::genLink($this->year+1,1);
	else
	  self::genLink($this->year,$this->month+1);
	echo KNEXT.'&raquo;</a></caption>';
  echo '<tr>';
  foreach ($weekdays as $value)
		echo '<th>'.$value.'</th>';            
	echo '</tr>';
  $dy=date('N',mktime(0,0,0,$this->month,1,$this->year))-1;
	echo '<tr>';           
  if ($dy>0)
		for ($j=0;$j<$dy;++$j) echo '<td>&nbsp;</td>';
	for ($i=1;$i<=$num_days;++$i){
		if ($this->today!=$i) echo '<td>';
		else echo '<td class="today">';  
  	if (isset($days[$i]) && $i!=$this->day)
      $this->tooltip($days[$i],$i,$this->link.'year='.$this->year.'&month='.$this->month.'&day='.$i);
		else echo $i;
		++$dy;
		echo '</td>';
		if ($dy%7==0) echo '</tr><tr>';
	}	
	if ($dy%7!=0){
    while ($dy%7!=0){ 
			echo '<td>&nbsp;</td>';
			++$dy;
		}  
		echo '</tr>';
	}  
	echo '</table></div>';
}
}