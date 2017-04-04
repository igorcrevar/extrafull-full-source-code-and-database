<?php
defined('CREW') or die();

class UserController extends JController
{
	function display(){
		$viewName = JRequest::getCmd('view', 'register'); //[A-Za-z0-9.-_]
		$doc = &JFactory::getDocument();
		$viewType = $doc->getType();
		$view = $this->getView( $viewName, $viewType );
		if ($viewName != 'register'){
			$model = $this->getModel( $viewName, 'usermodel' ); 
			$view->setModel( $model, true ); 
		}
   	$view->display(); 
	}
	
  function displayimg(){	
	   exit();
	}
	
	function cancel(){
		$this->setRedirect( 'index.php' );
	}

	function register_save()
	{
		global $mainframe;
		$username = JRequest::getString('username','');
		$email = JRequest::getString('email','');
		$password = JRequest::getString('password','');
		$password2 = JRequest::getString('password2','');
		$email = JRequest::getVar( 'email','', 'string', JREQUEST_ALLOWRAW );	
		$year = JRequest::getInt('birth_year',1946);
		$month = JRequest::getInt('birth_month',1);
		$day = JRequest::getInt('birth_day',1);
		$birth = $year.'-'.$month.'-'.$day;
		$gender = JRequest::getInt('gender',1);		
		require_once(BASE_PATH.DS.'library'.DS.'userhelper.php');
		$error = array();

		include BASE_PATH.DS.'component'.DS.'user'.DS.'captcha'.DS.'securimage.php';
	  $img = new Securimage();
  	$valid = $img->check(JRequest::getString('scode') );

	  if($valid == false) {
  		//$error[] = JText::_('WRONG_CAPTCHA');
  	}
		if ( JString::strlen($email)<3 || JString::strlen($email)>50 || !JUserHelper::isEmail($email) ) {			
			$error[] = JText::_('WRONG_EMAIL');
		}		
		if (JString::strlen($password)<3 || JString::strlen($password)>20  || $password != $password2 ) {
			$error[] = JText::_('WRONG_PASS');
		}
		if (JString::strlen($username)<4 || JString::strlen($username)>25 ) {
			$error[] = JText::_('WRONG_USER');
		}
		if ( !count($error) ){
			$db = &Database::getInstance();
			$query = 'SELECT id FROM #__users WHERE username='.$db->Quote($username);
			$db->setQuery( $query );
			$xid = intval( $db->loadResult() );
			if ($xid) {			
				$error[] = JText::_('WRONG_USER2');
			}
			if ( !count($error) ){
				$query = 'SELECT id FROM #__users WHERE email='.$db->Quote($email);
				$db->setQuery( $query );
				$xid = intval( $db->loadResult() );
				if ($xid) {			
					$error[] = JText::_('WRONG_EMAIL2');
				}
			}
		}		
		if ( !count($error) ){
			require_once(BASE_PATH.DS.'library'.DS.'userhelper.php');
			$salt = JUserHelper::genRandomPassword(32);
			$crypt = JUserHelper::getCryptedPassword($password, $salt);
			$password = $crypt.':'.$salt;

	// If there was an error with registration, set the message and display form
	
		// Send registration confirmation mail
		
	  //$password = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
		//$password = preg_replace('/[\x00-\x1F\x7F]/', '', $password); //Disallow control chars in the email
		//UserController::_sendMail($user, $password);
		
		//A joj sta cu kada ovo treba transaktivno
		  $qUsername = $db->Quote($username);
			$qEmail = $db->Quote($email);
			$qPassword = $db->Quote($password);
			$qBirth = $db->Quote($birth);
			$qGender = $db->Quote($gender);
		  $query = "INSERT INTO #__users (name,username,email,password,gid,params,birthdate,gender) VALUES ('xyz',$qUsername,$qEmail,$qPassword,18,'',$qBirth,$qGender)";
			$db->setQuery( $query );
			if ( $db->query()	){	
				$db->setQuery( "SELECT id FROM #__users where username=".$db->Quote($username) );
				$user_id = $db->loadResult();			
				if ( $db->query()	){	
					$query = "INSERT INTO #__fb_users (userid) VALUES ($user_id)";
					$db->setQuery( $query );
					if ($db->query()){	
						$this->setRedirect('index.php',JText::_('REG_OK'));
						return;
					}		
		  	}	  	
		  }
		  $error[] = 'Greska';	
	  }
	  $rv = new stdClass();
	  $rv->username = $username;
	  $rv->email = $email;
	  $rv->year = $year;
	  $rv->month = $month;
	  $rv->day = $day;
	  $rv->gender = $gender;
	  $view = $this->getView( 'register', 'html' );
	  $view->oldVals = $rv;
	  $view->errors = $error;
   	$view->display(); 
	}

	
	function requestreset()
	{
		$email		= JRequest::getString('email', '', 'post');
		$model = $this->getModel('Reset','UserModel');
		if ($model->requestReset($email) === false){
			$message = $model->getError();
			$this->setRedirect('index.php?option=user&view=reset', $message);
			return false;
		}

		$this->setRedirect('index.php?option=user&view=reset&layout=confirm');
	}

	/**
	 * Password Reset Confirmation Method
	 *
	 * @access	public
	 */
	function confirmreset(){
		// Get the input
		$token = JRequest::getString('token', '','GET');
		$uid = JRequest::getInt('uid',0,'GET');
		// Get the model
		$model = $this->getModel('Reset','UserModel');
		// Verify the token
		if ($model->confirmReset($uid,$token) === false)
		{
			//$message = sprintf('PASSWORD_RESET_CONFIRMATION_FAILED', $model->getError());
			$message = $model->getError();
			$this->setRedirect('index.php', $message);
			return false;
		}

		$this->setRedirect('index.php?option=user&view=reset&layout=complete');
	}

	/**
	 * Password Reset Completion Method
	 *
	 * @access	public
	 */
	function completereset()
	{
		// Verify the submission
		$sess = &Session::getInstance();
		$token = $sess->get('user.reset.token', '');
		if(!JRequest::getInt($token, 0, 'POST')) {
			$this->setRedirect('index.php', 'Hacking attempt!'.$token);
			return false;
		}

		// Get the input
		$password1 = JRequest::getVar('password1', null, 'post', 'string', JREQUEST_ALLOWRAW);
		$password2 = JRequest::getVar('password2', null, 'post', 'string', JREQUEST_ALLOWRAW);

		// Get the model
		$model = $this->getModel('Reset','UserModel');

		// Reset the password
		if ($model->completeReset($password1, $password2) === false)
		{
			$message = $model->getError();
			//$message = sprintf('PASSWORD_RESET_FAILED', $model->getError());
			$this->setRedirect('index.php?option=user&view=reset&layout=complete', $message);
			return false;
		}

		$message = JText::_('PASSWORD_RESET_SUCCESS');
		$this->setRedirect('index.php', $message);
	}

}
?>