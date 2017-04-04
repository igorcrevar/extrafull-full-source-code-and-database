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
 
class ModelPhotoImageUpload extends JModel
{
 var $private;
 
 function deleteEvent($id,$userId){
 	 $db = $this->getDBO();
 	 $db->setQuery( 'SELECT id FROM #__photo_events WHERE id='.$id.' AND a_id='.$userId );
 	 if ( !intval($db->loadResult()) ) return -2;
 	 $db->setQuery( 'SELECT id,file_name FROM #__photo_images WHERE event_id='.$id );
 	 $rows = $db->loadObjectList();
 	 if ( $db->isError() ) return -1;
   $path = BASE_PATH.DS.'photos'.DS.$id.'e'.DS;  	    
 	 if ( count($rows) ){
 	 		$in = '';
 	 		foreach ($rows as $row){
 	 	 		if ( $in != '' ) $in .= ',';
 	 	 		$in .= ''.$row->id;
	   		@unlink($path.'b_'.$row->file_name);
		 		@unlink($path.$row->file_name);
 	 		}
 	 		$in = '('.$in.')';
	 		$db->setQuery( 'DELETE FROM #__photo_votes WHERE image_id in '.$in );
   		$db->query();
   		$db->setQuery( 'DELETE FROM #__photo_favourites WHERE image_id in '.$in );
   		$db->query();
 	 		$db->setQuery( 'DELETE FROM #__photo_comments WHERE image_id in '.$in );
	 		$db->query();
	 		$db->setQuery( 'DELETE FROM #__photo_images WHERE event_id='.$id );
	 		$db->query();
	 }	
	 	 	 		 
 	 rmdir($path);
 	 $db->setQuery( 'DELETE FROM #__photo_events WHERE id='.$id );
 	 $db->query();
 	 return 1;
 }
  
 function makeEvent()
 {
 	  $c_id = JRequest::getInt( 'c_id',0,'POST' );
 	  $l_id = JRequest::getInt( 'l_id',0,'POST' );
 	  $options = JRequest::getInt('options',0);
 	  $name = JRequest::getString( 'name', '','POST' );
 	  $name = trim($name);
    $description = JRequest::getString( 'description', '','POST' );
    $id = JRequest::getInt( 'event_id', -1,'POST' );
 	  $private = $this->private;
 	  $user = & User::getInstance(); 
 	  $a_id = $user->id;
 	  //nema prava kreiranja javne ili nema prva kreiranje privatne?
 	  if ( $user->gid < 18  || ($user->gid<21 && $private!=1) ) return -1;  	  
 	  $db		= &Database::getInstance(); 
 	  $description = $db->Quote($description);    
 	  if ($private == 0){
 	  	$date = JRequest::getString( 'date', null, 'POST' );
 	  	if (strpos($date,'.') !== FALSE ){
 	  		$tmpD = explode('.',$date);
 	  		$date = $tmpD[2].'-'.$tmpD[1].'-'.$tmpD[0];
 	  	}
 	  	$date = $db->Quote($date);
 	  	$pub = 0;
 	  }
 	  else{
 	    $MAX_FOR_USER = 2;
 	  	$db->setQuery( "SELECT count(*) FROM #__photo_events WHERE a_id=$a_id AND published=2" );
		  $cnt = $db->loadResult();
		  if ( $id <= 0 && $cnt == $MAX_FOR_USER )return -666; 
		  $pub = 2;
		  if ( empty($name) ) $name = 'Galerija '.$cnt;
			$date = 'now()'; 	  
 	  }	 
		$name = $db->Quote($name);
 	  if ($id > 0 )
 	  {
 	  	$query = "UPDATE #__photo_events SET name=$name,description=$description,date=$date,c_id=$c_id,l_id=$l_id,options=$options WHERE id=$id AND a_id=$a_id";
 	  	$db->setQuery( $query );
 	  	return $db->query() ? $id : -1;
 	  }
 	  $db->setQuery( "INSERT INTO #__photo_events (c_id,l_id,a_id,date,name,description,published,options) VALUES ($c_id,$l_id,$a_id,$date,$name,$description,$pub,$options)" );
		if ( !$db->query() ) return -1;
		$db->setQuery( "SELECT LAST_INSERT_ID() FROM #__photo_events WHERE a_id=$a_id" );
		$id = $db->loadResult();
		if ( !$id ) return -1;
		$tmp = BASE_PATH.DS.'photos'.DS.$id.'e'.DS;
		$rv = mkdir( $tmp, 0755 );
		return $rv ? $id : -1; 
 }	
 
