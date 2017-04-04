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
function editor_show($taname, $oldmsg,$type,$maxchars,$xSize = 60, $ySize = 12 ){
	 $doc = &Document::getInstance();
	 $doc->addStyleSheet( JURI::base().'editor/editor.css');
	 $doc->addScript( JURI::base().'editor/editor.js' );
 ?>
  <div id="editor">
  	<?php if ($type == 'forum'){
  	echo '<div id="editor-topics">';	
    $base = BASE_PATH.DS.'editor'.DS.'topics'.DS;
    $dirs = opendir($base);  
    $i = 0;
    while ($f_n = readdir($dirs)) {
    	if ( $f_n != "." && $f_n != ".."){
    		list($rn,$ext) = explode('.',$f_n);
    		$add = ($i == 0) ? 'CHECKED' : '';
    		echo '<input type="radio" name="topic-icon" value="'.substr($rn,4).'" '.$add.'><img src="/editor/topics/'.$f_n.'"/> &nbsp;';
    	}
    	++$i;
    }
  	closedir ($dirs);
  	echo '</div>';
  	}
  	?>
   	 	 <a href="javascript:addYourTag('b')" onMouseOver="helpline('b')"><img src="<?php echo JURI::base();?>/editor/images/bbc_b.gif"/></a>
  	 	 <a href="javascript:addYourTag('i')" onMouseOver="helpline('i')"><img src="<?php echo JURI::base();?>/editor/images/bbc_i.gif"/></a>
  	 	 <a href="javascript:addYourTag('u')" onMouseOver="helpline('u')"><img src="<?php echo JURI::base();?>/editor/images/bbc_u.gif"/></a>
  	 	 <a href="javascript:addYourTag('url')" onMouseOver="helpline('url')"><img src="<?php echo JURI::base();?>/editor/images/bbc_url.gif"/></a>
  	 	 <a href="javascript:addYourTag('img')" onMouseOver="helpline('img')"><img src="<?php echo JURI::base();?>/editor/images/bbc_img.gif"/></a>
  	 	 <a href="javascript:addYourTag('center')" onMouseOver="helpline('center')"><img src="<?php echo JURI::base();?>/editor/images/bbc_code.gif"/></a>
  	 	 <a href="javascript:addYourTag('quote')" onMouseOver="helpline('quote')"><img src="<?php echo JURI::base();?>/editor/images/bbc_quote.gif"/></a>
  	 Font : <select name="fontsize" onChange="addYourTag('size',this.value)" onMouseOver="helpline('size')">
         <option value="50%" >Tiny</option>
         <option value="75%" >Small</option>
         <option value="100%" selected >Normal</option>
         <option value="150%" >Large</option>
         <option  value="200%" >Huge</option>
      </select><?php if ($xSize < 45) echo '<br/><br/>';?>
  	 <select name="fontcolor" onChange="addYourTag('color',this.value)" onMouseOver="helpline('color')"  >
        <option value="black" style="color:black">Black</option>
        <option value="silver" style="color:silver">Silver</option>
        <option value="gray" style="color:gray">Gray</option>
        <option value="maroon" style="color:maroon">Maroon</option>
        <option value="red" style="color:red">Red</option>                                                                                
        <option value="purple" style="color:purple">Purple</option>
        <option value="fuchsia" style="color:fuchsia">Fuchsia</option>
        <option value="navy" style="color:navy">Navy</option>
        <option value="blue" style="color:blue">Blue</option>
        <option value="aqua" style="color:aqua">Aqua</option>
        <option value="teal" style="color:teal">Teal</option>
        <option value="lime" style="color:lime">Lime</option>
        <option value="green" style="color:green">Green</option>
        <option value="olive" style="color:olive">Olive</option>
        <option value="yellow" style="color:yellow">Yellow</option>
        <option value="white" style="color:white">White</option>  
       </select>
       &nbsp;
       <span id="editor-smiles-pick"><a href="javascript:smiles()" onMouseOver="helpline('smile')"><img src="<?php echo JURI::base();?>/editor/smiles/smile.gif"/></a></span>  
  		
  	<br> 
  	<div id="editor-helper">&nbsp;</div>
   	<textarea <?php echo 'rows="'.$ySize.'" cols="'.$xSize.'"'?> name="<?php echo $taname?>" id="editor-text" onkeydown="commentChanged(this,<?php echo $maxchars;?>,'edit-chars-left')" onkeyup="commentChanged(this,<?php echo $maxchars;?>,'edit-chars-left')"><?php if ($oldmsg) echo $oldmsg;?></textarea>
   	<div>Preostalo <span id="edit-chars-left"><?php echo $maxchars;?></span> karaktera</div>
  <div id="editor-tmp" style="display:none">
    <a href="javascript:addSmile('angry')"><img src="<?php echo JURI::base();?>/editor/smiles/angry.gif"></a><a href="javascript:addSmile('biggrin')"><img src="<?php echo JURI::base();?>/editor/smiles/biggrin.gif"></a><a href="javascript:addSmile('blink')"><img src="<?php echo JURI::base();?>/editor/smiles/blink.gif"></a><a href="javascript:addSmile('blush')"><img src="<?php echo JURI::base();?>/editor/smiles/blush.gif"></a><a href="javascript:addSmile('bye2')"><img src="<?php echo JURI::base();?>/editor/smiles/bye2.gif"></a><a href="javascript:addSmile('cool')"><img src="<?php echo JURI::base();?>/editor/smiles/cool.gif"></a><a href="javascript:addSmile('devil')"><img src="<?php echo JURI::base();?>/editor/smiles/devil.gif"></a><a href="javascript:addSmile('dizzy')"><img src="<?php echo JURI::base();?>/editor/smiles/dizzy.gif"></a><a href="javascript:addSmile('evillaugh')"><img src="<?php echo JURI::base();?>/editor/smiles/evillaugh.gif"></a><a href="javascript:addSmile('happy')"><img src="<?php echo JURI::base();?>/editor/smiles/happy.gif"></a><a href="javascript:addSmile('huh')"><img src="<?php echo JURI::base();?>/editor/smiles/huh.gif"></a><a href="javascript:addSmile('kissing')"><img src="<?php echo JURI::base();?>/editor/smiles/kissing.gif"></a><a href="javascript:addSmile('laugh')"><img src="<?php echo JURI::base();?>/editor/smiles/laugh.gif"></a><a href="javascript:addSmile('mf_tongue')"><img src="<?php echo JURI::base();?>/editor/smiles/mf_tongue.gif"></a><a href="javascript:addSmile('ohmy')"><img src="<?php echo JURI::base();?>/editor/smiles/ohmy.gif"></a><a href="javascript:addSmile('pinch')"><img src="<?php echo JURI::base();?>/editor/smiles/pinch.gif"></a><a href="javascript:addSmile('rolleyes')"><img src="<?php echo JURI::base();?>/editor/smiles/rolleyes.gif"></a><a href="javascript:addSmile('sad')"><img src="<?php echo JURI::base();?>/editor/smiles/sad.gif"></a><a href="javascript:addSmile('smile')"><img src="<?php echo JURI::base();?>/editor/smiles/smile.gif"></a><a href="javascript:addSmile('thumbsdown')"><img src="<?php echo JURI::base();?>/editor/smiles/thumbsdown.gif"></a><a href="javascript:addSmile('thumbsup')"><img src="<?php echo JURI::base();?>/editor/smiles/thumbsup.gif"></a><a href="javascript:addSmile('tongue')"><img src="<?php echo JURI::base();?>/editor/smiles/tongue.gif"></a><a href="javascript:addSmile('w00t')"><img src="<?php echo JURI::base();?>/editor/smiles/w00t.gif"></a><a href="javascript:addSmile('wink')"><img src="<?php echo JURI::base();?>/editor/smiles/wink.gif"></a>
  </div> 	
     	
  </div>

<?php 
}

