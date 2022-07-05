<?php // no direct access
defined('CREW') or die('Restricted access'); 
$user = &User::getInstance();
?>
<div>
	<?php 
$doc = &Document::getInstance();
$doc->addStyleSheet(JURI::base().'component/events/css.css');
require_once(BASE_PATH.DS.'cache.php');
require_once(BASE_PATH.DS.'modules'.DS.'crewMost.php');	
	if ($user->gid >= 18) crewMost::getCurrentUsers(); ?>
<div style="float:left;width:500px;padding-right:8px">
<?php $news = JText::_('NEWS_MY');
if (!empty($news)){
  $news = str_replace("{{BASE}}", Basic::uriBase(), $news);
?>
<div class="myWindow">		
<h3 class="normal">Obavestenje</h3>
<div style="padding:4px;color:#000;font-size:14px;font-family: Tahoma, sans-serif;"><?php echo $news;?></div>
</div>
<?php } ?>
<?php 
if ($user->gid < 18){
		echo '<div class="myWindow">';
		echo '<h3 class="normal">Iskoristi Extrafull da...</h3>';
    echo JText::_('REGISTRATION_STIMULATION');
    echo '<center><form action="'. Basic::routerBase().'/index.php">';
    echo '<input type="submit" class="button" value="Registruj se!"/>';
    echo '<input type="hidden" name="option" value="user"/>';
    echo '<input type="hidden" name="view" value="register"/>';
    echo '</form></center><br>';
    echo '</div>';
  }  
crewMost::renderLastGaleries();
crewMost::renderEvents();
crewMost::renderUsers1(); 
?>
</div>
<div style="float:left;width:312px">
<?php require_once(BASE_PATH.DS.'component'.DS.'forum'.DS.'modul.php'); ?>
<?php crewMost::renderPic('Najnovije privatne slike','getPrivate', $user->gid>=18);?>
</div>
<div style="clear:left"></div>
<?php crewMost::renderUsers2(); ?>
</div>
