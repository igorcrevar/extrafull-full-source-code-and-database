<?php
defined('CREW') or die('Restricted access');
class crewMost
{	
	
	public static function generateDate($date=null){
		if ($date == null){
		  $howMany = 10;
      $day = date('j');
      $month = date('m');
      $year = date('Y');
    }
    else{
    	list($year,$month,$day)=explode('-',$date);
    	$howMany = 10;
    }  
    if ( $day - $howMany < 1 ){
    	if ( $month - 1 > 0 )
    		$month = $month - 1;
    	else{
    		$month = 12;
    		$year = $year - 1;
    	}
   		$day = 29 + ( $month == 2 ? 0 : 1) - ($howMany - $day);
    }
    else
      $day = $day - $howMany;
    return "'$year-$month-$day'";
	}
	
	public static function getEvents(){
	  $db = &Database::getInstance();
    $query = 'SELECT id,name,location_name,image,date FROM #__events WHERE feautured=1 AND DATE(date) >= DATE(now()) ORDER BY date';
    $db->setQuery( $query );
    $rows = $db->loadObjectList();
		if ( !count($rows) ) return '';
		$rv = '<div class="myWindow">';
    $rv .= '<h3 class="normal">'.STAT_EVENTS.'</h3>';    
		foreach ( $rows as $row ){		
    	$rv .= '<div class="ev2">';
    	$rv .= '<div class="ev-f2">';
    	if ( !empty($row->image) ){
    		$rv .= '<a href="'.Basic::routerBase().'/desavanja/event/'.$row->id.'">';
    		$rv .= '<img src="'.Basic::routerBase().'/events/'.$row->image.'t.jpg" />';
  	  	$rv .= '</a>';
    	}
    	else {
    		$rv .= '&nbsp;';
    	}
  		$rv .= '</div>';
  		$rv .= '<div class="ev-s2">';
  			$rv .= '<span class="ev-loc">';
    		$rv .= '<a href="'.Basic::routerBase().'/desavanja/event/'.$row->id.'">';
    		$rv .= $row->name;
    		$rv .= '</a>';
    	$rv .= '</span><br />';
    	$rv .= '<span class="ev-loc">'.$row->location_name.'</span> ';
  		$rv .= '<span class="ev-date">';
  		$rv .= JHTML::date($row->date,'date2');
    	$rv .= '</span><br />';
    	$rv .= JString::substr( $row->text, 0, 100 ).'...';
    		$rv .= '<a href="'.Basic::routerBase().'/desavanja/event/'.$row->id.'">';
    		$rv .= ' [detaljnije]';
    		$rv .= '</a>';
    	$rv .= '</div>';  	
    	$rv .= '<div style="clear:left"></div>';
    	$rv .= '</br />';
    	$rv .= '</div>';
  	}    
  	$rv .= '</div>';	    
  	return $rv;
	}
  
	public static function renderEvents(){
		$cache = &Cache::getInstance();
		$cache->setCaching(1);
		$cache->setLifeTime( 60 * 60 * 24 );
    $ocache = $cache->call( array('crewMost','getEvents') ); 
    echo $ocache;	
	}
	
	function news(){
		$rows = self::getNews();
    if (count($rows)<1) return;
    echo '<div class="myWindow">';
    echo '<h3 class="normal">Extrafull novosti</h3>'; 
	  foreach ( $rows as $row ){
		   echo '&nbsp;'.JHTML::_('date',$row->date);
		   if ($row->link) echo ' <a href="'.$row->link.'">'.$row->name.'</a><br>';
		   else echo " $row->name<br>";
		}   
		echo '</div>'; 
  } 
 	
