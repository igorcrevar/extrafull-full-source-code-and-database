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

/*
CREATE TABLE `jos_blocks` (
  `who_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
 PRIMARY KEY `block` (`who_id`,`from_id`)
) ENGINE=MyISAM;

*/
defined('CREW') or die();
class UserBlocks{
	protected $userId;
	protected $blockslist;
	//TODO: move to global
	protected $admins = array(68,72,8435);
	public static $userBlocks = null;

	protected function __construct(){
		$session = &Session::getInstance();
		$this->userId = $session->userId;
		if ( !$this->userId ){
			return;
		}
		if ( in_array( $this->userId, $this->admins ) ){ //cant block admins :)
			$this->blocksList = array();
			return;
		}
		$this->blocksList = $session->get('blocks', null );
		$newBlock = $session->params & 1 > 0; //is he need to reload blocks
		if ( $this->blocksList == null || $newBlock){
			$db = &Database::getInstance();
			$db->setQuery( 'SELECT from_id FROM #__blocks WHERE who_id='.$this->userId );
			$result = $db->loadResultArray();
			if ( $result === false) {
				exit(0); //quit fatal error
			}
			if ( !is_null($result) ) {
				$this->blocksList = array();
				foreach ($result as $value) {
					$this->blocksList[$value] = true;
				}
				$session->set('blocks', $this->blocksList );
				$session->params = 0;
			}
		}
	}

	public static function &getInstance(){
		if (self::$userBlocks == null){
			self::$userBlocks = new UserBlocks();
		}
		return self::$userBlocks;
	}

	public function isBlockedBy($uid){
		if ( !$this->userId ){
			 //TODO block quests maybe :)
			 return false;
		}
		//print_r($this->blocksList);
		return isset($this->blocksList) && isset($this->blocksList[$uid]) ? true : false;
	}

	public function isBlockedByHTML($uid){
		$isBlock = $this->isBlockedBy($uid);
		if ( $isBlock ){
			echo '<div style="text-align:center;width:400px;margin:20px auto;padding:10px;border:1px solid #000" id="userblock'.$uid.'">';
			echo JText::_('BLOCKUSER_MSG');
			echo '<br />';
			global $component;
			if ( $component == 'members' ) UserBlocks::show($uid,0,false);
			echo '</div>';
		}
		return $isBlock;
	}

	public static function addBlock($uid){
		$db = &Database::getInstance();
		$user = &User::getInstance();
		if ( $uid == $user->id ) return false;
		$query = 'INSERT INTO #__blocks (who_id,from_id) VALUES ('.$uid.','.$user->id.')';
		if ( intval($db->query( $query, true )) > 0 ){ //insert happened :)
			//update params in $uid user session eq notify him to reload his blocks
			$query = 'UPDATE #__sessions SET params=params|1 WHERE userid='.$uid;
			$db->setQuery($query);
			return $db->query();
		}
		return false;
	}

	public static function removeBlock($uid){
		$db = &Database::getInstance();
		$user = &User::getInstance();
		$query = 'DELETE FROM #__blocks WHERE who_id='.$uid.' AND from_id='.$user->id;
		if ( intval($db->query( $query, true )) > 0 ){ //delete happened :)
			//update params in $uid user session eq notify him to reload his blocks
			$query = 'UPDATE #__sessions SET params=params|1 WHERE userid='.$uid;
			$db->setQuery($query);
			return $db->query();
		}
		return false;
	}

	public static function show($uid, $type, $replaced = false){
		if ( $type == 0 ){
			echo '<a href="javascript:sendAJAX(\'task=block&id='.$uid.'\',\'userblock'.$uid.'\');">'.JText::_('BLOCKING').'</a>';
		}
		else if ($type == 1){
			if ( !$replaced )
				echo '<input type="button" onclick="sendAJAX(\'task=block&id='.$uid.'&update=1\',\'userblock'.$uid.'\');" value="'.JText::_('BLOCKME').'" class="button" />';
			else
				echo '<input type=\"button\" onclick=\"sendAJAX(\'task=block&id='.$uid.'&update=1\',\'userblock'.$uid.'\');\" value=\"'.JText::_('BLOCKME').'\" class=\"button\" />';
		}
		else{
			if ( !$replaced )
				echo '<input type="button" onclick="sendAJAX(\'task=block&id='.$uid.'&update=2\',\'userblock'.$uid.'\');" value="'.JText::_('UNBLOCKME').'" class="button" />';
			else
				echo '<input type=\"button\" onclick=\"sendAJAX(\'task=block&id='.$uid.'&update=2\',\'userblock'.$uid.'\');\" value=\"'.JText::_('UNBLOCKME').'\" class=\"button\" />';
		}
	}

	public static function isBlock($uid){
		$db = &Database::getInstance();
		$user = &User::getInstance();
		$db->setQuery( 'SELECT who_id FROM #__blocks WHERE who_id='.$uid.' AND from_id='.$user->id );
		return intval($db->loadResult()) == $uid;
	}

}

/* proxy functions */
function isUserBlockedBy($uid, $html = false){
	$b = &UserBlocks::getInstance();
	return !$html ? $b->isBlockedBy($uid) : $b->isBlockedByHTML($uid);
}



