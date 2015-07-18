<?php

namespace util;
use Zend\Mail\Message;
use Zend\Mime;

/*
 * Methods to help determine a private exchange domain admin
 * or demand customer's credit approvals
 */
class CreditHelper {
	
	private static function getDemandCustomerInfoIDFromAuthUserID($auth_user_id) {
		
		$authUsersFactory = \_factory\authUsers::get_instance();
		
		$params = array();
		$params["user_id"] = $auth_user_id;
		$authUser = $authUsersFactory->get_row($params);
		
		if ($authUser != null):
			
			$authUser->DemandCustomerInfoID;
		
		endif;
		
		return false;
	}
	
	public static function wasApprovedForPlatfromConnectionInventoryAuthUserID($auth_user_id) {
			
		$demand_customer_info_id = self::getDemandCustomerInfoIDFromAuthUserID($auth_user_id);
		
		if ($demand_customer_info_id === false) return false;
		
		return self::wasApprovedForPlatfromConnectionInventory($demand_customer_info_id);
		
	}
	
	public static function wasApprovedForPlatfromConnectionInventory($demand_customer_info_id) {
		
		$DemandCustomerInfoFactory = \_factory\DemandCustomerInfo::get_instance();
		
		$params = array();
		$params["DemandCustomerInfoID"] = $demand_customer_info_id;
		$DemandCustomerInfo = $DemandCustomerInfoFactory->get_row($params);
		
		if ($DemandCustomerInfo != null):
		 
			return $DemandCustomerInfo->ApprovedForPlatformConnectionInventory == 1 ? true : false;

		endif;
		
		return false;
	}
	
	public static function wasApprovedForSspRtbInventoryAuthUserID($auth_user_id) {
			
		$demand_customer_info_id = self::getDemandCustomerInfoIDFromAuthUserID($auth_user_id);
	
		if ($demand_customer_info_id === false) return false;
	
		return self::wasApprovedForSspRtbInventory($demand_customer_info_id);
	
	}
	
	public static function wasApprovedForSspRtbInventory($demand_customer_info_id) {
		
		$DemandCustomerInfoFactory = \_factory\DemandCustomerInfo::get_instance();
		
		$params = array();
		$params["DemandCustomerInfoID"] = $demand_customer_info_id;
		$DemandCustomerInfo = $DemandCustomerInfoFactory->get_row($params);
		
		if ($DemandCustomerInfo != null):
			
			return $DemandCustomerInfo->ApprovedForSspRtbInventory == 1 ? true : false;
		
		endif;
		
		return false;
	}
	
	public static function creditApplicationWasSentAuthUserID($auth_user_id) {
			
		$demand_customer_info_id = self::getDemandCustomerInfoIDFromAuthUserID($auth_user_id);
	
		if ($demand_customer_info_id === false) return false;
	
		return self::creditApplicationWasSent($demand_customer_info_id);
	
	}
	
	public static function creditApplicationWasSent($demand_customer_info_id) {
		
		$DemandCustomerInfoFactory = \_factory\DemandCustomerInfo::get_instance();
		
		$params = array();
		$params["DemandCustomerInfoID"] = $demand_customer_info_id;
		$DemandCustomerInfo = $DemandCustomerInfoFactory->get_row($params);
		
		if ($DemandCustomerInfo != null):
			
			$date_was_sent = date("m/d/Y", strtotime($DemandCustomerInfo->DateCreditApplicationWasSent));
		
			return $DemandCustomerInfo->CreditApplicationWasSent == 1 ? $date_was_sent : false;
		
		endif;
		
		return false;
	}
	
	public static function sendCreditApplicationAuthUserID($auth_user_id) {
			
		$demand_customer_info_id = self::getDemandCustomerInfoIDFromAuthUserID($auth_user_id);
	
		if ($demand_customer_info_id === false) return false;
	
		return self::sendCreditApplication($demand_customer_info_id);
	
	}
	
	public static function sendCreditApplication($demand_customer_info_id) {
		
		$DemandCustomerInfoFactory = \_factory\DemandCustomerInfo::get_instance();
		
		$params = array();
		$params["DemandCustomerInfoID"] = $demand_customer_info_id;
		$DemandCustomerInfo = $DemandCustomerInfoFactory->get_row($params);
		
		if ($DemandCustomerInfo != null):

			// approval, send out email
			$subject = "Your NginAd Exchange Private Exchange Requires Credit Approval";
		
			$message = 'Your NginAd Private Exchange for: ' . $DemandCustomerInfo->Website . ', ' . $DemandCustomerInfo->Company . ', needs credit approval before you can access Platform Connection Inventory and SSP RTB Inventory.'
					. '<br /><br />Please download the credit application <a href="http://server.nginad.com/forms/credit.application.pdf">here</a>.'
					. '<br /><br />Fill out the application completely and email it back to:' . $this->config_handle['mail']['reply-to']['email']
					. '<br /><br />Once your application is approved we will set a credit limit for your programmatic media buys and enable either the Platform Connection inventory, or the SSP RTB inventory or both depending on your credit worthiness.';
			
			$transport = $this->getServiceLocator()->get('mail.transport');
			
			$text = new Mime\Part($message);
			$text->type = Mime\Mime::TYPE_HTML;
			$text->charset = 'utf-8';
			
			$mimeMessage = new Mime\Message();
			$mimeMessage->setParts(array($text));
			$zf_message = new Message();
			$zf_message->addTo($DemandCustomerInfo->Email)
			->addFrom($this->config_handle['mail']['reply-to']['email'], $this->config_handle['mail']['reply-to']['name'])
			->setSubject($subject)
			->setBody($mimeMessage);
			$transport->send($zf_message);
			
			$DemandCustomerInfoFactory->markCreditApplicationSent($demand_customer_info_id);
		
		endif;
	}
	
}