	public static function renderUsers1()
	{
    $cache = &Cache::getInstance();
		$cache->setCaching( 1 );
	  $cache->setLifeTime(60*25+33);			
    $rows = $cache->call( array('crewMost','getUsers'),0 ); 
    if (count($rows)<1) return;
    echo '<div class="myWindow">';
    echo '<h3 class="normal">'.STAT_KARMA.'</h3>'; 
    $i = 0;
		foreach ($rows as $row)
    {
    	if ($i>0) echo ', ';
    	echo JHTML::profileLink($row->id, $row->username);	     
		  echo '('.$row->karma.'/'.$row->karma_time.')';
		  ++$i;
		}  
	  echo '</div>';	  
 	}
 	
 	public static function renderUsers2(){
    $cache = &Cache::getInstance();
		$cache->setCaching( 1 );
		$cache->setLifeTime(60*47+14);			
    $rows = $cache->call( array('crewMost','getUsers'),1 ); 
    if (count($rows)){
    	echo '<div class="myWindow">';
    	echo '<h3 class="normal">'.STAT_VISITS.'</h3>'; 
    	$i = 0;
			foreach ($rows as $row){
    		if ($i>0) echo ', ';
    		echo JHTML::profileLink($row->id, $row->username );
    		echo '('.$row->cnt.')';
		  	++$i;
			}  
	  	echo '</div>';
	  }	 		
 	}
 	
 	public static function getUsers($visit){
 		 $db = &Database::getInstance();
 		 if ($visit){
     	$time = time()-60*60*24*5;
     	$query = "SELECT a.who_id AS id,b.username,count(a.from_id) as cnt FROM #__members_visit AS a JOIN #__users AS b ON a.who_id=b.id WHERE a.time>=$time GROUP BY a.who_id order by count(a.from_id) DESC limit 20";
 		 }else{
 		 	 $query = "SELECT a.userid AS id,a.karma,a.karma_time,b.username FROM #__fb_users AS a JOIN #__users AS b ON a.userid=b.id order by a.karma*2+a.karma_time DESC limit 10";
 	   } 	   
     $db->setQuery( $query );
     $rows = $db->loadObjectList(); 
     return $rows;		
 	}
 	
	public static function renderLastGaleries()
	{
    $rows = crewMost::getLastGaleries();
    echo '<div class="myWindow">';
    echo '<h3 class="normal">'.STAT_GALLERIES.'</h3>'; 
    $tmp = '';
    $first = true;
    $address = '';    
	  foreach ($rows as $row)
    {
		  if ($address != $row->address){
		  	$address = $row->address;
		  	$newAddress = true;
		  }
		  else $newAddress = false;
    	if ($tmp==$row->date){ 
    	  echo ' , ';  
    	  if ($newAddress && $address != '')
    	    echo $address.': ';   	  
    	}
    	else
    	{
    		if (!$first) echo '<br>';
    		echo '&nbsp;<b>'.JHTML::date($row->date,'date2').' </b> - ';
    		if ($address != '')
    		  echo $address.': ';
    		$tmp = $row->date;
      }
      echo JHTML::galleryLink($row->id,$row->location);
		  $first = false;
		}  
	  echo '</div>';
 	}
 	
	public static function getCurrentUsers(){
  	$cache = &Cache::getInstance();
  	$cache->setLifeTime(15); //15 seconds
		$cache->setCaching( 1 );
    $rows = $cache->call( array('crewMost','getCurrentUsersHTML') );
    echo $rows;
  }
  
 	public static function getCurrentUsersHTML(){
  	$db = &Database::getInstance();
    $query = "SELECT a.who_id,b.username,b.avatar FROM #__members_visit AS a JOIN #__users AS b ON a.who_id=b.id ORDER BY a.time DESC LIMIT 6";
    $db->setQuery( $query );
    $rows = $db->loadObjectList();
    if ( !count($rows) ) return;
    $rv = '<div class="myWindow">';
    $rv .= '<h3 class="normal">Profili koji se trenutno gledaju</h3>';
    foreach( $rows as $row ){
			$rv .= '<div class="flLeft" style="height:120px">';
			if ( empty($row->avatar) ){
	      $row->avatar = 's_nophoto.jpg';
	    }
      $photo = '<img src="'.Basic::routerBase().'/avatars/'.$row->avatar.'" class="my_thumb" />';
     	$rv .= JHTML::profileLink($row->who_id, $photo);
     	$rv .= '<br />';
   		$rv .= JHTML::profileLink($row->who_id, JString::substr($row->username,0,15) );
   		$rv .= '</div>';   	
   	}	  
   	$rv .= '<div class="clearB"></div>';
   	$rv .= '</div>';
    return $rv;
  }
  
