<?php

/**
 * Regions
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ShineISP
 * 
 * @author     Shine Software <info@shineisp.com>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
class Regions extends BaseRegions {
	
	/**
	 * 
	 * Get all the regions of country
	 */
	public static function findAll( $countryid ) {
		
    	return Doctrine_Query::create ()->from ( 'Regions s' )
                                        ->where('s.country_id = ?', array($countryid))
           								->orderBy('s.name')
           								->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );		
	}
    
    public static function fildAllFromCountryCode ( $countrycode ) {
        return Doctrine_Query::create ()
                    ->from ( 'Regions s' )
                    ->leftJoin ( 's.Countries c' )
                    ->where('c.code = ?', array($countrycode))
                    ->orderBy('s.name')
                    ->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );       
        
    }
	
	/**
	 * Find a state by id
	 * 
	 * 
	 * @param unknown_type $id
	 */
	public static function find($id) {
		return Doctrine::getTable ( 'Regions' )->findOneBy ( 'region_id', $id );
	}

	/**
	 * Find by name
	 * 
	 * 
	 * @param unknown_type $name
	 */
	public static function findbyName($name) {
		return Doctrine::getTable ( 'Regions' )->findOneBy ( 'name', $name );
	}
    

}