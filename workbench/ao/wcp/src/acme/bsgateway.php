<?php namespace Ao\Wcp\Acme;
/**
 * @author Browser Based Network Limited
 * @version 1.0
 * @since August 24, 2012
 */

class BSGateway {
	protected $username = null;
	protected $mobile = null;
	protected $sender = null;
	protected $message = null;
	protected $isflash = 0;
	protected $schedule = 0;
	protected $sendTime = 0;
	protected $sendScheduleNotification = 0;
	protected $scheduleName = null;
	protected $appid = 0;
	protected $callBack = 0;
	protected $messageIds = null;
	protected $responseString = array ();
	protected $countryCode = null;
	
	const BROADCAST_URL_V2 = 'http://gateway.sms.bbnplace.com/api/sendtext.php';
	const BROADCAST_URL = 'https://www.bbnplace.com/sms/bulksms/bulksms.php';
	const BALANCE_URL = 'https://www.bbnplace.com/sms/bulksms/acctbals.php';
	const LOGIN_TRIAL = 'https://www.bbnplace.com/sms/bulksms/login.php';
	const DLR_URL = 'http://gateway.sms.bbnplace.com/api/dlreport.php';
	
	public function __construct($config = array()) {
		if (count ( $config ) > 0) {
			$this->appid = ( int ) $config ['appid'];
			$this->callBack = ( int ) $config ['callback'];
		}
	}
	
	/**
	 * @param string $apiurl
	 * @param string $params
	 * @return string
	 */
	protected function doBroadcast($apiurl, $params) {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $apiurl );
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $params );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		$response = curl_exec ( $ch );
		return $response;
	}
	
	/**
	 * @param string $myUsername
	 * @param string $myPassword
	 * @return string
	 */
	public function tryLogin($myUsername, $myPassword) {
		$this->username = ( string ) $myUsername;
		$this->password = ( string ) $myPassword;
		$parameters = sprintf ( "username=%s&password=%s", urlencode ( $this->username ), urlencode ( $this->password ) );
		
		$responseString = $this->doBroadcast ( self::LOGIN_TRIAL, $parameters );
		return $responseString;
	}
	/**
	 * @param string $myUsername
	 * @param string $myPassword
	 * @param string $mySender
	 * @param string $myRecipients
	 * @param string $myMessage
	 * @param int $flash
	 * @param string $messageid
	 * @return string
	 */
	public function sendMessage($myUsername, $myPassword, $mySender, $myRecipients, $myMessage, $flash = 0, $countrycode=null, $messageid=null) {
		$this->username = ( string ) $myUsername;
		$this->password = ( string ) $myPassword;
		$this->sender = ( string ) $mySender;
		$this->message = ( string ) $myMessage;
		$this->mobile = ( string )  $myRecipients;
		$this->isflash = ( int ) $flash;
		$this->countryCode = (int)$countrycode;
		
		if(!is_null($messageid)){
			$this->messageIds = trim($messageid);
		}
		
		$additional_params = array();
		if($this->appid > 0){
			$additional_params[] = "appid=$this->appid";
		}
		
		if ($this->callBack == 1){
			$additional_params[] = "callback=$this->callBack";
		}
		
		if(!empty($this->messageIds)){
			$additional_params[] = "messageid=$this->messageIds";
		}
		
		if($this->countryCode > 0){
			$additional_params[] = "ccode=$this->countryCode";
		}
		
		$parameters = sprintf ( "username=%s&password=%s&sender=%s&mobile=%s&flash=%d&message=%s", urlencode ( $this->username ), urlencode ( $this->password ), urlencode ( $this->sender ), urlencode ( $this->mobile ), $this->isflash, urlencode ( $this->message ) );
		
		if (count($additional_params)>0){
			$parameters .= '&'.implode('&', $additional_params);
			$responseString = $this->doBroadcast(self::BROADCAST_URL_V2, $parameters);
		}else{
			$responseString = $this->doBroadcast ( self::BROADCAST_URL, $parameters );
		}
		return $responseString;
	}
	
	/**
	 * @param string $myUsername
	 * @param string $myPassword
	 * @param string $mySender
	 * @param string $myRecipients
	 * @param string $myMessage
	 * @param int $myBroadcastTime
	 * @param string $myScheduleName
	 * @param int $notifyMe
	 * @param int $flash
	 * @return string
	 */
	public function scheduleMessage($myUsername, $myPassword, $mySender, $myRecipients, $myMessage, $myBroadcastTime, $myScheduleName=null, $notifyMe=0, $flash=0){
		$this->username = (string)$myUsername;
		$this->password = (string)$myPassword;
		$this->sender = (string)$mySender;
		$this->message = (string)$myMessage;
		$this->mobile = (string)$myRecipients;
		$this->isflash = (int)$flash;
		$this->schedule = 1;
		$this->sendTime = (int)$myBroadcastTime;
		$this->scheduleName = (string)$myScheduleName;
		$this->sendScheduleNotification = (int)$notifyMe;
	
		$parameters = sprintf ( "username=%s&password=%s&sender=%s&mobile=%s&flash=%d&message=%s&schedule=%d&broadcast_time=%d&schedule_name=%s&schedule_notification=%d",
				urlencode($this->username),
				urlencode($this->password),
				urlencode($this->sender),
				urlencode($this->mobile),
				$this->isflash,
				urlencode($this->message),
				$this->schedule,
				$this->sendTime,
				urlencode($this->scheduleName),
				$this->sendScheduleNotification);
		$responseString = $this->doBroadcast ( self::BROADCAST_URL, $parameters );
		return $responseString;
	}
	
	/**
	 * @param string $myUsername
	 * @param string $myPassword
	 * @return string
	 */
	public function checkBalance($myUsername, $myPassword) {
		$this->username = ( string ) $myUsername;
		$this->password = ( string ) $myPassword;
		$parameters = sprintf ( "username=%s&password=%s", urlencode ( $this->username ), urlencode ( $this->password ) );
		$responseString = $this->doBroadcast ( self::BALANCE_URL, $parameters );
		return $responseString;
	}
	
	/**
	 * @param string $messageid
	 * @return string
	 */
	public function loadDLReport($messageid){
		if ($this->appid === 0){
			return false;
		}else{
			$parameters = sprintf('appid=%s&messageid=%s', $this->appid, $messageid);
			$responseString = $this->doBroadcast(self::DLR_URL, $parameters);
			return $responseString;
		}
	}
}