 	public static function renderPrivate(){
 		self::renderPic('Najnovije privatne slike','getPrivate');
 	}
  
	public static function renderCommented(){
		self::renderPic('Najnovije javne slike sa najvise komentara','getCommented');
	}
  
  public static function renderViewed(){
		self::renderPic('Najnovije najgledanije javne slike','getViewed');
	}
  
  public static function renderVoted(){
		self::renderPic('Najnovije najbolje javne ocenjene','getVoted');
	}	
	
  public static function renderPic($title,$func,$access){
  	$cache = &Cache::getInstance();
		$cache->setCaching( 1 );
    $rows = $cache->call( array('crewMost',$func) );
    echo '<div class="myWindow">';
    echo '<h3 class="normal">'.$title.'</h3>';
	  for ($i=0;$i< count($rows);++$i){
    	$row = &$rows[$i];
			echo '<div class="flLeft">';
      $photo = '<img src="'.Basic::routerBase().'/photos/'.$row->event_id.'e/'.$row->file_name.'" class="my_thumb" />';
      if ($access){
      	 echo JHTML::picLink($row->id, $photo) ;
      }
      else {
      	echo $photo;
      }
      echo '<br>';
      if ($func=='getHallOfFame') echo 'ocena '.number_format($row->has_grade,2).' ('.$row->cnt.')';
      else if ($func=='getCommented') echo '[komentara : '.$row->comments.']';
   		else if ($func=='getPrivate') echo JHTML::profileLink($row->a_id, JString::substr($row->username,0,15) );
   		else echo '[pregleda : '.$row->number_of_views.']';
   		echo '</div>';   	
   	}	  
   	echo '<div class="clearB"></div>';
   	echo '</div>';
  }
  
  public static function lastForum()
  {
  	 $cache = &Cache::getInstance();
   	$cache->setLifeTime(60*11);  	
		$cache->setCaching( 1 );
    $rows = $cache->call( array('crewMost','getForumPosts') ); 
    echo '<div class="myWindow">';
    echo '<h3 class="normal">'.STAT_FORUM.'</h3>'; 
    foreach ($rows as $row)
    {
    	$tmp = floor(($row->cnt-1) / 10) * 10;
    	if ( $tmp > 0 )
    	  $tmp = '&start='.$tmp;
    	else
    	  $tmp = '';  
		  echo '<a href="'.JRoute::_('index.php?option=com_fireboard&func=view&catid='.$row->catid.'&id='.$row->thread.$tmp.'#'.$row->id).'">';
		  echo '<b>&nbsp;'.$row->subject.'</b> ['.$row->catname.'] od <b>';
		  echo $row->username.'</b></a><br>';
		}  
	  echo '</div>';
  }
  
