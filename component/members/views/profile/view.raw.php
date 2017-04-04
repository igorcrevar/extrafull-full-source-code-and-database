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
defined( 'CREW' ) or die( 'Restricted access' );

class MembersViewProfile extends JView
{
	function display( $tpl = null)
	{
		$me = &JFactory::getUser();
		if ($me->gid < 18) { echo 'Da bi ste videli ove podatke morate da se registrujete i ulogujete'; return; }
		$model = $this->getModel();
		$id = JRequest::getInt('id',70);
		$what = JRequest::getCmd('what','');
		if ($what=='stat'){
			$user_last_forum_msgs = $model->userLastForumMsgs($id);	  
			$user_last_comments = $model->userLastComments($id);			
      $photoCommentsCnt = $model->getPhotoCommentsCnt($id);
      $posts = JRequest::getInt( 'posts' );
	    $this->assignRef( 'user_last_forum_msgs',$user_last_forum_msgs);
	    $this->assignRef( 'photoCommentsCnt',$photoCommentsCnt);
			$this->assignRef( 'user_last_comments', $user_last_comments);	    
			$this->assignRef( 'posts', $posts );
 	    $this->setLayout( 'statistics' );
		  parent::display($tpl);	      
			return;
		}
		else if ($what=='descs'){
			$db =  &JFactory::getDBO();
			$db->setQuery('SELECT id2 FROM #__members_friends WHERE id1='.$id.' AND status=1');
			$fs = $db->loadObjectList();
			$friends = array();
			for ($i=0;$i<count($fs);++$i)
			  $friends[$i] = $fs[$i]->id2;
			$txt = JRequest::getString('txt','');
			if (JString::strlen($txt) >= 3){
				$model->writeDesc($me->id,$id,$txt);
			}
			$rows = $model->loadDescs($id,$friends);
			$canWrite = in_array($me->id,$friends);
			$this->assignRef( 'id', $id);
			$this->assignRef( 'canWrite', $canWrite);
			$this->assignRef( 'rows',$rows);
 	    $this->setLayout( 'descriptions' );
		  parent::display($tpl);				
			return;
		}
		
		$limitstart = JRequest::getInt('limitstart',0);
		$limit = JRequest::getInt('limit',10);
    $cnt = $model->getCommentsCnt($id);
    if ($limitstart >= $cnt)
			$limitstart = floor(($cnt-1) / $limit)*$limit;
		$comments = $model->loadComments($id,$limitstart,$limit);
		$user = JFactory::getUser();
    for ($i = 0;$i < count($comments); ++$i )
    {
     	$row = &$comments[$i];
			echo '<b>'.JHTML::profileLink($row->from_id,$row->username);
			echo ' '.JHTML::_('date',$row->date);
			echo '</b><br>';     	
    	echo $row->comment;	  	
    	if ( $user->id == $id || $user->id==$row->from_id)
    	{
    		 echo '&nbsp; <a href="javascript:del_c('.$id.','.$row->id.');">'.JText::_('MB_ERASE_CMNT').'</a>';
    	} 
     	echo '<br>';
    }
		$pag = &JHTML::getPagination( $cnt, $limitstart, $limit, array('com_pag','id',$id), 'ajax' );
		$pag->getPagesLinks(false);
	}
}	
?>