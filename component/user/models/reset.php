<?php
defined('CREW') or die;

/**
 * User Component Reset Model
 *
 * @author		Rob Schley <rob.schley@joomla.org>
 * @package		Joomla
 * @subpackage	User
 * @since		1.5
 */
class UserModelReset extends JModel
{
	/**
	 * Registry namespace prefix
	 *
	 * @var	string
	 */
	var $_namespace	= 'user.reset.';

	/**
	 * Verifies the validity of a username/e-mail address
	 * combination and creates a token to verify the request
	 * was initiated by the account owner.  The token is
	 * sent to the account owner by e-mail
	 *
	 * @since	1.5
	 * @param	string	Username string
	 * @param	string	E-mail address
	 * @return	bool	True on success/false on failure
	 */
	function requestReset($email){
		require_once(BASE_PATH.DS.'library'.DS.'userhelper.php');

		$db = &Database::getInstance();

		// Make sure the e-mail address is valid
		if (!JUserHelper::isEmail($email))
		{
		  $this->addError( JText::_('INVALID_EMAIL_ADDRESS') );
			return false;
		}

		// Build a query to find the user
		$query	= 'SELECT id,name,username FROM #__users WHERE email = '.$db->Quote($email).' AND block = 0';
		$db->setQuery($query);
		// Check the results
		$obj = $db->loadObject();
		if ( !count($obj) ){
			$this->addError( JText::_('COULD_NOT_FIND_USER'));
			return false;
		}

		$name = ($obj->name != '' && $obj->name != 'nema_ime' && $obj->name != 'nema') ? $obj->name : $obj->username;
		// Generate a new token
		$token = md5( JUserHelper::genRandomPassword() );

		$query	= 'UPDATE #__users SET activation = '.$db->Quote($token).' WHERE id='.$obj->id.' AND block = 0';
		$db->setQuery($query);
		// Save the token
		if (!$db->query()){
		  $this->addError( 'Database error!');
			return false;
		}

		// Send the token to the user via e-mail
		if ( !$this->_sendConfirmationMail($obj->id,$name,$email, $token) ){
			return false;
		}

		return true;
	}

	/**
	 * Checks a user supplied token for validity
	 * If the token is valid, it pushes the token
	 * and user id into the session for security checks.
	 *
	 * @since	1.5
	 * @param	token	An md5 hashed randomly generated string
	 * @return	bool	True on success/false on failure
	 */
	function confirmReset($uid,$token){
		require_once(BASE_PATH.DS.'library'.DS.'userhelper.php');
		$sess = &Session::getInstance();		
		if(strlen($token) != 32) {
			$this->addError( JText::_('INVALID_TOKEN'));	
			return false;
		}
		
		$db = &Database::getInstance();
		$db->setQuery('SELECT id FROM #__users WHERE id='.$uid.' AND block = 0 AND activation = '.$db->Quote($token));

		// Verify the token
		$id = intval( $db->loadResult() );
		if (!$id)
		{
			$this->addError( JText::_('INVALID_TOKEN'));
			return false;
		}
		
		$sess->set($this->_namespace.'token', $token);
		$sess->set($this->_namespace.'id', $id);

		// Push the token and user id into the session
		return true;
	}

	/**
	 * Takes the new password and saves it to the database.
	 * It will only save the password if the user has the
	 * correct user id and token stored in her session.
	 *
	 * @since	1.5
	 * @param	string	New Password
	 * @param	string	New Password
	 * @return	bool	True on success/false on failure
	 */
	function completeReset($password1, $password2){
		require_once(BASE_PATH.DS.'library'.DS.'userhelper.php');
		$sess = &Session::getInstance();	
		$db = &Database::getInstance();	

		global $mainframe;
		
		// Make sure that we have a pasword
		if ( ! $password1  &&  strlen($password1) < 3){
			$this->addError( JText::_('MUST_SUPPLY_PASSWORD'));
			return false;
		}

		// Verify that the passwords match
		if ($password1 != $password2)	{
			$this->addError( JText::_('PASSWORDS_DO_NOT_MATCH_LOW'));
			return false;
		}

		// Get the necessary variables
		$token = $sess->get($this->_namespace.'token', '');
		$id = intval( $sess->get($this->_namespace.'id', '0') );
		$salt		= JUserHelper::genRandomPassword(32);
		$crypt		= JUserHelper::getCryptedPassword($password1, $salt);
		$password	= $crypt.':'.$salt;


		$query 	= 'UPDATE #__users'
				. ' SET password = '.$db->Quote($password)
				. ' , activation = ""'
				. ' WHERE id = '.$id
				. ' AND activation = '.$db->Quote($token)
				. ' AND block = 0';
		$db->setQuery($query);
		// Save the password
		if ( !$db->query() ){
			$this->addError( JText::_('DATABASE_ERROR'));
			return false;
		}

		// Flush the variables from the session
		$sess->set($this->_namespace.'token', null);
		$sess->set($this->_namespace.'id', null);
		
		return true;
	}

	function _sendConfirmationMail($id,$name,$email, $token){
		$sitename	= 'extrafull';

		// Set the e-mail parameters
		//$subject	= sprintf( JText::_('PASSWORD_RESET_CONFIRMATION_EMAIL_TITLE'), $sitename);
		$subject = JText::_('PASSWORD_RESET_CONFIRMATION_EMAIL_TITLE');
		$body		= sprintf(JText::_('PASSWORD_RESET_CONFIRMATION_EMAIL_TEXT'), $sitename, $id,$token,$id,$token);
		$senderEmail = 'admin@extrafull.com';
		$senderName = 'Extrafull Community';
		//$header = "From: ".$senderName." <".$senderEmail.">\r\n"; //optional headerfields

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html;charset=UTF-8' . "\r\n";

// Additional headers
		$headers .= 'To: '.$name.' <'.$email.'>'. "\r\n";
		$headers .= 'From: '.$senderName.' <'.$senderEmail.'>' . "\r\n";
		$headers .= 'Cc: '.$senderEmail . "\r\n";
		$headers .= 'Bcc: '.$senderEmail . "\r\n";
		ini_set( 'sendmail_from', $senderEmail ); 
		if ( !mail($email, $subject, $body, $headers) ){ 
			$this->addError( JText::_('ERROR_SENDING_EMAIL') );
			return false;
		}

		return true;
	}
}
?>