  public static function getForumPosts(){  	    
  	 $db = &Database::getInstance();
  	$querytime = time() - 3600 * 24*2; //u poslednjih 5 dana
  	$query = "SELECT thread,catid,count(id) as cnt,MAX(time) as time FROM #__fb_messages GROUP BY catid,thread ORDER BY time DESC LIMIT 12";
    $db->setQuery( $query );
    $rows = $db->loadObjectList();
  	for ($i=0;$i<count($rows);++$i)
    {
    	$time = $rows[$i]->time;
    	$catid = $rows[$i]->catid;
    	$thread = $rows[$i]->thread;
  	  $query = "SELECT b.name as catname,a.id,a.name as username,a.subject FROM #__fb_messages AS a LEFT JOIN #__fb_categories AS b ON b.id=a.catid WHERE a.thread=$thread AND a.catid=$catid AND a.time=$time";
      $db->setQuery( $query );
      $row = $db->loadObjectList();
      $row = $row[0];
      $rows[$i]->id = $row->id;
      $rows[$i]->catname = $row->catname;
      $rows[$i]->username = $row->username;
      $rows[$i]->subject = $row->subject;
    }
    return $rows;
  }	
  
	public static function getPrivate(){
	  $db = &Database::getInstance();
    $date = self::generateDate();
		
		$query = "select max(a.id) from #__photo_images as a join #__photo_events as b On b.id=a.event_id group by b.a_id order by max(a.id) DESC";
		$db->setQuery($query, 0, 6);
		$ids = $db->loadResultArray();
		$idsString = join(',', $ids);
		$query = "SELECT a.*,b.a_id,c.username FROM #__photo_images AS a JOIN #__photo_events AS b ON b.id=a.event_id AND b.published>1 JOIN #__users AS c ON b.a_id=c.id WHERE a.id in ($idsString) ORDER BY a.id DESC";
    $db->setQuery( $query );
    return $db->loadObjectList();
  }
  
	public static function getViewed(){
	   $db = &Database::getInstance();
    $date = self::generateDate();
    $query = "SELECT a.* FROM jos_photo_images AS a JOIN jos_photo_events AS b ON b.id=a.event_id AND b.date>=$date AND b.published=1 ORDER BY a.number_of_views DESC LIMIT 4";
    $db->setQuery( $query );
    return $db->loadObjectList();
  }
  
	public static function getCommented(){
  	 $db = &Database::getInstance();
  	$date = self::generateDate();
    $db->setQuery( "SELECT a.* FROM jos_photo_images AS a JOIN jos_photo_events as b ON a.event_id=b.id AND b.date>=$date AND b.published=1  GROUP BY a.id order by a.comments DESC LIMIT 4" );
    return $db->loadObjectList();;    
  }
  
  public static function getVoted(){
  	 $db = &Database::getInstance();
  	$date = self::generateDate();
    $db->setQuery( "SELECT a.*,avg(b.grade) AS has_grade,count(b.grade) as cnt FROM jos_photo_images AS a JOIN jos_photo_votes AS b ON a.id=b.image_id AND b.published=1 JOIN jos_photo_events AS c ON c.id=a.event_id AND c.date>=$date GROUP BY a.id order by has_grade DESC,cnt DESC LIMIT 4" );
    return $db->loadObjectList();
  }
  
  public static function getNews(){
  	$path=JPATH_ROOT.DS.'news.xml';   
  	$rows=null;
  	if (!file_exists($path)) return $rows;
		$xml =& JFactory::getXMLParser('Simple');
		if ($xml->loadFile($path))
		{
			$doc =& $xml->document;
			$cnt = $doc->attributes('count');
			if ($doc->name()!='news' || $cnt==0 ) return $rows;
			$c = $doc->children();
			for ($i=0;$i< $cnt;++$i)
			{
				 $els = $c[$i]->children();
				 foreach ($els as $el)
				 {
				 	 $tmp = $el->name();
				 	 $rows[$i]->$tmp = $el->data();
				 }
			}
	  }
    return $rows;
  }
  
  public static function getLastGaleries(){
  	$db = &Database::getInstance();
  	$db->setQuery( "SELECT a.id,DATE(a.date) AS date,b.name as location,b.address FROM #__photo_events AS a JOIN #__photo_locations AS b ON b.id=a.l_id WHERE a.published=1 ORDER BY a.id DESC,b.address ASC LIMIT 20" );
    return $db->loadObjectList();
  }
}