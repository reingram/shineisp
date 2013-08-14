<?php

/**
 * OrdersItemsDomains
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class OrdersItemsDomains extends BaseOrdersItemsDomains {
	
    /**
     * getList
     * Get all the records for the select objects
     * @param $domain_name, $tld, $fields="*", $retarray=false
     * @return Doctrine Record
     */
    public static function getList($id = null) {
        $items = array ();
        
        if ($id) {
            $order_items = Doctrine::getTable ( 'OrdersItemsDomains' )->findby ( 'orderitem_id', $id )->toArray ();
        } else {
            $order_items = Doctrine::getTable ( 'OrdersItemsDomains' )->findAll ()->toArray();
        }
        
        foreach ( $order_items as $item ) {
            $domain = Domains::find($item ['domain_id']);
            $items [$item['domain_id']] = $domain[0] ['domain'] . "." . $domain [0]['DomainsTlds']['WhoisServers']['tld'];
        }
        return $items;
    }
	
 /**
     * findOrdersByDomainID
     * Get all records by the domain ID
     * @param $id
     * @return Doctrine Record or Array
     */
    public static function findOrdersByDomainID($id, $fields = "*", $retarray = false, $locale=1) {
        try {
            $items = Doctrine_Query::create ()
            			->select ( $fields )
            			->from ( 'OrdersItemsDomains oid' )
			            ->leftJoin ( 'oid.OrdersItems oi' )
			            ->leftJoin ( 'oid.Orders o' )
			            ->leftJoin ( 'o.Statuses s' )
			            ->leftJoin ( 'oi.Products p' )
			            ->leftJoin ( "p.ProductsData pd WITH pd.language_id = $locale" )
			            ->where ( "domain_id = ?", $id )
			            ->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
        
            return $items;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
	
 /**
     * findByDomainID
     * Get all records by the domain ID
     * @param $id
     * @return Doctrine Record or Array
     */
    public static function find_by_domain_id($id, $fields = "*", $retarray = false, $locale=1) {
        try {
            $items = Doctrine_Query::create ()->select ( $fields )->from ( 'OrdersItemsDomains oid' )
			            ->leftJoin ( 'oid.OrdersItems oi' )
			            ->leftJoin ( 'oid.Orders o' )
			            ->leftJoin ( 'oi.Products p' )
			            ->leftJoin ( "p.ProductsData pd WITH pd.language_id = $locale" )
			            ->where ( "domain_id = ?", $id )
			            ->andWhere ( "p.type <> ?", 'domain')
			            ->orderBy('o.order_date')
			            ->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
        
            return $items;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
	
 /**
     * findServicesByDomainID
     * Get all the services by the domain ID
     * @param $id
     * @return Doctrine Record or Array
     */
    public static function findServicesByDomainID($id, $fields = "*", $retarray = false) {
        try {
            $dq = Doctrine_Query::create ()->select ( $fields )->from ( 'OrdersItemsDomains oid' )
            ->leftJoin ( 'oid.OrdersItems oi' )
            ->leftJoin ( 'oid.Orders o' )
            ->leftJoin ( 'oi.Products p' )
            ->where ( "domain_id = ?", $id )
            ->andWhere ( "p.type <> ?", 'domain' );
        
            $retarray = $retarray ? Doctrine_Core::HYDRATE_ARRAY : null;
            $items = $dq->execute ( array (), $retarray );
            return $items;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    
 /**
     * findIDsByOrderItemID
     * Get the join ids from the detail ID
     * @param $detail_id
     * @return Doctrine Record or Array
     */
    public static function findIDsByOrderItemID($orderitem_id, $fields = "*", $retarray = false) {
        try {
            $dq = Doctrine_Query::create ()->select ( $fields )->from ( 'OrdersItemsDomains oid' )
            ->leftJoin ( 'oid.OrdersItems oi' )
            ->leftJoin ( 'oi.Products p' )
            ->where ( "orderitem_id = ?", $orderitem_id );
        
            $retarray = $retarray ? Doctrine_Core::HYDRATE_ARRAY : null;
            $items = $dq->execute ( array (), $retarray );
            
            return $items;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    
 	/**
     * Get the domains attached to a service
     * 
     * 
     * @param $detail_id
     * @return ArrayObject
     */
    public static function get_domains($id) {
        try {
            $items = Doctrine_Query::create ()->select("domain_id, CONCAT(d.domain, '.', w.tld) as domain")
									            ->from ( 'OrdersItemsDomains oid' )
									            ->leftJoin ( 'oid.Domains d' )
									            ->leftJoin ( 'd.DomainsTlds tld' )
									            ->leftJoin ( 'tld.WhoisServers w' )
									            ->where ( "orderitem_id = ?", $id )
									            ->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
            
            return $items;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    
 	/**
     * Check the domains attached to a service
     * 
     * 
     * @param $detail_id
     * @return ArrayObject
     */
    public static function check_domains_in_order($orderID, $domainID) {
        try {
            $items = Doctrine_Query::create ()->from ( 'OrdersItemsDomains oid' )
            ->leftJoin ( 'oid.Domains d' )
            ->where ( "domain_id = ?", $domainID )
            ->addWhere ( "order_id = ?", $orderID )
            ->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
            return count($items) ? true : false;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    
    /**
     * Attach a hosting service to a domain name
     * 
     * 
     * @param integer $orderID
     * @param integer $domainID
     */
    public static function addDomain($orderID, $domainID){
    	
    	if(is_numeric($orderID) && is_numeric($domainID)){
	    	$order = Orders::find($orderID);
	    	if(!empty($order)){
		    	foreach ( $order->OrdersItems as $detail ) {
// 	    			if ($detail->Products['type'] == "hosting") {
	    				#if(FALSE === self::check_domains_in_order($orderID, $domainID)){
			    			$domain = new OrdersItemsDomains();
							$domain ['domain_id']    = $domainID;
							$domain ['order_id']     = $orderID;
							$domain ['orderitem_id'] = $detail['detail_id'];
							$domain->save ();
	    				#}
// 	    			}
				}
				
				return true;
	    	}
    	}
    	return false;
    }

    /**
     * Remove all domain names from an hosting service
     * 
     * @param integer $orderID
     */
    public static function removeAllDomains($orderID){
    	if( is_numeric($orderID) ) {
			return Doctrine_Query::create ()->delete ()->from ( 'OrdersItemsDomains oid' )
				->where ( 'orderitem_id = ?', $orderID )
				->execute ();
    	}
    	return false;
    }


}