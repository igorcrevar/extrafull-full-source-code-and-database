<?php
defined( 'CREW' ) or die( 'Restricted access' );
class ForumViewPost extends JView
{
  function display($tpl = null)
	{		
		$pid = JRequest::getInt('pid',0);
		$tid = JRequest::getInt('tid',0);
		$cid = JRequest::getInt('cid',-1);
		$model = $this->getModel();
		if ($pid > 0){
			$change = JRequest::getInt('change',0);
			$row = $model->getMsg($pid, $change);
			if (!$row) return;
			$msg = $row->message;
			$subject = $row->subject;
			if (!$change){
     		$msg = '[quote='.$row->username.']'.$msg."[/quote]\n";
     		$subject = $row->subject;
				if (substr($subject,0,3) != 'RE:'){
   				$subject = 'RE:'.$subject;     		
   			}
			}
   		else{
   		  $this->assignRef('pid',$pid);
   		}				
   		$this->assignRef('tid',$row->tid);
   		$this->assignRef('subject',$subject);
			$this->assignRef('message',$msg);
		}
		else if ($tid > 0){			
			$subject = $model->getMsg2($tid);
			if (!$subject) return;
			if (substr($subject,0,3) != 'RE:'){
   			$subject = 'RE:'.$subject;
   		}							
			$this->assignRef('tid',$tid);
			$this->assignRef('subject',$subject);
		}
		else if ($cid > 0){
			$this->assignRef('cid',$cid);			
		}else{
			return;
		}
    parent::display( $tpl );
  }
}  
?>