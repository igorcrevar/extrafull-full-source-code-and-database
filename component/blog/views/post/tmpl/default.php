<?php defined('CREW') or die('Restricted access'); 
require_once(JPATH_BASE.DS.'editor'.DS.'editor.php');	
$this->val->subject = str_replace( '"', '&quot;', $this->val->subject );
?>
<div style="width:470px;padding-left:100px"><br/>
<form method="POST" action="<?php echo Basic::uriBase();?>/blog">
Naslov: 
<input type="input" name="subject" maxlength="100" size="70" value="<?php echo $this->val->subject;?>"/><br/><br/>
Tip:<br/>
<?php 
$types = explode( ',', JText::_('TYPES') );
array_unshift( $types, '...');
$rows = array();
$i = 0;
foreach ($types as $t){
	$rows[$i] = new stdClass;
	$rows[$i]->name = $t;
	$rows[$i]->val = $i;
	++$i;
}
echo JHTML::_('select.genericlist', $rows, 'type', 'class="inputbox" size="1"','val', 'name', $this->val->type ).'<br/><br/>';
echo 'Opcije:<br/>';
			 $tname = explode(',',JText::_('B_OPTIONS'));
			 $opt = array(new stdClass(), new stdCLass(), new stdCLass());
			 $opt[0]->options=2;$opt[0]->val=$tname[0];
			 $opt[1]->options=0;$opt[1]->val=$tname[1];
			 $opt[2]->options=1;$opt[2]->val=$tname[2];
       echo JHTML::_('select.genericlist', $opt, 'options', '','options', 'val', $this->val->options ).'<br/><br/>';
editor_show('text',$this->val->text,'',10000,60,15);
if (isset($this->id)) echo '<input type="hidden" value='.$this->id.' name="id"/>';?>
<input type="hidden" name="task" value="post" />	
<input type="submit" class="button" value="<?php echo SEND;?>"/>	
</form>	
</div>