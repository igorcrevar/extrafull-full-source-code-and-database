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
class MembersModelMyProfile extends JModel
{ 
	var $id;	
	
	function lastBeholders(){
		$query = "SELECT a.from_id AS id,b.username FROM #__members_visit AS a JOIN #__users AS b ON b.id=a.from_id WHERE a.who_id=$this->id ORDER BY a.time DESC LIMIT 10";
    $rows = $this->_getList( $query );
		return $rows;
	}
	
	function update_is(){
		$is = JRequest::getString( 'is','' );
		$db = $this->getDBO();
		$is = $db->Quote($is);
		$query = 'UPDATE #__fb_users SET whatsup='.$is.' WHERE userid='.$this->id;
		$db->setQuery($query);
		if ($db->query()){
			$tm = time();
			if ($is != '\'\''){
				$query = 'REPLACE INTO #__changes SET who_id='.$this->id.',what=4,time='.$tm.',tag='.$is;
				$db->setQuery($query);
				$db->query();			
			}
		  return 'Promene zapisane';
		}  
		else
		  return 'Greska!';  
	}
	function update_profile()
	{		
		$db = $this->getDBO();
		$id = $this->id;
		$name = JRequest::getString( 'name' );
		$name = trim($name);
		if ( $name == ''  ||  $name == 'nema_ime' ){
		  return 'Moras uneti ime i prezime!'; 
		}
		$name = $db->getEscaped($name);
		$location = JRequest::getString( 'location' ); 
		$location = $db->getEscaped($location);
		$msn = JRequest::getString( 'MSN' ); 
		$msn = $db->getEscaped($msn);
		$yim = JRequest::getString( 'YIM' ); 
		$yim = $db->getEscaped($yim);
		$love = JRequest::getInt( 'love' );
		$gtalk = JRequest::getString( 'GTALK' ); 
		$gtalk = $db->getEscaped($gtalk);
		$skype = JRequest::getString( 'SKYPE' ); 
		$skype = $db->getEscaped($skype);
		$signature = JRequest::getString( 'signature' ); 
		if (JString::strlen($signature) > 300){
			$signature = JString::substr($signature,0,300);
		}
		$signature = $db->getEscaped($signature);
		$personalText = JRequest::getString( 'personalText' ); 
		if (JString::strlen($personalText) > 1200){
			$personalText = JString::substr($personalText,0,1200);
		}
		$personalText = $db->getEscaped($personalText);
		$birth = JRequest::getInt('birth_year','1946').'-'.JRequest::getInt('birth_month','1').'-'.JRequest::getInt('birth_day','1');
		$gender = JRequest::getInt('gender',1);
		$mood = JRequest::getInt('mood',2);
		if ($mood < 1) $mood = 1;
		$music = isset($_REQUEST['music']) ? $_REQUEST['music'] : null;			
		$music_val = 0;	
		if ( is_array( $music) ){
			 foreach ($music as $one){			 	 
			 	 $one = intval($one);
			 	 if (is_int($one)){
			 	 	 $music_val += $one;
			 	 }
			 }
		}
		$query = "UPDATE #__fb_users as b, #__users AS a SET a.name='$name', b.music=$music_val, b.love=$love, a.birthdate='$birth',a.gender=$gender,b.location='$location',". 
			"b.MSN='$msn',b.YIM='$yim',b.GTALK='$gtalk',b.SKYPE='$skype',b.signature='$signature',b.personalText='$personalText',b.mood=$mood WHERE a.id=b.userid AND b.userid=$id";
	  $db->setQuery( $query );
		$rv = $db->query();
		
		if ( $rv ){
			$tm = time();
			$db->setQuery('REPLACE INTO #__changes SET who_id='.$id.',what=1,time='.$tm);
			$db->query();
		  return 'Promene zapisane';
		}  
		else
		  return 'Greska!';  
	}
	
	function update_ban(){
		$db = $this->getDBO();
		//17.I
		$db->setQuery( 'DELETE FROM #__sessions WHERE userid='.$this->id);
		$db->query();
		//end 17.I
		$db->setQuery( 'UPDATE #__users SET block=1-block WHERE id='.$this->id);
		$rv = $db->query();
		if ( $rv )
		  return 'Promene zapisane';
		else
		  return 'Greska!';  
	}
	
	function update_locations(){		
		$db = $this->getDBO();
		$id = $this->id;
		$locs = $_REQUEST['locs'];
		if ( is_array( $locs) ){
			 for ($i = count($locs) - 1; $i >= 0; --$i){
			 	  $locs[$i] = intval($locs[$i]);
			 	  if (!is_int($locs[$i])){
			 	  	unset($locs[$i]);
			 	  }
			 }
		}
		
		$query = 'DELETE FROM #__members_locations where user_id='.$id;
		$db->setQuery($query);
		$db->query();
		if (count($locs)){
			foreach ($locs as $loc){
				$query = 'INSERT INTO #__members_locations VALUES('.$loc.','.$id.')';
				$db->setQuery($query);
				$db->query();
			}
		}
		return 'Promene zapisane';
	}
	
