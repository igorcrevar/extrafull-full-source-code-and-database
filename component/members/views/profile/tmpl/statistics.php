<?php defined( '_JEXEC' ) or die( 'Restricted access' );		
		echo '<div class="user_edit">'.JText::_('MB_FORUM_MSGS').' '.$this->posts.'</div>';		
		if ( count($this->user_last_forum_msgs) ){
			foreach ($this->user_last_forum_msgs as $row){
				echo JHTML::lnk('forum?pid='.$row->id,$row->message).'<br/>';		 
			}
			if ($this->posts > 5) echo '...<br>';
		}
		echo '<br><div class="user_edit">'.JText::_('MB_PHOTO_CMNTS').' '.$this->photoCommentsCnt.'</div>';				
		if ( count($this->user_last_comments) ){
			foreach ($this->user_last_comments as $row){
		    echo JHTML::picLink($row->image_id, JString::substr($row->comment,0,70));
				echo '<br>';
			}
			if ($this->photoCommentsCnt > 5) echo '...<br>';
		}?>		