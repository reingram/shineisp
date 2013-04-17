<?php

/**
 * Dns_Zones_Types
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
class Dns_Zones_Types extends BaseDns_Zones_Types {
	
	/**
	 * getType
	 * Get the dns zones type 
	 * @return integer
	 */
	public static function getType($zonetype) {
		$dq = Doctrine_Query::create ()->from ( 'Dns_Zones_Types zt' )->where ( 'zt.zone = ?', $zonetype );
		$zone = $dq->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
		if (count ( $zone ) > 0) {
		  return $zone[0]['type_id'];
		}
		
		return null;
	}
	
	/**
	 * getZones
	 * Get all dns zones 
	 * @return ARRAY Record
	 */
	public static function getZones() {
		$zones = array ();
		$zones[] = "";
		
		$dq = Doctrine_Query::create ()->from ( 'Dns_Zones_Types zt' )
			->where ( "zt.zone != ?", "NS"); // TODO: Delete this criteria in order to show the NS records
		
		$items = $dq->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
		foreach ( $items as $c ) {
			$zones [$c ['type_id']] = $c ['zone'];
		}
		return $zones;
	}
}