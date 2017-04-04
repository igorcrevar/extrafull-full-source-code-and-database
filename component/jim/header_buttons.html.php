	<style type="text/css" media="screen">@import "components/com_jim/buttons.css";</style>		
	<DIV>
		<UL id="button-jim">
			<LI >
				<a  <?php if ($page=="inbox") {echo 'class="current" ';}?>href="<?php echo JURI::base();?>/index.php?option=com_jim&task=inbox">
				<?php echo _JIM_INBOX?>
				</a>
			</LI>
			<LI class="button-jim">
				<a <?php if ($page=="new") {echo 'class="current" ';}?>href="<?php echo JURI::base();?>/index.php?option=com_jim&task=new">
				<?php echo _JIM_NEW?></a>
			</LI>
			<LI  class="button-jim">
				<a <?php if ($page=="outbox") {echo 'class="current" ';}?>href="<?php echo JURI::base();?>/index.php?option=com_jim&task=outbox">
				<?php echo _JIM_OUTBOX?>
				</a>
			</LI>
		</UL>
	</DIV>
