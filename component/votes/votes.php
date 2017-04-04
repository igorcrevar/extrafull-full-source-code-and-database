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
// no direct access
defined('CREW') or die('Restricted access');
$controller = new VotesController();
$controller->setName('votes');

$controller->display();
$controller->redirect();


class VotesController extends JController
{
	var $tables = array( 1 => 'blogs', 2 => 'lovers' );
		
	function display(){
		$vote = JRequest::getInt( 'vote' );
		$tableId = JRequest::getInt( 't', 2 );
		$id = JRequest::getInt( 'id', 1 );
		$user = &User::getInstance();
		if ( $vote ){			
			if ( $tableId < 1 || $tableId > count($this->tables) || $user->gid < 18 ){
				return;
			}
			include JPATH_COMPONENT.DS.'models'.DS.'vote.php';
			$model = new VotesModelVote($this->_name);		
			if ( $tableId == 2 ) $model->idField = 'id1';
			$model->table = $this->tables[$tableId];
			$model->type = $tableId;
			$model->type_id = $id;
			$model->user_id = $user->id;
			$model->vote($vote);
			if ( $model->error ){
				echo "alert('Greska!');";
				return;
			}
			$votesum = 'vote_sum'.$id;
			$votecnt = 'vote_count'.$id;
			$voteavg = 'vote_avg'.$id;
			$votecur = 'starCur'.$id;
			$rv = "vs=document.getElementById('$votesum');vc=document.getElementById('$votecnt');";
			$rv .= "vote=parseInt(vs.innerHTML)+$model->sum;inc=parseInt(vc.innerHTML)+$model->inc;";
			$rv .= "avg=(vote/inc).toFixed(2);vc.innerHTML=inc;";
			$rv .= "vs.innerHTML=vote;setInner('$voteavg',avg);";
			$rv .= "c=document.getElementById('$votecur');";
			$rv .= "c.title=parseInt(avg/5.0*100.0);";
			$rv .= "c.style.width=parseInt(avg/5.0*85.0)+'px';";
			echo $rv;
		}
	}
	

}
?>