	function update_account(){
		$db = $this->getDBO();
		$id = $this->id;
		$user = & JFactory::getUser($id);
		$password = JRequest::getString( 'password','','POST', JREQUEST_ALLOWRAW );
		$password2 = JRequest::getString( 'password2','', 'POST', JREQUEST_ALLOWRAW );
		$email = JRequest::getString( 'email','', 'POST');	
		require_once(BASE_PATH.DS.'library'.DS.'userhelper.php');
		if ( JString::strlen($email)<3 || JString::strlen($email)>50 || !JUserHelper::isEmail($email) ) {			
			return JText::_('WRONG_EMAIL');
		}
		if ( $password != ''){
				if (JString::strlen($password)<3 || JString::strlen($password)>20  || $password != $password2 ) {
					return 'Lozinka mora biti dobro potvrdjena i imati >=3 znakova!';
				}
				$salt = JUserHelper::genRandomPassword(32);
				$crypt = JUserHelper::getCryptedPassword($password, $salt);
				$password = $crypt.':'.$salt;
				$update = ',password=\''.$password.'\'';
		}				
		$query = 'SELECT id FROM #__users WHERE email='.$db->Quote($email).' AND id!='. (int)$id.' FOR UPDATE';
		$db->setQuery( $query );
		$xid = intval( $db->loadResult() );
		if ($xid && $xid != intval( $id )) {			
			return JText::_('WRONG_EMAIL2');
		}
		$query = 'UPDATE #__users SET email='.$db->Quote($email).$update.' WHERE id='.$id;
		$db->setQuery( $query );
		$db->query();		
		return 'Promene zapisane';
	}
	
	function update_username(){
		$db = $this->getDBO();
		$id = $this->id;
		$username = JRequest::getString( 'username' );
		if (JString::strlen($username)<3) return 'Moras uneti barem 3 znaka!';
		
		if ($username != 'ERASED'){
			if ( isset($res)  && $res != ''){
				$username = $db->Quote($username);
				$query = 'SELECT username FROM #__users WHERE id != '.$id.' AND username='.$username.' FOR UPDATE';
				$db->setQuery( $query );
				$res = $db->loadResult(); 
				return 'Postoji takvo korisnicko ime!';
			}
			$query = 'UPDATE #__users SET username='.$db->Quote($username).' WHERE id='.$id;
		}
		else{
			$username = $db->Quote($username.$id);
			$db->setQuery('DELETE FROM #__sessions where userid='.$id);
			$db->query();			
			$db->setQuery('DELETE FROM #__photo_events where a_id='.$id);
			$db->query();
			$db->setQuery('DELETE FROM #__photo_images where private='.$id);
			$db->query();
			$db->setQuery('DELETE FROM #__photo_comments where user_id='.$id);
			$db->query();
			$db->setQuery('DELETE FROM #__members_comments where who_id='.$id.' OR from_id='.$id);
			$db->query();
			$db->setQuery('DELETE FROM #__members_friends where id1='.$id.' OR id2='.$id);
			$db->query();			
			$query = 'UPDATE #__users SET email=\''.$id.'@erased.com\',block=1,name=\'\',lastvisitDate=\'0000-00-00 00:00\',username='.$username.' WHERE id='.$id;
		}
		$db->setQuery($query);
		if (!$db->query()){
			return 'Greska!';
		}		
		return 'Promene zapisane';		  
	}
	 
