<?php defined('CREW') or die('Restricted access'); 
$blog = $this->blog;
require_once(BASE_PATH.DS.'editor'.DS.'editor.php');	
?>
<div style="padding:0px 40px;width:600px">	

<div style="float:left;width:88%">
		
	<div class="fontMy1"><?php echo $blog->subject;?></div>
	by <b><?php echo JHTML::profileLink($blog->who_id,$blog->username).'</b> - '.JHTML::date($blog->date);?>
	<span style="font-size:9px"><a href="<?php echo Basic::routerBase();?>/blog/<?php echo $blog->who_id.'">'.JText::_('VIEW_USER');?></a></span>

</div>
<div style="float:left">
<?php $link = Basic::routerBase().'/blog/blog/'.$blog->id;?>
<script type="text/javascript">
	digg_url = '<?php echo $link;?>';
	</script>
	<script src="http://digg.com/tools/diggthis.js" type="text/javascript"></script>
</div>
<div style="clear:left"></div>
	
<div style="text-align:justify;border-top:1px dotted #888;border-bottom:1px dotted #888;margin:3px 0px;padding:10px 0px;">
<p>
<?php
echo editor_decode($blog->text);
?>
</p>
</div>
<?php
if ($this->privileges > 0){
	?>
<script type="text/javascript" language="javascript">
function vote_m(_id,_v){
 sendAJAX('task=vote&id='+_id+'&vote='+_v,'v_cs');
} 	
function requestdone(){	
 if (http_request.readyState == 4){ 
 	 if (http_request.status == 200 || window.location.href.indexOf("http")==-1){ 
 	 	  _label.innerHTML='';
 	  	r=http_request.responseText;
 	  	if (r != '!'){
				 rs = eval('('+r+')');
				 a = document.getElementById('vsum');
				 b = document.getElementById('vcnt');
				 a.innerHTML = parseInt(a.innerHTML)+rs.vote;
				 b.innerHTML = parseInt(b.innerHTML)+rs.inc;
			}
 	 }
 }
}  	
</script>		
	<?php
		  echo '<div style="font-size:13px;color:red">'.JHTML::image('love.gif');
   		echo '<span id="vsum">'.$blog->vote_sum.'</span>&nbsp;od&nbsp;';
   		echo '<span id="vcnt">'.$blog->vote_count.'</span>';
	    echo '<span id="v_cs">';
 			echo ' &nbsp; <a href="#" onclick="vote_m('.$blog->id.',1);return false;">'.JHTML::image('kplus.gif','title="Pozitivna"').'</a>&nbsp;';
   		echo '<a href="#" onclick="vote_m('.$blog->id.',-1);return false;">'.JHTML::image('kminus.gif','title="Negativna"').'</a>&nbsp;';
   		echo '</span>';
	    echo '</div>';
		echo '<div id="comment_start">';
		echo '<div class="headc">&nbsp;<b>Komentari ('.$blog->comments.') :</b></div>';
		if ($this->privileges == 7)
		  echo '<form method="post" action="'.Basic::routerBase().'/blog?task=delCmnt&id='.$blog->id.'">';
    $level = 0; 
    for ($i = 0;$i<count($this->comments);++$i)
    {
    	$c = $this->comments[$i];
    	echo '<div class="justc">';
    	if ($this->privileges == 7){
    		echo '<input type="checkbox" name="cid['.$c->id.']" value="del"> ';
    	}
    	echo '<b>'.JHTML::profileLink($c->who_id,$c->username);
    	echo '&nbsp; '.JHTML::date($c->date).'</b>';
    	echo '</div>';
    	echo "<blockquote>$c->comment</blockquote>";
    }
		$this->pag->getPagesLinks();
    if ($this->privileges == 7){
    	echo '<input type="submit" class="button" value="'.ERASE.'">';
	    echo '</form>';
    }	
		echo '</div>';
		if ($this->privileges >= 3){
			 echo '<br/><form method="post" action="'.Basic::routerBase().'/blog?task=comment&id='.$blog->id.'">';
 			 echo '<textarea name="comment" cols="50" rows="4" wrap="VIRTUAL" id="ucnt" onKeyDown="commentChanged(this,600,\'chars_left\')" onKeyUp="commentChanged(this,600,\'chars_left\')"></textarea><br/>';
 			 echo 'Znakova: <span id="chars_left">600</span><br/>';
	     echo '<input type="submit" class="button" value="'.SEND.'">';
			 echo '</form>';
		}
}
?>
</div>
