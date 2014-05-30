<?php

/*
 * Shineisp_Banks_Ideal_Gateway
* -------------------------------------------------------------
* Type:     class
* Name:     Shineisp_Banks_Ideal_Gateway
* Purpose:  Manage the communications with TargetPay for iDeal transactions
* -------------------------------------------------------------
*/

class Shineisp_Banks_Ideal_Gateway extends Shineisp_Banks_Abstract implements Shineisp_Banks_Interface {

		public function __construct($orderid) {
		parent::__construct ( $orderid );
		parent::setModule ( __CLASS__ );
	}
	
	/**
	 * CreateForm
	 * Create the bank form
	 * @return string
	 */
	public function CreateForm() {
		$order = self::getOrder ();
		$bank = self::getModule ();
		$translator = self::getTranslator ();
		
		if (empty ( $bank ['account'] )) {
			return null;
		}
		
		if ($order) {
		
		$form = '';
		$custom = self::getOrderID ();
		
# Targetpay parameters	
		require_once('func.php');
		$item_name = $translator->translate ( "Order No." ) . " " . $custom . " - " . date ( 'Y' );	
		$rtlo = $bank ['account'];// account number
		$description = substr(trim($item_name),0,32);// max 32 char
		$amount = $order ['grandtotal']*100;
		$returnurl = 'http://'.self::getUrlOk();// http or https is necessary!
		//par1=order id, par2=amount, par3=customer_id
		$token = 'par1-'.$custom.',par2-'.$amount.',par3-'.$order['customer_id'];
		//it might be a good idea to code $token to avoid fooling around with the amount or other parameters.
		$returnurl = $returnurl.'?token='.$token;
		
		//$reporturl = 'http://'.self::getUrlCallback();		
# /Targetpay parameters		
		try {
			if (! self::isHidden ()) {
				
				$form .= '<table width="95%"><tr><td width="50px" valign="top"> ';
				$form .= '<img src="/skins/default/base/images/banks/ideal.gif" border="0" alt="' . $translator->translate ( 'Pay Now' ) . ": " . $bank ['name'] . '" title="' . $translator->translate ( 'Pay Now' ) . ": " . $bank ['name'] . '" width="48px">';
			}			
			
			$form .= '</td><td align="left">';	
			$form .= '<p>Betaal met iDeal via uw eigen bank<br />';//Translation
			$form .= '<form name="bankselect">';
			$form .= '<span style="font-size:14px; font-weight: bold;">Kies eerst uw bank: </span>';//Translation
			$form .= '<select name=bank onChange="document.bankselect.submit();">';
			$form .= '<script src="https://www.targetpay.com/ideal/issuers-nl.js"></script>';// https://www.targetpay.com/ideal/issuers-en.js for english
			$form .= '</select>';
$form .= '</form>';
			$form .= '</p>NB: Onze iDeal betalingen worden verwerkt door <a href="https://www.targetpay.com/info/secure" target="_blank">Targetpay</a>. Dit wordt ook op uw bankafschrift vermeld.</td></tr></table>';// Translation
			$form .= 'Onderstaande banken bieden de mogelijkheid tot iDeal betalingen';// Translation		
			$form .= '<table width="100%"><tr>';
			$form .= '<td><img src="/skins/default/base/images/banks/logo/banklogo_abnamro.png"></td>';
			$form .= '<td><img src="/skins/default/base/images/banks/logo/banklogo_rabo.png"></td>';
			$form .= '<td><img src="/skins/default/base/images/banks/logo/banklogo_friesland.png"></td></tr>';
			$form .= '<tr><td><img src="/skins/default/base/images/banks/logo/banklogo_regio.png"></td>';
			$form .= '<td><img src="/skins/default/base/images/banks/logo/banklogo_ing.png"></td> ';
			$form .= '<td><img src="/skins/default/base/images/banks/logo/banklogo_sns.png"></td></tr>';
			$form .= '<tr><td><img src="/skins/default/base/images/banks/logo/banklogo_asn.png"></td>';
			$form .= '<td><img src="/skins/default/base/images/banks/logo/banklogo_knab.png"></td>';
			$form .= '<td><img src="/skins/default/base/images/banks/logo/banklogo_lanschot.png"></td><tr>';
			$form .= '<tr><td></td> ';
			$form .= '<td><img src="/skins/default/base/images/banks/logo/banklogo_triodos.png"></td> ';
			$form .= '<td></td></tr> ';
			$form .= '</table>';
			
			if (isset($_GET['bank'] ))
			{
				$urltp = StartTransaction($rtlo, $_GET['bank'], $description,$amount, $returnurl, $reporturl);

				header( "Location: ". $urltp ) ;
		 		exit();
			}			
			return array('name' => $bank ['name'], 'description' => $bank ['description'], 'html' => $form);
			//return $form;
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
	}
}

	/**
	 * Response
	 * Create the Order, Invoice and send an email to the customer
	 * @param $response from the Gateway Server
	 * @return order_id or false
	 */
	public function Response($response) {
		$bank = self::getModule ();
		$bankid = $bank ['bank_id'];
		require_once('func.php');
		$rtlo = $bank ['account'];//accountnumber
		// Customer returns from bank to returnurl.
		// Check transaction status
		if ((! empty ( $response ['trxid'])) && (! empty( $response['ec'])))
		{
			$orderid = $response['custom'];

			// 000000 OK is success. Order has been paid and can be handled
			if(($status = CheckReturnurl( $rtlo, $response['trxid'] ))=="000000 OK" )
			{
				//Update order status
				// Replacing the comma with the dot in the amount value. 
				$amount = str_replace ( ",", ".", $response ['amount'] );
				$payment = Payments::addpayment ( $orderid, $response ['trxid'], $bankid, 0, $amount );
				Orders::set_status ( $orderid, Statuses::id("paid", "orders") ); // Paid
				OrdersItems::set_status ( $orderid, Statuses::id("paid", "orders") ); // Paid
				//below just temporarily, must go to callback function
				Orders::Complete ( $orderid, true ); // Complete the order information and it executes all the tasks to do
				Payments::confirm ( $orderid, true ); // Set the payment confirm 
				return $orderid;
				
			}
		// if no succes: error in payment
		// ToDo: add code to handle errors
		
		else
			{
			
			die( $status );
			}
		}

		/*
		if (! empty ( $response ['item_number'] )) {
			
			// Get the indexes of the order 
			$orderid = trim ( $response ['custom'] );
			
			if (is_numeric ( $orderid ) && is_numeric ( $bankid )) {
				
				// Replacing the comma with the dot in the amount value. 
				$amount = str_replace ( ",", ".", $response ['amount'] );
				
				$payment = Payments::addpayment ( $orderid, $response ['thx_id'], $bankid, 0, $amount );
				Orders::set_status ( $orderid, Statuses::id("paid", "orders") ); // Paid
				OrdersItems::set_statuses ( $orderid, Statuses::id("paid", "orders") ); // Paid
				

				return $orderid;
			}
		}
	*/
	}

	/**ToDo
	 * CallBack
	 * This function is called by the bank server in order to confirm the transaction previously executed
	 * @param $response from the Gateway Server
	 * @return boolean
	 */
	public function CallBack($response) {
		Shineisp_Commons_Utilities::logs ( "Start callback", "ideal.log" );
		$bank = self::getModule ();
		$bankid = $bank ['bank_id'];
		require_once('func.php');
		$rtlo = $bank ['account'];//accountnumber
		if ( isset($_POST['rtlo'])&&isset($_POST['trxid'])&& isset($_POST['status'])) 
		{
			HandleReporturl($_POST['rtlo'], $_POST['trxid'], $_POST['status'] );
		}
			
			
		
	}
}
