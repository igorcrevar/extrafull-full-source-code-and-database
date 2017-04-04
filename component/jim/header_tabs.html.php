	<?php 
	$doc = &Document::getInstance();
	$doc->addStyleSheet(Basic::uriBase().'/component/jim/tabs.css');
	?>
	<DIV id=header-jim>
		<UL class="nobul">
			<LI  class="nobul" <?php if ($page=="inbox") {echo "id=current";}?>>
				<a href="<?php echo JURI::base();?>/index.php?option=com_jim&task=inbox">
				<?php echo _JIM_INBOX?>
				</a>
			</LI>
			<LI class="nobul" <?php if ($page=="new") {echo "id=current";}?>>
				<a href="<?php echo JURI::base();?>/index.php?option=com_jim&task=new">
				<?php echo _JIM_NEW?></a>
			</LI>
			<LI  class="nobul" <?php if ($page=="outbox") {echo "id=current";}?>>
				<a href="<?php echo JURI::base();?>/index.php?option=com_jim&task=outbox">
				<?php echo _JIM_OUTBOX?>
				</a>
			</LI>
		</UL>
	</DIV>
