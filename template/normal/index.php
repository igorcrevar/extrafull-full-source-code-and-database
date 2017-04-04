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
defined( 'CREW' ) or die( 'Restricted index access' );
$user = &User::getInstance();
$session = &Session::getInstance();
global $mainframe;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $mainframe->getLanguage();?>" lang="<?php echo $mainframe->getLanguage();?>" >
<head>
  <?php echo $doc->toString();?>	
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
	<link rel="shortcut icon" href="<?php echo Basic::routerBase();?>/template/normal/images/favicon.ico" />
	<link href="<?php echo Basic::routerBase();?>/template/normal/tpl.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php echo Basic::routerBase();?>/template/normal/tpl.js" language="javascript"></script>
<!--[if lte IE 7]>
<style type="text/css"> 
#horiz-menu {margin-top: 1px;}
#adds a:hover {border-bottom: none;}
#horiz-menu a:hover {border-top: none;line-height: 22px;}
</style>
<![endif]-->
</head>
<body<?php echo $doc->getOnLoad();?>>	
	<div class="my_body">	     	
		<div id="logo">
			<div id="adds">
		  </div>
			<div id="horiz-menu"><ul class="menu">
<?php 
$menusRights = array(0,0,0,0,0,18,21,0);
$menusLinks = explode(',',MENU_LINKS);
$menus = explode(',',MENU_NAMES);
for ($i = 0; $i < count($menus); ++$i){
	if ( $menusRights[$i] <= $user->gid ){
		echo '<li><a href="'.Basic::uriBase().$menusLinks[$i].'">';
		echo '<span>'.$menus[$i].'</span></a></li>';
	}
}
?>				
			</ul></div>
		</div>	  	
		
		<div class="main_part">      	
			<div class="left-side">
				<?php
				require_once(BASE_PATH.DS.'modules'.DS.'login.php'); 
				?><br />
			</div>	
			
			<div class="right-side">
				<?php 
				/*if ($component == 'photo'){
					require_once(JPATH_BASE.DS.'modules'.DS.'breadcrumbs.php');    	  	 
				}*/
				$userMsg = $session->get('usermsgid', null);
		 		if ( $userMsg != null ){
		 			$tmp = JText::_($userMsg);
		 			if (!isset($tmp)){
		 				$tmp = $userMsg;
		 			}
		 	 		echo '<div id="userMsgs">'.$tmp.'</div>';
		 	 		$session->set('usermsgid', null);
		 		}				
		 		echo $componentEcho;

				if ($component == 'default'){
					require_once(BASE_PATH.DS.'modules'.DS.'whosonline.php'); 	 
				}
				?>				
			</div> 
			<div style="clear:left;"></div>
		</div>	 	
    	<br><br><br><br>
    	<div id="footer">
		<br><span style="text-align:left"><a href="<?php echo Basic::routerBase();?>/korisnik/kontakt?what=1"><?php echo RULES;?></a></span>&nbsp; <b>Copyright &copy;2007-2008 by Extrafull team</b>
  	</div>      		
	</div>	
  <script type="text/javascript" language="javascript">
  	var jbase='<?php echo Basic::routerBase();?>/';
  	var img_base = '<?php echo $mainframe->getImageDir();?>';
  	var component='<?php echo $component;?>';
  </script>
</body>	
</html>