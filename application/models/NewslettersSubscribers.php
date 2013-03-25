<?php

/**
 * NewslettersSubscribers
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class NewslettersSubscribers extends BaseNewslettersSubscribers
{

	/**
	 * grid
	 * create the configuration of the grid
	 */	
	public static function grid($rowNum = 10) {
		
		$translator = Zend_Registry::getInstance ()->Zend_Translate;
		
		$config ['datagrid'] ['columns'] [] = array ('label' => null, 'field' => 's.subscriber_id', 'alias' => 'subscriber_id', 'type' => 'selectall' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'ID' ), 'field' => 's.subscriber_id', 'alias' => 'subscriber_id', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Email' ), 'field' => 's.email', 'alias' => 'email', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Subscription Date' ), 'field' => 's.subscriptiondate', 'alias' => 'subscriptiondate', 'sortable' => true, 'searchable' => true, 'type' => 'date' );
		
		$config ['datagrid'] ['fields'] = "s.subscriber_id, s.email as email, DATE_FORMAT(s.subscriptiondate, '%d/%m/%Y %H:%i:%s') as subscriptiondate";
		$config ['datagrid'] ['rownum'] = $rowNum;
		
		$config ['datagrid'] ['dqrecordset'] = Doctrine_Query::create ()->select ( $config ['datagrid'] ['fields'] )->from ( 'NewslettersSubscribers s' );
		
		$config ['datagrid'] ['basepath'] = "/admin/subscribers/";
		$config ['datagrid'] ['index'] = "subscriber_id";
		$config ['datagrid'] ['rowlist'] = array ('10', '50', '100', '1000' );
		
		$config ['datagrid'] ['buttons'] ['edit'] ['label'] = $translator->translate ( 'Edit' );
		$config ['datagrid'] ['buttons'] ['edit'] ['cssicon'] = "edit";
		$config ['datagrid'] ['buttons'] ['edit'] ['action'] = "/admin/subscribers/edit/id/%d";
		
		$config ['datagrid'] ['buttons'] ['delete'] ['label'] = $translator->translate ( 'Delete' );
		$config ['datagrid'] ['buttons'] ['delete'] ['cssicon'] = "delete";
		$config ['datagrid'] ['buttons'] ['delete'] ['action'] = "/admin/subscribers/delete/id/%d";
		$config ['datagrid'] ['massactions'] = array ('massdelete'=>'Mass Delete', 'bulkexport'=>'Export');
		
		$bulkmailinglist = array();
		$mailchimplists = Newsletters::get_mailchimp_list();
		if(!empty($mailchimplists)){
			foreach($mailchimplists as $id=>$name){
				$bulkmailinglist['bulk_mailchimp_optin&list=' . $id ] = "Add to $name";
				$bulkmailinglist['bulk_mailchimp_optout&list=' . $id ] = "Remove from $name";
			}
		}
		
		$config ['datagrid'] ['massactions'] = array_merge($config ['datagrid'] ['massactions'], $bulkmailinglist);
		
		return $config;
	}	
	
	/**
     * find
     * Get a record by ID
     * @param $id
     * @return Doctrine Record
     */
    public static function find($id) {
        return Doctrine::getTable ( 'NewslettersSubscribers' )->findOneBy ( 'subscriber_id', $id );
    }
	
	/**
     * Get the subscriber's email by ID
     * @param $id
     * @return string email
     */
    public static function getSubscriberEmail($id) {
        $record = Doctrine::getTable ( 'NewslettersSubscribers' )->findOneBy ( 'subscriber_id', $id )->getData();
        return !empty($record['email']) ? $record['email'] : null;
    }
    
    /**
     * findbyvar
     * Get a record by ID
     * @param $var
     * @return Doctrine Record
     */
    public static function findbyvar($var) {
        return Doctrine::getTable ( 'NewslettersSubscribers' )->findBy ( 'var', $var, Doctrine::HYDRATE_ARRAY);
    }
    
    /**
     * delete
     * Delete a record by ID
     * @param $id
     */
    public static function deleteItem($id) {
        Doctrine::getTable ( 'NewslettersSubscribers' )->findOneBy ( 'subscriber_id', $id )->delete();
    }
    
    /**
     * getbyId
     * Get a record by ID
     * @param $id
     */
    public static function getbyId($id) {
        return Doctrine::getTable ( 'NewslettersSubscribers' )->find ( $id );
    }
    
    /**
     * Add the customer in the newsletter
     * @param integer $customer_id
     */
    public static function customer_optIn($customer_id) {
    	$customer = Customers::getAllInfo($customer_id, "email");

    	// Check if the email is already registered
    	$retval = Doctrine::getTable ( 'NewslettersSubscribers' )->findOneBy ( 'email', $customer['email'] );
    	if(empty($retval)){
    		// Save the new email address
	    	$subscriber = new NewslettersSubscribers();
	    	$subscriber['email'] = $customer['email'];
	    	$subscriber['customer_id'] = $customer_id;
	    	$subscriber['subscriptiondate'] = date('Y-m-d H:i:s');
	    	if($subscriber->trySave()){
	    		return true;
	    	}
    	}
    	return false;
    }
    
    /**
     * Add the customer in the newsletter
     * @param integer $customer_id
     */
    public static function customer_optOut($customer_id) {
    	$customer = Customers::getAllInfo($customer_id, "email");
	    $subscriber = Doctrine::getTable ( 'NewslettersSubscribers' )->findOneBy ( 'email', $customer['email'] );
	    if($subscriber){
	    	$subscriber->delete();
	    	return true;
	    }else{
	    	return false;
	    }
	    
    }
    
    /*
     * optIn
     * add a new subscriber in the database
     */
    public static function optIn($email) {
    	if(Shineisp_Commons_Utilities::isEmail($email)){
    		
    		// Check if the email is already registered
    		$retval = Doctrine::getTable ( 'NewslettersSubscribers' )->findOneBy ( 'email', $email );
    		if(empty($retval)){
	    		// Save the new email address
		    	$subscriber = new NewslettersSubscribers();
		    	$subscriber->email = $email;
		    	$subscriber->subscriptiondate = date('Y-m-d H:i:s');
		    	if($subscriber->trySave()){
		    		
		    		// Send the email to confirm the subscription
			    	$retval = Shineisp_Commons_Utilities::getEmailTemplate ( 'new_subscriber' );
					if ($retval) {
						$subject = $retval ['subject'];
						$template = $retval ['template'];
						$isp = Isp::getActiveISP ();
						$template = str_replace ( "[signature]", $isp ['company'], $template );
						Shineisp_Commons_Utilities::SendEmail ( $isp ['email'], $email, null, $subject, $template );
					}
		    		return true;	
		    	}
    		}
    	}
    	
    	return false;
    }
    
    /*
     * optOut
     * delete the email from the database and set the customer newsletter preference
     */
	public static function optOut($md5email) {
    	if(!empty($md5email)){
    		
    		// Check if the email owner is one of the registered customers
    		$customer = Customers::getCustomerbyEmailMd5($md5email);
    		
    		if(!empty($customer[0]['customer_id'])){
    			Customers::newsletter_subscription($customer[0]['customer_id'], false);
    		}else{
				// Remove the email address from the email subscribers
    			Doctrine_Query::create ()->delete( 'NewslettersSubscribers' )->where ( 'MD5(email)', $md5email )->delete();
    		}
    		
    		return true;
    		
    	}else{
    		return false;
    	}
    }
    
	public static function get_active_subscribers() {
    	return Doctrine_Query::create ()->from ( 'NewslettersSubscribers s' )
    									->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    }
    
    /**
     * getAllInfo
     * Get all data starting from the wikiID 
     * @param $id
     * @return Doctrine Record / Array
     */
    public static function getAllInfo($id, $fields = "*", $retarray = false) {
        $dq = Doctrine_Query::create ()->select ( $fields )->from ( 'NewslettersSubscribers s' )->where ( "subscriber_id = $id" )->limit ( 1 );
        
        $retarray = $retarray ? Doctrine_Core::HYDRATE_ARRAY : null;
        $items = $dq->execute ( array (), $retarray );
        
        return $items;
    }
    
	
	/**
	 * massdelete
	 * delete the newsletter selected 
	 * @param array
	 * @return Boolean
	 */
	public static function massdelete($items) {
		$retval = Doctrine_Query::create ()->delete ()->from ( 'NewslettersSubscribers s' )->whereIn ( 's.subscriber_id', $items )->execute ();
		return $retval;
	}    
    

	######################################### BULK ACTIONS ############################################
	
	
	/**
	 * massdelete
	 * delete the customer selected 
	 * @param array
	 * @return Boolean
	 */
	public static function bulk_delete($items) {
		if(!empty($items)){
			return self::massdelete($items);
		}
		return false;
	}

	/**
	 * OptIn MailChimp
	 * @param array
	 * @return Boolean
	 */
	public static function bulk_mailchimp_optin($items, $parameters) {
		$errors = array();
		
		if(!empty($items)){
			$key = Settings::findbyParam ( "MailChimp_key", "admin", Isp::getActiveISPID () );
			$confirm = Settings::findbyParam ( "MailChimp_confirm", "admin", Isp::getActiveISPID () );
			
			if(empty($key)){
				die('MailChimp Api Key has been not set yet. Go to Configuration > MailChimp');
			}
			
			$list_id = $parameters['list'];
			
			$api = new Shineisp_Api_Newsletters_Mailchimp_Main($key);

			foreach ($items as $id){
				$email = self::getSubscriberEmail($id);
	
	    		$result = $api->listSubscribe($list_id, $email, NULL, true, $confirm);
	    		if(!empty($api->errorCode)){
	    			$errors[] = $api->errorMessage;
	    		}
			}
			
			if(!empty($errors)){
				die(json_encode(array('mex' => implode("<br/>", $errors))));
			}
			
			return true;
		}
		return false;
	}	

	/**
	 * OptOut MailChimp
	 * @param array
	 * @return Boolean
	 */
	public static function bulk_mailchimp_optout($items, $parameters) {
		$errors = array();
		
		if(!empty($items)){
			$key = Settings::findbyParam ( "MailChimp_key", "admin", Isp::getActiveISPID () );
			$confirm = Settings::findbyParam ( "MailChimp_confirm", "admin", Isp::getActiveISPID () );
			
			if(empty($key)){
				die('MailChimp Api Key has been not set yet. Go to Configuration > MailChimp');
			}
			
			$list_id = $parameters['list'];
			$api = new Shineisp_Api_Newsletters_Mailchimp_Main($key);
			
			foreach ($items as $id){
				$email = self::getSubscriberEmail($id);
	
	    		$result = $api->listUnsubscribe($list_id, $email, true, $confirm);
	    		if(!empty($api->errorCode)){
	    			$errors[] = $api->errorMessage;
	    		}
			}
			
			if(!empty($errors)){
				die(json_encode(array('mex' => implode("<br/>", $errors))));
			}
			
			return true;
		}
		return false;
	}	
}