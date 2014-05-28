<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('InvoicesPurchasesCategories', 'doctrine');

/**
 * BaseInvoicesPurchasesCategories
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $category_id
 * @property string $category
 * @property Doctrine_Collection $InvoicesPurchases
 * 
 * @package    ShineISP
 * 
 * @author     Shine Software <info@shineisp.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseInvoicesPurchasesCategories extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('invoices_purchases_categories');
        $this->hasColumn('category_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('category', 'string', 150, array(
             'type' => 'string',
             'length' => 150,
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
        $this->hasMany('InvoicesPurchases', array(
             'local' => 'category_id',
             'foreign' => 'category_id'));
    }
}