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

class ForumController extends JController
{
	function display()
	{	
		$cid = JRequest::getInt('cid',-1);   
		$tid = JRequest::getInt('tid',-1);
		$pid = JRequest::getInt('pid',-1);
		if ($pid > 0){
			$db = &Database::getInstance();
			$query = 'SELECT a.tid,count(b.id) as cnt FROM #__forum_posts as a JOIN #__forum_posts AS b on b.tid=a.tid AND b.id <= a.id WHERE a.id='.$pid.' GROUP BY a.tid';
			$db->setQuery($query);
			$obj = $db->loadObject();
			if (!$obj) return;
			global $mainframe;
			$limitstart = intval(($obj->cnt - 1) / 10)*10;
			$mainframe->redirect(Basic::uriBase().'/forum?tid='.$obj->tid.'&limitstart='.$limitstart.'#post'.$pid);
		}
		else if ($tid > 0){
			$viewName = 'topic';
			$id = $tid;			
		}
		else if ($cid > 0){
			$viewName = 'topics';
			$id = $cid;
		}
		else {
			$id = JRequest::getInt('show');
			$viewName = $id ? 'posts' : 'cats';
		}
		$view = $this->getView( $viewName, 'html' );
		$view->id = $id;
		$model = $this->getModel( $viewName, 'modelforum' ); 
		$view->setModel( $model, true ); 
   	$view->display(); 
  }

	function last(){
		$tid = JRequest::getInt('tid',-1);
		$db = &Database::getInstance();
		$query = 'SELECT max(id) AS id,count(id) AS cnt FROM #__forum_posts WHERE tid='.$tid;
		$db->setQuery($query);
		$obj = $db->loadObject();
		global $mainframe;
		$limitstart = intval(($obj->cnt - 1) / 10)*10;
		$mainframe->redirect(Basic::uriBase().'/forum?tid='.$tid.'&limitstart='.$limitstart.'#post'.$obj->id);
	}
	
  function newP(){
 		$view = $this->getView( 'post', 'html' );
		$model =$this->getModel( 'post', 'modelforum' ); 
		$view->setModel( $model, true ); 
   	$view->display();
  }
  
