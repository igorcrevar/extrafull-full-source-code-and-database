<?php defined('_JEXEC') or die('Restricted access'); 
require_once(JPATH_BASE.DS.'editor'.DS.'editor.php');	
$this->message = str_replace( '"', '&quot;', isset($this->message) ? $this->message : '' );
$this->subject = str_replace( '"', '&quot;', isset($this->subject) ? $this->subject : '' );
?>
<div style="width:470px;padding-left:100px"><br/>
<form method="post" action="<?php echo Basic::routerBase();?>/forum">
Naslov: 
<input type="input" name="subject" maxlength="100" size="70" value="<?php echo $this->subject;?>"/><br/><br/>
<?php editor_show('message',$this->message,'',2000,53,8);?>
<?php if (isset($this->cid)) echo '<input type="hidden" value='.$this->cid.' name="cid"/>';
else echo '<input type="hidden" value="'.$this->tid.'" name="tid"/>';
if (isset($this->pid)) echo '<input type="hidden" value='.$this->pid.' name="pid"/>';?>
<input type="hidden" name="task" value="post" />	
<?php $first = JRequest::getInt('first',0);
if ($first) echo '<input type="hidden" name="first" value="1" />';
?>
<input type="submit" class="button" value="<?php echo SEND;?>"/>	
</form>	
</div>