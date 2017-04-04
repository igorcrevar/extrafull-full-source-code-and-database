<?php defined('CREW') or die('Restricted access'); ?>
<div id="comment_start">
<?php 
    $level = 0; 
    //$user = &User::getInstance();
    for ($i = 0;$i<count($this->comments);++$i)
    {
    	$c = $this->comments[$i];
    	echo '<div class="justc">';
    	echo '<b>'.JHTML::profileLink($c->user_id,$c->user_name);
    	echo '&nbsp; '.JHTML::date($c->date).'</b>';
			if ( $this->moderate)// || $user->id == $c->user_id)
    	{
    		echo ' &nbsp; &nbsp; <a href="javascript:delCmnt('.$c->id.')">'.JText::_('OBRISI_KOMENTAR').'</a>';
    	}
    	echo '</div>';
    	echo "<blockquote>$c->comment</blockquote>";
    }
  ?> 
</div>
