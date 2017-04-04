<?php
defined( 'CREW' ) or die( 'Direct Access to this location is not allowed.' );
define( 'MYBASEPATH', dirname(__FILE__) );

$page = JRequest::getCmd('task',null);
$action = trim( JRequest::getCmd('action',null) );

$rep_i = JRequest::getInt('rep_i',0);

$id = JRequest::getInt('id',0);
$rid = JRequest::getInt('rid',0);
$title = trim(JRequest::getString('title',''));

$new_to = trim( JRequest::getString('new_to','') );
$new_title = trim( JRequest::getString('new_title',''));
$new_message = trim( JRequest::getString('new_message','' ));



$Itemid = JRequest::getInt('Itemid',0);

// backward compatibility
if ($page === null) {
	$page = JRequest::getCmd('page',null);
}

// backward compatibility
if ($page != 'new') {
	$mid = JRequest::getInt('id',0);
}
else {
	$to = JRequest::getInt('id',0);
}

include_once(MYBASEPATH.DS.'config.jim.php');
require_once( MYBASEPATH.DS.'jim.html.php' );

$my = &JFactory::getUser();
$my_id = $my->id;
$gid = $my->gid;

if ($my->gid < 18){
   JimOS_html::notconnected();
} else {

     switch ($page) {
     
     	case "xml":
     	showXmlOutput();
     	break;
     
     	case "deletemsgs":
     	deleteMessages();
     	break;
     
     	case "deletemsg":
     	deleteSMessage($id);
     	break;
     
     	case "view":
     	showHeader ($page);
     	viewMessage($id);
     	showFooter();
     	break;
     
     	case "viewsent":
      showHeader ($page);
     	viewSent($id);
     	showFooter();
     	break;
     
     	case "inbox":
     	showHeader ($page);
     	showInbox();
     	showFooter();
     	break;
     
     	case "outbox":
     	showHeader ($page);
     	showOutbox();
     	showFooter();
     	break;
     
     	case "leavemsgs":
     	leaveMessages();
     	break;
     
     	case "leavemsg":
     	leaveSMessage($id);
     	break;
     
     	case "new":
     	showHeader ($page);
     	cnewMessage ( $to, $title);
     	showFooter();
     	break;
     
     	case "sendpm":
     	sendPM($my->username, $new_to , $new_title, $new_message,$rid);
     	break;
     
     	default:
     	showHeader ($page);
     	showInbox();
     	showFooter();
     	break;
     
     }
}
     
     function showXmlOutput() {
     	$my = &JFactory::getUser();
     	$database = &JFactory::getDBO();
     
     		if ($my->gid>=18) {
     			$sql = "SELECT count(*) FROM #__jim "
     		."\n WHERE who_id=$my->id AND readstate=0";
     
     			$database->setQuery($sql);
     			$howmany = $database->loadResult();
     
     			header('Content-Type: text/xml');
     			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
     			header("Cache-Control: no-store, no-cache, must-revalidate");
     			header("Cache-Control: post-check=0, pre-check=0", false);
     			header("Pragma: no-cache");
     
               echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'; ?>
               
               <response>
                 <method>doCheck</method>
                 <result><?php echo $howmany;?></result>
               </response>
               <?php
     		}
     }
     
     function showHeader ($page) {
     	JimOS_html::showHeader($page);
     }
     function showfooter () {
     	JimOS_html::showFooter();
     }
     
     function showInbox () {
     	$my = &JFactory::getUser();
     	$database = &JFactory::getDBO();
     	$limit = JRequest::getInt('limit',20);
     	$limitstart = JRequest::getInt('limitstart',0);
     
     	$query = "SELECT count(id) FROM #__jim WHERE who_id=$my->id";//username='$my->username'";
     	$database->setQuery($query);
     	//echo str_replace('#__','jos_',$query).';<br>';
     	$total = $database->loadResult();
     
     	if ($total <= $limit) {
     		$limitstart = 0;
     	}
     
      $query = 'SELECT j.*,u.username as whofrom FROM #__jim AS j LEFT JOIN #__users AS u ON u.id=j.from_id WHERE j.who_id='.$my->id.' ORDER BY j.id DESC LIMIT '.$limitstart.','.$limit;
      $database->setQuery($query);
     	$rows = $database->loadObjectList();
     
		  $pageNav = &JHTML::getPagination( $total, $limitstart, $limit, null, 'html' );		      
     
     	JimOS_html::showInbox($rows, $pageNav);
     
     }
     
     function showOutbox () {	
      $my = &JFactory::getUser();
     	$database = &JFactory::getDBO();

      $limit = JRequest::getInt('limit',20);
     	$limitstart = JRequest::getInt('limitstart',0);
     
     	$query = "SELECT count(id) FROM #__jim WHERE from_id=$my->id and outbox=1";
     	$database->setQuery($query);
     	$total = $database->loadResult();
     
     	if ($total <= $limit) {
     		$limitstart = 0;
     	}
     	$jimquery ="SELECT j.*, u.username FROM #__jim as j left join #__users as u on u.id=j.who_id WHERE j.from_id=$my->id and j.outbox=1 order by j.id desc LIMIT $limitstart, $limit";
     	$database->setQuery($jimquery);     
     	$rows = $database->loadObjectList();     
 		  $pageNav = &JHTML::getPagination( $total, $limitstart, $limit, null, 'html' );		      
			JimOS_html::showOutbox($rows, $pageNav);       
     }
     
     function deleteMessages() {
     	$my = &JFactory::getUser();
     	$database = &JFactory::getDBO();
		     
     	$inb_delete = JRequest::getCmd('delm',null);
   		$del_ids = JRequest::getVar( 'delete', array(), 'request', 'null' );;
     	if ( isset($del_ids) ){
     		// get the list of message IDs to be deleted in an array
        $del_ides_c =chop( implode(",",array_keys($del_ids)));     
     		if ($del_ides_c !== "") {
     			$database->setQuery("delete from #__jim where id in ($del_ides_c) AND who_id=$my->id"); //username='$my->username'");
     			if ($database->query()) {
     				$msg = _JIM_MSG_DELETED;
					}
     			else{
     				$msg = _JIM_ERROR;
    			}
   			}
   			else{
   				$msg = _JIM_SELECT_TO_DELETE;
     		}
     	}
     	global $mainframe;     	
 			$mainframe->redirect( JURI::base()."/index.php?option=com_jim", $msg);
     }
     
     function leaveMessages() {
     		$my = &JFactory::getUser();
     		$database = &JFactory::getDBO();
     
     		$inb_delete = JRequest::getString('leaveme',null);	
     
     		if (isset($inb_delete))
     		{
     			$del_ids = JRequest::getVar( 'leave', array(), 'request', 'null' );
     			$del_ides_c =chop( implode(",",array_keys($del_ids)));
     			if ($del_ides_c !== "") {
     				$jimquery = "update #__jim set outbox=0 where id in ($del_ides_c) AND from_id=$my->id"; //whofrom='$my->username'";
     				$database->setQuery($jimquery);
     
     				if ($database->query()){
     					$jimmessage = _JIM_MSG_DELETED;
     				} 
     				else	{
     					$jimmessage = _JIM_ERROR;
     				}
     			}
     			else{
     				$jimmessage = _JIM_SELECT_TO_DELETE ;
     			}
     		     
     			global $mainframe;
     			$mainframe->redirect( JURI::base()."/index.php?option=com_jim&task=outbox", $jimmessage);
     		}
     }
     
     function leaveSMessage ($id) {
     		global $mainframe;
     		$my = &JFactory::getUser();
     		$db = &JFactory::getDBO();
     		$id = intval($id);     
     		if ($id ) {
     			$query = "update #__jim set outbox=0 where id=$id AND from_id=$my->id";//and whofrom='$my->username'";
     			$db->setQuery($query);
     			if ($db->query()) {
     				$mainframe->redirect( JURI::base()."/index.php?option=com_jim&task=outbox", $jimmessage);
     			}
     		}
     }
     
     function deleteSMessage ($id) {
     		global $mainframe;
      	$my = &JFactory::getUser();
     		$database = &JFactory::getDBO();
     
     
     		$id = intval($id);
     
     		if ($id ) {
     			$database->setQuery("delete from #__jim where id=$id  AND who_id=$my->id");//and username='$my->username'");
     
     			if ($database->query()) {
     				$mainframe->redirect( JURI::base()."/index.php?option=com_jim", _JIM_MSG_DELETED);
     			}
     		}
     }
     
     
     
     function viewMessage ($mid) {
     	$my = &JFactory::getUser();
     	$database = &JFactory::getDBO();     
      $query = 'SELECT j.*,u.name AS uname, u.username AS whofrom FROM #__jim AS j LEFT JOIN #__users AS u ON u.id=j.from_id WHERE j.id='.$mid;
      $database->setQuery( $query );
     	$row = $database->loadObject();
     	if ( isset($row) && $row )
     	{
     		if ($row->who_id != $my->id) return;
     		if ($row->readstate == 0)
     		{
     			$database->setQuery("update #__jim set readstate='1' where id='$mid'");
     			$database->query();
     		}
     		JimOS_html::viewMessage($row);
     	}
     }
     
     function viewSent ($mid) {
      $my = &JFactory::getUser();
     	$database = &JFactory::getDBO();     
      $query = 'SELECT j.*,u.name AS uname, u.username AS username FROM #__jim AS j LEFT JOIN #__users AS u ON u.id=j.who_id WHERE j.id='.$mid;
      $database->setQuery($query);     
     	$row = $database->loadObject();
     	if ( isset($row) && $row){
        if ($row->outbox != 1 || $row->from_id != $my->id) return;
     		JimOS_html::viewSent($row);
     	}
     }
     
     
     function  cnewMessage ( $to, $title) {
       $my = &JFactory::getUser();
     	$database = &JFactory::getDBO();
     
     	$rid = JRequest::getInt('rid',0);
     	if (!$to &&  !$rid)
     	{
     	  $list=array(new stdClass());
     	  $list[0]->username2='Izaberi prijatelja...';
     	  $list[0]->username='';
  	   	$query = 'SELECT a.username,a.username AS username2 FROM #__users AS a JOIN #__members_friends AS b ON b.id1='.$my->id.' AND b.id2=a.id ORDER BY a.username';
	     	$database->setQuery($query);	
     	  $rows = $database->loadObjectList();
     	  $rows=array_merge($list,$rows);
			  $userList	= JHTML::_('select.genericlist', $rows, 'friends', 'onchange="addFriend()"','username','username2');
			}
			else $userList='';  
     	if ($rid) {
     		$query = "SELECT u.username as whofrom, j.subject, j.message FROM #__jim AS j JOIN #__users AS u ON u.id=j.from_id where j.id=$rid";
     		$database->setQuery($query);
     		$row = $database->loadObject();
     		$rtitle = $row->subject;
     		$rmsg = $row->message;
     		$to = $row->whofrom;
     		$rmsg = "\n\n[quote=".$row->whofrom.']'.$rmsg.'[/quote]';
     		if (substr($rtitle,0,3) != 'RE:'){
     			$rtitle='RE: '.$rtitle;
     		}
     	} else {
        $row = new stdClass();
        $rtitle = '';
     		$rmsg = '';
     		$to = '';
     		$rmsg = '';
      }
     	JimOS_html::newMessage($row, $title, $to,$userList, $rtitle,$rmsg, $rid);     
     }
     
   function sendPM ($who, $to , $title, $message,$rid) {
			global $mainframe;
     	$my = &User::getInstance();
     	if ( $my->gid < 18 ) return;
     	$database = &Database::getInstance();
     	global $JimConfig;
     	$rid = JRequest::getInt('rid',0);
     	if (is_numeric($to)){
     	  $who_id = intval($to);
     	}
     	else{
				$to = $database->Quote($to);
				$database->setQuery("SELECT id FROM #__users WHERE username = $to");
				$who_id = intval( $database->loadResult() );
				if ( !isset($who_id)  ||  $who_id < 30) {
					$mainframe->redirect(JURI::base().'/index.php?option=com_jim',_JIM_USERDOESNTEXIST);
				}     	
			}
		
			/* block code */
			require_once BASE_PATH.DS.'block.php';
			if ( isUserBlockedBy($who_id, false ) ){
				$mainframe->redirect(JURI::base().'/.index.php?option=com_jim', JText::_('BLOCKUSER_MSG') );
			} 
			/* end block code */  
			
			if (JString::strlen($title) < 3 || JString::strlen($message) < 3 ||
				 JString::strlen($title) > 200 || JString::strlen($message) > 3000){
	 	 	   $mainframe->redirect(JURI::base().'/index.php?option=com_jim','Premalo ili previse karaktera');
			}     	
     	$title = $database->Quote($title);
     	$message = $database->Quote($message);
     
      $query = "INSERT INTO #__jim (who_id,from_id,outbox,date,readstate,subject,message) VALUES ($who_id,$my->id,1,now(),0,$title,$message)";
      $database->setQuery($query);
     	if( $database->query()) {
     
     		if ($JimConfig["emailnotify"]) {
     			$database->setQuery("select name, email from #__users"
     			."\n where id= $who_id");
     
     			$mail_user = $database->loadObjectList();
     			$mail_to = $mail_user[0]->email;
     			$mail_user_name = $mail_user[0]->name;
     
     			$m_sub = sprintf( _JIM_MAILSUB, 'extrafull.com');
     			$m_msg = sprintf( _JIM_MAILMSG,  'admin extrafull', $my->username,'extrafull.com', 'extrafull.com','extrafull.com' );
     
     			$head= "MIME-Version: 1.0\n";
     			$head .= "Content-type: text/html; charset=iso-8859-1\n";
     			$head .= "X-Priority: 1\n";
     			$head .= "X-MSMail-Priority: High\n";
     			$head .= "X-Mailer: php\n";
     			$head .= "From: \"".'extrafull.com'."\" <".'extrafull.com'.">\n";
     
     			@mail($mail_to, $m_sub, $m_msg, $head);
     		}
            if ($rid){
          		$mainframe->redirect(JURI::base().'/index.php?option=com_jim', _JIM_REPLY_SENT);             
            }else{
          		$mainframe->redirect(JURI::base().'/index.php?option=com_jim', _JIM_MSG_SENT);
            }
     
      	}
      	else
      	 $mainframe->redirect(JURI::base().'/index.php?option=com_jim', 'Greska! Privatna poruka nije poslana');
     }