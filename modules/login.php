<?php
// Copyright by extrafull
// V: 14.III.2009

defined('CREW') or die('Restricted access');

$session = &Session::getInstance();
$base = Basic::routerBase();
$my_id = $session->userId;
if($my_id > 0) : 
	$menus = explode(',',MENU_USER_NAMES);
	?>
  <h3 class="normal"><span><?php echo $menus[1];?></span></h3>
  <ul id="logged">
	<li><a href="<?php echo $base;?>/clan/mojprofil"><?php echo $menus[1];?></a></li>
	<li><?php echo JHTML::profileLink($user->id, $menus[2]);?>
	<li><a href="<?php echo $base;?>/poruke"><?php echo $menus[3];?></a></li>
	<li><a href="<?php echo $base;?>/galerije/upload?private=1"><?php echo $menus[4];?></a></li>
	<li><a href="<?php echo $base;?>/index.php?option=members&view=request"><?php echo $menus[5];?></a></li>
	<li><a href="<?php echo $base;?>/slike"><?php echo $menus[6];?></a></li>
	<li><a href="<?php echo $base;?>/index.php?option=members&view=changes"><?php echo $menus[8];?></a></li>
	<li><a href="<?php echo $base;?>/index.php?option=members&view=lovers"><?php echo $menus[9];?></a></li>	
	<li><a href="<?php echo $base;?>/index.php?option=members&view=druk"><?php echo $menus[10];?></a></li>	
	<li><a href="#" onclick="document.getElementById('form-login').submit();return false;"><?php echo $menus[7];?></a></li>
  </ul>
  <form action="<?php echo $base;?>/korisnik/logout" method="post" name="login" id="form-login">
  </form>
<?php     
  $time = $session->get('updatesTimer');
  $check = true;  
  if ( isset($time) ){ //first login
    $last = date('Y-m-d H:i:s', $time );
    $session->set('updatesTimer', null );
  }
  else{
  	if ( time() - $session->time < CHECK_TIME ) $check = false;
    $last = date('Y-m-d H:i:s',$session->time );  
  }
  if ($check){
    $db = &Database::getInstance();
    $rv = '';
   	$db->setQuery( "SELECT count(*) FROM jos_jim WHERE who_id=$my_id AND readstate=0" );
   	$cnt = $db->loadResult();
   	if ( $cnt ){
     	$tmpS = sprintf ( UPDATES_PROFIL_MSGS, $cnt );
     	$rv = '<a href="'.$base.'/poruke">'.$tmpS.'</a><br/>';
   	}  
   	$query = "SELECT count(*) FROM jos_request WHERE id2=$my_id";
   	$db->setQuery( $query );
   	$cnt = $db->loadResult();
   	if ( $cnt ){
   		$tmpS = sprintf ( 'Imate novih zahteva : %s', $cnt );
     	$rv .= '<a href="'.$base.'/index.php?option=members&view=request">'.$tmpS.'</a><br/>';
		}
   	$query = "SELECT date FROM jos_members_comments WHERE who_id=$my_id ORDER BY id DESC LIMIT 1";
    $db->setQuery( $query );
    $date = $db->loadResult();
    if ( !empty($date)  &&  $date > $last ){
   		$rv .= JHTML::profileLink($my_id, 'Novi komentari' ).'<br/>';
		}
	/*	$query = "SELECT count(id) AS cnt,image_id FROM jos_photo_comments WHERE datetime>$last AND published=$my_id GROUP BY image_id";
    $db->setQuery( $query );
    $rows = $db->loadObjectList();
    for ($i=0;$i<count($rows);++$i)
    {
    	$row = &$rows[$i];
    	$rv .= JHTML::picLink($row->image_id, sprintf ( UPDATES_PROFIL_PHOTO_CMNTS, $row->cnt ) ).'<br/>';
    }
    */
    if ( $rv != '' ){
      echo '<div style="padding:2px">'.$rv.'</div>';
    }  
  }  
?>  
<?php else : ?>
<h3 class="normal"><span>Prijava</span></h3>
<form action="<?php echo $base;?>/korisnik/pjavi" method="post">
		<?php echo LOGIT;?><br/>
		<input class="specinput" type="text" name="usname" size="18" /><br/>
		<?php echo PASSWORD;?><br/>
		<input class="specinput" type="password" name="uspass" size="18" /><br/>
		<?php echo REMEMBER_ME; ?>
		<input type="checkbox" name="remember" value="yes" />
	<br><input type="submit" name="Submit" class="button" value="<?php echo LOGIN;?>" />
	<br><a href="<?php echo $base;?>/index.php?option=user&view=reset">
	<?php echo FORGOT_YOUR_PASSWORD; ?></a>
	<br><a href="<?php echo $base.'/korisnik/novi'; ?>">
	<?php echo REGISTER;?></a>
</form>
<?php endif; ?>