  function post(){
  	global $mainframe;
  	$user = &User::getInstance();
  	if ($user->gid < 18) return;
  	$subject = JRequest::getString('subject');
  	$message = JRequest::getString('message');
  	if (JString::strlen($subject) < 4 || JString::strlen($message) < 2){
  		$mainframe->redirect(Basic::requestURI(),'Naslov teme mora imati barem 4 znaka, a tekst barem 2');
  	}
		$cid = JRequest::getInt('cid',-1);   
		$tid = JRequest::getInt('tid',-1);  
		$pid = JRequest::getInt('pid',-1);
  	$db = &Database::getInstance();
  	if ($tid < 1  && $cid < 1) return;
		if ($pid > 0) $tmp = ',moderators';
		else $tmp = '';
  	if ($tid > 0){
  		$query = 'SELECT b.id,b.group'.$tmp.' FROM #__forum_topics AS a JOIN #__forum_cats AS b ON b.id=a.cid WHERE a.id='.$tid;
    }
    else{
    	$query = 'SELECT id,`group`'.$tmp.' FROM #__forum_cats WHERE id='.$cid;
    }
 		$db->setQuery($query);
    $cat = $db->loadObject();
    $subject = $db->Quote($subject);
    $message = $db->Quote($message);
    $un = $db->Quote($user->username);
    if (!$cat) return;

  	$time = time();
  	if ($pid > 0){
  		$query = 'UPDATE #__forum_posts SET subject='.$subject.',message='.$message.' WHERE id='.$pid;
  		$mods = &getModers($cat->moderators);
  		if (!in_array($user->id,$mods)) $query .= ' AND who_id='.$user->id;
  		$db->setQuery($query);
  		$first = JRequest::getInt('first',0);
			if ($db->query() && $first){
  			$query = 'UPDATE #__forum_topics SET subject='.$subject.' WHERE id='.$pid;
  			$db->setQuery($query);
				$db->query();
			}
  		$mods = getModers($cat->moderators);
  		$mainframe->redirect(Basic::uriBase().'/forum?pid='.$pid);
  	} 
  	else if ($tid > 0){  		
  	/*
  		$query = "SET @LAST=(SELECT MAX(id) FROM #__forum_posts FOR UPDATE)+1;"; 
  		$query .= "INSERT INTO #__forum_posts VALUES (@LAST,$tid,$user->id,$un,$subject,$message,$time);"; 
  		$query .= "UPDATE #__forum_topics SET last_id=@LAST,last_userid=$user->id,last_username=$un,time=$time,replies=replies+1 WHERE id=$tid;";
  		$query .= "UPDATE #__forum_cats SET last_topic_id=$tid,numPosts=numPosts+1 WHERE id=$cat->id;";
  		*/
  		$query = "INSERT INTO #__forum_posts SELECT max(id)+1,$tid,$user->id,$un,$subject,$message,$time FROM #__forum_posts";
  		$db->setQuery($query);
  		$error = 'Greska!';
  		if ($db->query()){
  			$query = "UPDATE #__forum_topics SET last_id=(SELECT max(id) FROM #__forum_posts WHERE who_id=$user->id),last_userid=$user->id,last_username=$un,time=$time,replies=replies+1 WHERE id=$tid";
  			$db->setQuery($query);
  			if ($db->query()){
  				$query = "UPDATE #__forum_cats SET numPosts=numPosts+1 WHERE id=$cat->id";
  				$db->setQuery($query);
  				if ($db->query()){
  					 $error = '';
		   			 $db->setQuery('UPDATE #__fb_users SET posts=posts+1 WHERE userid='.$user->id);
  					 $db->query();
  				}
  			}
  		}
			$sess = &Session::getInstance();
			$sess->set( 'usermsgid', $error);
			echo '<br /><br />';
			echo '<form action="'.Basic::routerBase().'/forum" id="submitme" method="get">';
			echo '<input type="hidden" name="task" value="last" />';
			echo '<input type="hidden" name="tid" value="'.$tid.'" />';
			echo '</form>';
			echo '<a href="'.Basic::routerBase().'/forum?task=last&tid='.$tid.'">[Klikni ovde da pogledas unetu poruku ili sacekaj dve sekunde]</a>';			
			echo '<br /><br /><br />';
			echo '<script type="text/javascript">';
			echo 'setTimeout(\'submitme()\',2000);';
			echo 'function submitme(){ document.getElementById(\'submitme\').submit(); }';
			echo '</script>';  		
  	}
  	else{
  		/*
  		$query = "SET @LAST=(SELECT IFNULL(MAX(id),0) FROM #__forum_posts FOR UPDATE)+1;"; 
  		$query .= "INSERT INTO #__forum_posts VALUES (@LAST,@LAST,$user->id,$un,$subject,$message,$time);"; 
  		$query .= "INSERT INTO #__forum_topics VALUES (@LAST,$cid,@LAST,$user->id,$un,0,$time,$subject,0,0,0);";
  		$query .= "UPDATE #__forum_cats SET last_topic_id=@LAST,numPosts=numPosts+1,numTopics=numTopics+1 WHERE id=$cid;";
  		*/
  		$error = 'Greska!';
 			$query = "INSERT INTO #__forum_posts SELECT ifnull(max(id),0)+1,ifnull(max(id),0)+1,$user->id,$un,$subject,$message,$time FROM #__forum_posts;"; 
 			$db->setQuery($query);
  		if ($db->query()){			
 				$query = "INSERT INTO #__forum_topics SELECT max(id),$cid,max(id),$user->id,$un,0,$time,$subject,0,0,0 FROM #__forum_posts WHERE who_id=$user->id;";
  			$db->setQuery($query);
  			if ($db->query()){
  				$query = "UPDATE #__forum_cats SET numPosts=numPosts+1,numTopics=numTopics+1 WHERE id=$cid;";
  				$db->setQuery($query);
  				if ($db->query()){
  					 $error = '';
		   			 $db->setQuery('UPDATE #__fb_users SET posts=posts+1 WHERE userid='.$user->id);
  					 $db->query();
  				}
  			}
  		}  		
  		$mainframe->redirect(Basic::uriBase().'/forum?cid='.$cid, $error); 		
  	}
  }
	
	function change(){
		$user = &User::getInstance();
		$db = &Database::getInstance();
		if ($user->gid < 18) return;
		$tid = JRequest::getInt('tid',-1);
		$sticky = JRequest::getInt('sticky',0);
		$query = 'SELECT b.group,b.moderators FROM #__forum_topics AS a JOIN #__forum_cats AS b ON b.id=a.cid WHERE a.id='.$tid;
		$db->setQuery($query);
		$obj = $db->loadObject();
		if (!$obj) return;
		$mods = getModers($obj->moderators);
		if (in_array($user->id,$mods)){
			if ($sticky) $tmp = 'sticky=1-sticky';
			else $tmp = 'locked=1-locked';
			$query = 'UPDATE #__forum_topics SET '.$tmp.' WHERE id='.$tid;
			$db->setQuery($query);
			$db->query();
		}
		global $mainframe;
		$mainframe->redirect(Basic::uriBase().'/forum?tid='.$tid); 		
	}
	
