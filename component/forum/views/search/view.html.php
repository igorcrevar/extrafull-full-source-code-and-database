<?php
defined( 'CREW' ) or die( 'Restricted access' );

class ForumViewSearch extends JView
{
  function display($tpl = null)
	{		
		$model = $this->getModel();
		$limit = 10;
		$limitstart = JRequest::getInt('limitstart',0);		 	
		$this->error = '';
		if ( isset($_GET['ids'])  ){
			$ids = explode(',',$_GET['ids']);
			$model->initWithIds($ids);
			$this->posts = $model->getPosts($limitstart,$limit);
			$this->pag = JHTML::getPagination( $model->cnt, $limitstart, $limit, Basic::routerBase().'forum?task=search&ids='.join(',',$model->ids), 'html' );
		}
		else{		 	
			$session = &Session::getInstance();
			$oTime = $session->get('forum_search', 0);		 
		 	$nTime = time();
		 	$session->set('forum_search', $nTime);	
		 	if ( $nTime - $oTime < 60  &&  $session->userId != 72){
		 		$this->error = 'Pretraga je ogranicena na jednu pretragu po minuti! Sacekaj malo';
		 	}
		 	else{
		 		$cid = JRequest::getInt('cid', 0);
		 		$sentence = JRequest::getString('sentence');
				$model->init($sentence,$cid);
				$this->posts = $model->getPosts($limitstart,$limit);
				$this->pag = JHTML::getPagination( $model->cnt, $limitstart, $limit, Basic::routerBase().'forum?task=search&ids='.join(',',$model->ids), 'html' );
			}
		}
 		//$this->assignRef('rights',$rights);
 		parent::display( $tpl );
	}
}
?>