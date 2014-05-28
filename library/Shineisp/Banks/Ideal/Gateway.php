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
# Targetpay parameters	
		require_once('func.php');
		$item_name = $translator->translate ( "Order No." ) . " " . self::getOrderID() . " - " . date ( 'Y' );	
		$rtlo = 110519;//accountnumber!!
		$description = substr(trim($item_name),0,32);// max 32 char
		$amount = $order ['grandtotal']*100;
		$returnurl = 'http://'.self::getUrlOk();
		//$returnurl = 'http://localhost.domeinnamme.com/cms/2.php';
		// evt. aan $returnurl nog bijv. $amount en $description toevoegen voor evt. verdere verwerking!!  
		//$reporturl = 'http://'.self::getUrlCallback();		
# /Targetpay parameters		
		try {
			if (! self::isHidden ()) {
				
				$form .= '<table width="95%"><tr><td width="50px" valign="top"> ';
				$form .= '<img src="/skins/default/base/images/banks/ideal.gif" border="0" alt="' . $translator->translate ( 'Pay Now' ) . ": " . $bank ['name'] . '" title="' . $translator->translate ( 'Pay Now' ) . ": " . $bank ['name'] . '" width="48px">';
			}			
			
			$form .= '</td><td align="left">';	
			$form .= '<p>Betaal met iDeal via uw eigen bank<br />';
			$form .= '<form name="bankselect">';
			$form .= '<span style="font-size:14px; font-weight: bold;">Kies eerst uw bank: </span>';
			$form .= '<select name=bank onChange="document.bankselect.submit();">';
			$form .= '<script src="https://www.targetpay.com/ideal/issuers-nl.js"></script>';
			$form .= '</select>';
$form .= '</form>';
			$form .= '</p>NB: Onze iDeal betalingen worden verwerkt door <a href="https://www.targetpay.com/info/secure" target="_blank">Targetpay</a>. Dit wordt ook op uw bankafschrift vermeld.</td></tr></table>';
			$form .= 'Onderstaande banken bieden de mogelijkheid tot iDeal betalingen';		
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
			//return array('name' => $bank ['name'], 'description' => $bank ['description'], 'html' => $form);
			return $form;
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
		// De consument komt vanaf de bank terug op de returnurl.
		// Hier controleren we de transactiestatus
		//if( isset($_GET['ec']) && isset($_GET['trxid']))
		if ((! empty ( $response ['trxid'])) && (! empty( $response['ec'])))
		{
			// 000000 OK betekent succesvol. We kunnen het product leveren
			if(($status = CheckReturnurl( $rtlo, $response['trxid'] ))=="000000 OK" )
			{
				// Voeg hier programmacode toe om de orderstatus bij te werken.
				
				$payment = Payments::addpayment ( $orderid, $response ['trx_id'], $bankid, 0, $amount );
				Orders::set_status ( $orderid, Statuses::id("paid", "orders") ); // Paid
				OrdersItems::set_statuses ( $orderid, Statuses::id("paid", "orders") ); // Paid
												
				return $orderid;
				//die( "Status was Successful...<br>Thank you for your order" );
			}
		// Bij alle andere statussen producten niet leveren
		// Voeg hier zelf programmacode toe om de status bij te werken
		else die( $status );
		}
		
		// De reporturl wordt vanaf de Targetpay server aangeroepen
		if ( isset($_POST['rtlo'])&&isset($_POST['trxid'])&& isset($_POST['status'])) 
		{
		HandleReporturl($_POST['rtlo'], $_POST['trxid'], $_POST['status'] );
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

	/**
	 * CallBack
	 * This function is called by the bank server in order to confirm the transaction previously executed
	 * @param $response from the Gateway Server
	 * @return boolean
	 */
	public function CallBack($response) {
		Shineisp_Commons_Utilities::logs ( "Start callback", "ideal.log" );
	// De consument komt vanaf de bank terug op de returnurl.
		$bank = self::getModule ();
		//$bankid = $bank ['bank_id'];
		require_once('func.php');
		$rtlo = 110519;
		echo '<pre>';
		print_r($response);
		echo '</pre>';
		//http://localhost.domeinnamme.com/common/callback/gateway/7b4c0cb89796ad9a06d2277bae78a4b9?trxid=0030000824438908&ec=215316177138874
		// Hier controleren we de transactiestatus
		
		
		//if( isset($_GET['ec']) && isset($_GET['trxid'])){
		// 000000 OK betekent succesvol. We kunnen het product leveren
		$inttrxid = $response['trxid'];
		$intec = $response['ec'];
		echo $inttrxid;
		if(isset($intec) && isset($inttrxid ))
		{
		//if(($status = CheckReturnurl( $rtlo, $_GET['trxid'] ))=="000000 OK" ){
		if(($status = CheckReturnurl( $rtlo, $inttrxid ))=="000000 OK" )
		{		
		echo '// Voeg hier programmacode toe om de orderstatus bij te werken.';
		Shineisp_Commons_Utilities::logs ( "Callback parameters: ".$inttrxid.": ".$status, "ideal.log" );
		
		die( "Status was Successful...<br>Thank you for your order" );
		}
		
		else 
			{echo 'Product niet leveren';
			Shineisp_Commons_Utilities::logs ( "Callback parameters: ".$inttrxid.": ".$status, "ideal.log" );
			// Bij alle andere statussen producten niet leveren
		// Voeg hier zelf programmacode toe om de status bij te werken
				die( $status );
			
			}
		}
	}
}
