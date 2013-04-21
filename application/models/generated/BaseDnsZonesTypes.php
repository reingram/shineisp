<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('DnsZonesTypes', 'doctrine');

/**
 * BaseDnsZonesTypes
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $type_id
 * @property string $zone
 * @property Doctrine_Collection $DnsZones
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseDnsZonesTypes extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('dns_zones_types');
        $this->hasColumn('type_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('zone', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('DnsZones', array(
             'local' => 'type_id',
             'foreign' => 'type_id'));
    }
}