	function update_picture()
	{
		$thumbWIDTH = 100;
 		$thumbHEIGHT = 75;
  	$midWIDTH = 256;
	  $midHEIGHT = 198;
	  $MIN_PICTURE = 100*75;
	  $MAX_PICTURE = 1600*1200;
	  $MAX_SIZE = 150 * 1024;
	  $path = BASE_PATH.DS.'avatars'.DS;
		$id = $this->id;
		if ( isset($_FILES['upload_picture']) &&  $_FILES['upload_picture']['error'] == UPLOAD_ERR_OK )
		{			
		  $tmpFile = $_FILES['upload_picture']['tmp_name'];
  	  $fn = stripslashes( $_FILES['upload_picture']['name'] );
  	  $pos = strrpos($fn,'.');
		  if ($pos)
  	  {
 	      $ext = strtolower(substr($fn, $pos));
  	  }	
  	  if ( $ext != '.jpg'  &&  $ext != '.gif'  && $ext != '.png' )
  	  {
  	    unlink($tmpFile);
  	  	return 'Mora biti ili jpg ili gif ili png ekstenzija!!';
  	  }
  	  list( $oldWidth, $oldHeight ) = getimagesize( $tmpFile );
	   	//if ( filesize( $tmpFile ) > $MAX_SIZE  ||  $oldWidth*$oldHeight < $MIN_PICTURE )
	   	if (  $oldWidth*$oldHeight > $MAX_PICTURE ||  $oldWidth*$oldHeight < $MIN_PICTURE )
	   	{
  	  	unlink($tmpFile);
  	  	return 'Prevelik ili premali fajl!!';
  	  }	
  	  if ( $oldWidth >= $oldHeight )
  	  {
  	    $thumbHEIGHT = ($thumbWIDTH*$oldHeight) / $oldWidth;
  	    $midHEIGHT = ($midWIDTH*$oldHeight) / $oldWidth;
  	  }
  	  else
  	  {
  	    $thumbHEIGHT = $thumbWIDTH;
  	    $midHEIGHT = $midWIDTH;
  	    $thumbWIDTH = ($thumbHEIGHT*$oldWidth) / $oldHeight;
  	    $midWIDTH = ($midHEIGHT*$oldWidth) / $oldHeight;
  	  }  
  	  $timeadd = substr(time(), -4);
  	  $timeadd = 't'.$timeadd;
  	  $thumb = $id.$timeadd.$ext;
  	  $mid = 'l_'.$id.$timeadd.$ext;  	  
  	  $dst = imageCreateTrueColor( $thumbWIDTH, $thumbHEIGHT );  	  
  	  $dst2 = imageCreateTrueColor( $midWIDTH, $midHEIGHT );
  	  if ( $ext == '.jpg' )
  	  {
  	    $src = imagecreatefromjpeg( $tmpFile );
  	    imagecopyresampled( $dst,$src,0,0,0,0,$thumbWIDTH,$thumbHEIGHT,$oldWidth,$oldHeight );
        imagejpeg( $dst, $path.$thumb );
        imagecopyresampled( $dst2,$src,0,0,0,0,$midWIDTH,$midHEIGHT,$oldWidth,$oldHeight );
        imagejpeg( $dst2, $path.$mid );
  	  }  
  	  else if ( $ext == '.gif' ) 
  	  {
  	    $src = imagecreatefromgif( $tmpFile );
  	    imagecopyresampled( $dst,$src,0,0,0,0,$thumbWIDTH,$thumbHEIGHT,$oldWidth,$oldHeight );
  	    imagegif( $dst, $path.$thumb );
        imagecopyresampled( $dst2,$src,0,0,0,0,$midWIDTH,$midHEIGHT,$oldWidth,$oldHeight );
        imagegif( $dst2, $path.$mid );
  	  }  
  	  else if ( $ext == '.png' ) 
  	  {
  	    $src = imagecreatefrompng( $tmpFile );
  	    imagecopyresampled( $dst,$src,0,0,0,0,$thumbWIDTH,$thumbHEIGHT,$oldWidth,$oldHeight );
  	    imagepng( $dst, $path.$thumb );   		
        imagecopyresampled( $dst2,$src,0,0,0,0,$midWIDTH,$midHEIGHT,$oldWidth,$oldHeight );
        imagepng( $dst2, $path.$mid );
  	  }     		
  	  unlink( $tmpFile );
 	    imagedestroy($dst2);
   	  imagedestroy($dst);
   		imagedestroy($src);            
     	$db = $this->getDBO();
     	//add
     	$query = "SELECT avatar FROM #__users WHERE id=$id";
     	$db->setQuery($query);
     	$rv = $db->loadResult();
     	if (isset($rv) && $rv != ''){
     	  unlink($path.$rv);
     	  unlink($path.'l_'.$rv);
     	}
     	//
		  $query = "UPDATE #__users SET avatar='$thumb' WHERE id=$id";
		  $db->setQuery( $query );
		  $rv = $db->query();
		  if ( $rv ){
				$tm = time();
			  $db->setQuery('REPLACE INTO #__changes SET who_id='.$id.',what=2,time='.$tm);
			  $db->query();		  	
        return 'Promena zapisana';
      }else
        return 'Greska!';  
		}
		else if (JRequest::getInt('delete',0) > 0){
			$db = $this->getDBO();
     	$query = "SELECT avatar FROM #__users WHERE id=$id";
     	$db->setQuery($query);
     	$rv = $db->loadResult();
     	$db->setQuery('UPDATE #__users SET avatar=NULL WHERE id='.$id);
     	if ( isset($rv) && $rv != '' && $db->query() ){
     	  unlink($path.$rv);
     	  unlink($path.'l_'.$rv);
     	  return 'Slika je obrisana';
     	}
     	else 
     	  return 'Greska!';			     				
		}
		else 
		  return 'Greska!!!';
	}
	
	function loadLocs(){
		 $id = $this->id;
		 $db = $this->getDBO();
  	 $query = 'SELECT location_id FROM #__members_locations WHERE user_id='.$id;
  	 $db->setQuery($query);
  	 $rows['my'] = $db->loadResultArray();
		 $query = 'SELECT id,name FROM #__photo_locations order by name';
		 $rows['all'] = $this->_getList($query);   	   	 
  	 return $rows;
	}
	
	function load()
	{
		 $id = $this->id;
  	 $query = "SELECT a.*,b.* FROM #__users AS a JOIN #__fb_users AS b ON b.userid=a.id WHERE a.id=$id";
  	 $rows = $this->_getList($query); 
  	 return $rows[0];
	}
}
?>