function editor_decode($text, $clear=false){
	$text = nl2br(stripslashes($text));
	$text = str_replace( '  ', ' &nbsp;', $text );
  $bb_what = array (
  									'/\[img(=[0-9]+)\]http:\/\/([a-z0-9\-\.]+)([a-z0-9_\/\-%]+)\.(jpg|gif|png)\[\/img\]/ui',
  									'/\[img\]http:\/\/([a-z0-9\-\.]+)([a-z0-9_\/\-%]+)\.(jpg|gif|png)\[\/img\]/ui',
  									'/\[url\=(.+?)\](.+?)\[\/url\]/us',
  									'/\[url\](.+)\[\/url\]/u',
  									'/\[size\=(.+?)\](.+?)\[\/size\]/us',  									
  									'/\[color\=(.+?)\](.+?)\[\/color\]/us',
  								 );
	if ( !$clear ){  								   								 
  	$bb_with = array (
  									'<img src="http://\\2\\3.\\4" width="\\1"/>',
  									'<img src="http://\\1\\2.\\3"/>',
  									'<a href="$1">$2</a>',
  									'<a href="\\1">\\1</a>',
  									'<span style="font-size:$1">$2</span>',
  									'<span style="color:$1">$2</span>',
  								 );
  }
  else{
  	$bb_with = array (
  									'',
  									'',
  									'',
  									'',
  									'',
  									'',
  								 );  	
  }
	$smiles = array(
	':angry:'=>'<img src="'.JURI::base().'/editor/smiles/angry.gif"/>',
	':biggrin:'=>'<img src="'.JURI::base().'/editor/smiles/biggrin.gif"/>',
	':blink:'=>'<img src="'.JURI::base().'/editor/smiles/blink.gif"/>',
	':blush:'=>'<img src="'.JURI::base().'/editor/smiles/blush.gif"/>',
	':bye2:'=>'<img src="'.JURI::base().'/editor/smiles/bye.gif"/>',
	':cool:'=>'<img src="'.JURI::base().'/editor/smiles/cool.gif"/>',
	':devil:'=>'<img src="'.JURI::base().'/editor/smiles/devil.gif"/>',
	':dizzy:'=>'<img src="'.JURI::base().'/editor/smiles/dizzy.gif"/>',
	':evillaugh:'=>'<img src="'.JURI::base().'/editor/smiles/evillaugh.gif"/>',
	':happy:'=>'<img src="'.JURI::base().'/editor/smiles/happy.gif"/>',
	':huh:'=>'<img src="'.JURI::base().'/editor/smiles/huh.gif"/>',
	':kissing:'=>'<img src="'.JURI::base().'/editor/smiles/kissing.gif"/>',
	':laugh:'=>'<img src="'.JURI::base().'/editor/smiles/laugh.gif"/>',
	':mf_tongue:'=>'<img src="'.JURI::base().'/editor/smiles/mf_tongue.gif"/>',
	':ohmy:'=>'<img src="'.JURI::base().'/editor/smiles/ohmy.gif"/>',
	':pinch:'=>'<img src="'.JURI::base().'/editor/smiles/pinch.gif"/>',
	':rolleyes:'=>'<img src="'.JURI::base().'/editor/smiles/rolleyes.gif"/>',
	':)'=>'<img src="'.JURI::base().'/editor/smiles/smile.gif"/>',
	':('=>'<img src="'.JURI::base().'/editor/smiles/sad.gif"/>',
	':thumbsdown:'=>'<img src="'.JURI::base().'/editor/smiles/thumbsdown.gif"/>',
	':thumbsup:'=>'<img src="'.JURI::base().'/editor/smiles/thumbsup.gif"/>',
	':tongue:'=>'<img src="'.JURI::base().'/editor/smiles/tongue.gif"/>',
	':w00t:'=>'<img src="'.JURI::base().'/editor/smiles/w00t.gif"/>',
	':wink:'=>'<img src="'.JURI::base().'/editor/smiles/wink.gif"/>',
	';)'=>'<img src="'.JURI::base().'/editor/smiles/wink.gif"/>');
  $text = strtr($text, $smiles);
  $normals = array('b','u','i','center');
  $tmp = $tmp2 = 0;
  foreach ( $normals as $normal ){
  	$text = str_replace( '['.$normal.']', '<'.$normal.'>', $text, $tmp );
  	$text = str_replace( '[/'.$normal.']', '</'.$normal.'>', $text, $tmp2 );
  	for ( $i = $tmp2; $i < $tmp; ++$i )
  	  $text .= '</'.$normal.'>';
  	for ( $i = $tmp; $i < $tmp2; ++$i )
  	  $text = '<'.$normal.'>'.$text;
  }
  $text = quote2raw( $text,0 );
  return preg_replace($bb_what, $bb_with, $text);		
}

function quote2raw( $str, $id ){
	if ($id == 5) return $str;
  $str = preg_replace( '/\[quote\=(.+?)\](.+?)\[\/quote\]/us', '<div class="quoted">$1 je rekao/la :<blockquote>$2</blockquote></div>', $str );
        
  if ( preg_match('/\[quote\=(.+?)\](.+?)\[\/quote\]/us', $str )){
    $str = quote2raw( $str, $id+1 );
  }
  return $str;
}
?>