 function copyFiles( $id, $ajax = true )
 {
 	 $private = $this->private;
   $MAX_FOR_USER = 20;
 	 $user = User::getInstance(); 
   if ( $user->gid < 18  || ($user->gid<21 && $private==0) ) 
     return -10110; 
 	 if ( isset($_FILES['upload_file']) &&  $_FILES['upload_file']['error'] == UPLOAD_ERR_OK && $id > 0 )
   { 
   	 $path = BASE_PATH.DS.'photos'.DS.$id.'e'.DS;
  	 $tmpFile =$_FILES['upload_file']['tmp_name'];
  	 $fn = stripslashes( $_FILES['upload_file']['name'] );
   	 $db = $this->getDBO();
     //nadji trenutni broj slika u galeriji i izbaci obicnog registrovanog koji ima previse(kasnije)
     $query = "SELECT a_id,image_count FROM #__photo_events WHERE id=$id";
     $row = $this->_getList($query);
     $row = $row[0];
		 $image_count = $row->image_count;

		 if (!file_exists($path)) {
				@mkdir($path);
		 }
		 
		 if ( !file_exists($path) || $image_count == null || $row->a_id != $user->id )
       return -$row->a_id;
     if ( $private == 1  &&  $image_count == $MAX_FOR_USER ) //Max for user
       return -666;
 	   $imgTypes = array('.' => 0, '.jpg' => 1,'.jpeg' => 2,'.gif' => 3,'.png' => 4);
	   $MIN_PICTURE = 100*150;
	   $MAX_PICTURE = 3600*2800;
	   $MAX_LENGTH = 500;	   
	   $ext = '.';
 	   $pos = strrpos($fn,'.');
		 if ($pos  !== false){
 	     $ext = strtolower(substr($fn, $pos));
  	 }	
     if ($imgTypes[$ext])
     {     	
     	 ++$image_count;
     	 $pic_name = $db->quote( JRequest::getString('file_name','') );
     	 $fileName = time().'p'.$image_count.$ext;
	   	 $filePath = $path.DS.$fileName;
     	 if ( $ajax )
     	 {
     	   $handle = fopen($tmpFile, 'r');
   			 $contents = fread($handle, filesize($tmpFile));
   			 fclose($handle);
   			 $contents = preg_replace("/\+/", "%2B", $contents);
   			 $handle = fopen($filePath, 'w');
   			 fwrite($handle, urldecode($contents));
   			 fclose($handle);
   		 }
   		 else
   			 rename( $tmpFile, $filePath);	
 			 @unlink($tmpFile); 
 			 chmod ($filePath, 0755); 
   		 list( $oldWidth, $oldHeight ) = getimagesize( $filePath );
	   	 if ( $oldWidth*$oldHeight > $MAX_PICTURE  ||  $oldWidth*$oldHeight < $MIN_PICTURE )
	   	 {
  	  	 unlink($filePath);
  	  	 return -200;
	   	 }  
 			 
   		 if ( $oldWidth >= $oldHeight )
  	   {
 			 		$thumbWIDTH = 100;
 			 		$thumbHEIGHT = (int)($thumbWIDTH * $oldHeight / $oldWidth);
 			 		if ($thumbHEIGHT > 75){
 			 		  $thumbWIDTH = (int)( 75 * $thumbWIDTH / $thumbHEIGHT );
 			 	  	$thumbHEIGHT = 75;
 			 		}
  	   	  $midWIDTH = ($oldWidth >= $MAX_LENGTH) ? $MAX_LENGTH : $oldWidth;  	    	
  	    	$midHEIGHT = (int)($midWIDTH * $oldHeight / $oldWidth);
  	   }
  	   else
  	   {  	   		  	   	
 			 		$thumbHEIGHT = 75;
 			 		$thumbWIDTH = (int)( $thumbHEIGHT * $oldWidth / $oldHeight );
  	   	  $midHEIGHT = ($oldHeight >= $MAX_LENGTH) ? $MAX_LENGTH : $oldHeight;  	    	
	  	    $midWIDTH = (int)($midHEIGHT * $oldWidth / $oldHeight);
  	   }  
  	   
   		 $thumb = $path.$fileName;
 			 $mid = $path.'b_'.$fileName;  	
 			 switch ($ext){
 			 		case '.png':
 			 		$src = imagecreatefrompng($filePath);
 			 		break;
 			 		case '.gif':
 			 		$src = imagecreatefromgif($filePath);
 			 		break; 	
 			 		default:		 		
 			 		$src = imagecreatefromjpeg($filePath);
 			 }		  
  		 
   		 $dst = ImageCreateTrueColor($thumbWIDTH,$thumbHEIGHT);
   		 imagecopyresampled($dst,$src,0,0,0,0,$thumbWIDTH,$thumbHEIGHT,$oldWidth,$oldHeight);
   		 imagejpeg($dst,$thumb);
       imagedestroy($dst);      		
       if ( ($oldWidth != $midWIDTH  &&  $oldHeight != $midHEIGHT) || $private==1 )
       { 
   			  $dst2 = ImageCreateTrueColor($midWIDTH,$midHEIGHT);
      	  imagecopyresampled($dst2,$src,0,0,0,0,$midWIDTH,$midHEIGHT,$oldWidth,$oldHeight);
      	  imagejpeg($dst2,$mid);
      	 	imagedestroy($src);
      		$src = $dst2;
   		 }		
       if ( $private == 0  &&  file_exists(JPATH_COMPONENT.DS.'views'.DS.'watermark.png'))
       {
      	 $watermark = imagecreatefrompng(JPATH_COMPONENT.DS.'views'.DS.'watermark.png');
         $watermark_width = imagesx($watermark);  
         $watermark_height = imagesy($watermark);    
         $dest_x = $midWIDTH - $watermark_width;  
         $dest_y = $midHEIGHT - $watermark_height - 16;  
         imagecopy($src, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);
         imagejpeg($src,$mid); 
         imagedestroy($watermark);          
       }  
       imagedestroy($src);
       $prVal = ($private == 1) ? $user->id : 0;
       $time = time();
   	   $db->setQuery("insert into #__photo_images (event_id,file_name,name,private,time) values ($id,'$fileName',$pic_name,$prVal,$time)");
			 if (!$db->query())
   	   {
   	  	 unlink($thumb);
   	  	 unlink($mid);
   	     return -150;
   	   }	
   	   if ($private == 1){   	       
   	   		$sql = "update #__photo_events set file_name='$fileName',image_count=$image_count,published=2,date=now() where id=$id";
					$tm = time();
			  	$db->setQuery('REPLACE INTO #__changes SET who_id='.$user->id.',what=3,time='.$tm.',tag=\''.mysql_insert_id().'\'');
			  	$db->query();		   	   		
   	   }		
   	   else
   	      $sql = "update #__photo_events set file_name='$fileName',image_count=$image_count,published=1 where id=$id";		
    	 $db->setQuery( $sql );
    	 $db->query();
    	 if (!$db->query())
    	   return -111;  
    	 return 1;  
   	 }
   } 
   return -1;
 }
    