	function del(){
		$user = &User::getInstance();
		$db = &Database::getInstance();
		if ($user->gid < 18) return;
		$pid = JRequest::getInt('pid',-1);
		$query = 'SELECT c.who_id,a.id,a.cid,a.last_id,b.moderators FROM #__forum_posts AS c JOIN #__forum_topics AS a ON a.id=c.tid JOIN #__forum_cats AS b ON b.id=a.cid WHERE c.id='.$pid;
		$db->setQuery($query);
		$obj = $db->loadObject();
		if (!$obj) return;
		$mods = getModers($obj->moderators);
		global $mainframe;
		if (in_array($user->id,$mods) || $obj->who_id === $user->id){
			if ($obj->id == $pid){ //prva - brisi celu temu
				$query = 'DELETE FROM #__forum_posts WHERE tid='.$obj->id.';';
				$query .= 'UPDATE #__forum_cats SET numTopics=numTopics-1,numPosts=numPosts-(SELECT replies+1 FROM #__forum_topics WHERE id='.$obj->id.') WHERE id='.$obj->cid.';';
				$query .= 'DELETE FROM #__forum_topics WHERE id='.$obj->id.';';
				//$query .= 'UPDATE #__forum_cats SET last_topic_id=(SELECT id FROM #__forum_topics WHERE cid='.$obj->cid.' ORDER BY time DESC LIMIT 1) WHERE id='.$obj->cid.';';
				$db->setQuery($query);
				if ($db->queryBatch(true,true)){ //samo se prvom useru brise posts count jbg :)
					$db->setQuery('UPDATE #__fb_users SET posts=posts-1 WHERE userid='.$obj->who_id);
  				$db->query();
  			}
		 		$mainframe->redirect('/forum?cid='.$obj->cid);
			}
			//TBD:: za ova poslednja dva mozda da promenim i vremena u topics?
			else if ($obj->last_id == $pid){ //poslednja
				$query = 'SELECT id,username,who_id,time FROM #__forum_posts WHERE id<'.$pid.' AND tid='.$obj->id.' ORDER BY time DESC LIMIT 1';
				$db->setQuery($query);
				$obj2 = $db->loadObject();
				if (!$obj) return;
				$query = 'DELETE FROM #__forum_posts WHERE id='.$pid.';';
				$query .= "UPDATE #__forum_topics SET time=$obj2->time,last_id=$obj2->id,last_userid=$obj2->who_id,last_username='$obj2->username',replies=replies-1 WHERE id=$obj->id;";
				$query .= 'UPDATE #__forum_cats SET numPosts=numPosts-1 WHERE id='.$obj->cid.';';
				$db->setQuery($query);
				if ($db->queryBatch(true,true)){
					$db->setQuery('UPDATE #__fb_users SET posts=posts-1 WHERE userid='.$obj->who_id);
  				$db->query();
  			}
			}
			else {
				$query = 'DELETE FROM #__forum_posts WHERE id='.$pid.';';
				$query .= 'UPDATE #__forum_topics SET replies=replies-1 WHERE id='.$obj->id.';';
				$query .= 'UPDATE #__forum_cats SET numPosts=numPosts-1 WHERE id='.$obj->cid.';';
				$db->setQuery($query);
				if ($db->queryBatch(true,true)){
					$db->setQuery('UPDATE #__fb_users SET posts=posts-1 WHERE userid='.$obj->who_id);
  				$db->query();
  			}
			}

 		  $mainframe->redirect('/forum?tid='.$obj->id); 				
		}
	}
	
	function move(){
		$tid = JRequest::getInt('tid',0);
		$user = &User::getInstance();
		$db = &Database::getInstance();
		if ($user->gid < 18) return;
		$query = 'SELECT a.id,a.cid,a.replies,a.last_id,b.moderators FROM #__forum_topics AS a JOIN #__forum_cats AS b ON b.id=a.cid WHERE a.id='.$tid.' AND b.group=0';
		$db->setQuery($query);
		$obj = $db->loadObject();
		if (!$obj) return;
		$obj->replies += 1;
		$mods = getModers($obj->moderators);
		$ncid = JRequest::getInt('ncid',0);
		global $mainframe;
		if (in_array($user->id,$mods)){
			$query = "UPDATE #__forum_topics SET cid=$ncid WHERE id=$tid;";
			//$query .= "UPDATE #__forum_cats SET numTopics=numTopics-1,numPosts=numPosts-$obj->replies,last_topic_id=(SELECT id FROM #__forum_topics WHERE cid=$obj->cid ORDER BY time DESC LIMIT 1) WHERE id=$obj->cid;";
			//$query .=  "UPDATE #__forum_cats SET numTopics=numTopics+1,numPosts=numPosts+$obj->replies,last_topic_id=(SELECT id FROM #__forum_topics WHERE cid=$ncid ORDER BY time DESC LIMIT 1) WHERE id=$ncid AND `group`=0;";
			$query .= "UPDATE #__forum_cats SET numTopics=numTopics-1,numPosts=numPosts-$obj->replies WHERE id=$obj->cid;";
			$query .=  "UPDATE #__forum_cats SET numTopics=numTopics+1,numPosts=numPosts+$obj->replies WHERE id=$ncid AND `group`=0;";
			$db->setQuery($query);
			$db->queryBatch(true,true);
		}
		$mainframe->redirect(Basic::uriBase().'/forum');
	}
	
	function search(){
		$user = &User::getInstance();
		if ($user->gid < 18) return;
		$view = $this->getView( 'search', 'html' );
		$model = $this->getModel( 'search', 'modelforum' ); 
		$view->setModel( $model, true ); 
   	$view->display(); 
	}
}
?>