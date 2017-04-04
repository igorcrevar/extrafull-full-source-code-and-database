<?php 
$gid = User::getInstance()->gid;
if ($gid >= 18) : ?>
<div style="margin-left:200px">
<form action="<?php echo Basic::routerBase();?>/forum" method="POST">
<input type="text" style="width:400px" name="sentence" />
<input type="submit" class="button" value="Trazi" />
<?php if ( isset($cid) ) : ?>
<input type="hidden" value="<?php echo $cid;?>" name="cid" />
<?php endif ?>
<input type="hidden" name="task" value="search" />
</form>
</div>
<?php endif ?>