  function getEvents()
  {
  	$user = & User::getInstance(); 
 	  $a_id = $user->id;
 	  if ( $this->private == 0 )
  	  $query = "SELECT a.id,CONCAT(DATE(a.date),' @ ',b.name) AS name from #__photo_events AS a JOIN #__photo_locations AS b ON a.l_id=b.id WHERE a.published<2 AND a.a_id=$a_id  ORDER BY a.id DESC LIMIT 8";
  	else
  	  $query = "SELECT id,name from #__photo_events WHERE a_id=$a_id AND published=2 ORDER BY name";
   	$rows = $this->_getList( $query );		   	
    return $rows;
  }
  function getEvent($event_id){
  	$user = & User::getInstance();
 	  $a_id = $user->id;  	
  	if ( $this->private == 0 )
  	  $query = "SELECT * from #__photo_events WHERE published<2 AND a_id=$a_id AND id=$event_id";
  	else
  	  $query = "SELECT * from #__photo_events WHERE published=2 AND a_id=$a_id AND id=$event_id";
   	$rows = $this->_getList( $query );		
    return $rows[0];
  }
  function getCatLoc($row)
  {
  	$query = 'SELECT id AS c_id,name FROM #__photo_categories order by p_id asc,name asc';
  	$rows = $this->_getList( $query );
		$lists['c_id'] = JHTML::_('select.genericlist', $rows, 'c_id', 'class="inputbox" size="1"','c_id', 'name', $row ? $row->c_id : -1);
		$query = 'SELECT id AS l_id,name FROM #__photo_locations  WHERE hasPictures=1 order by name asc';
  	$rows = $this->_getList( $query );
  	$lists['l_id']		= JHTML::_('select.genericlist', $rows, 'l_id', 'class="inputbox" size="1"','l_id', 'name', $row ? $row->l_id : -1);
  	return $lists;
  }
}
?>