<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version133 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('domains_nichandles', 'domains_nichandles_profile_id_domains_profiles_profile_id', array(
             'name' => 'domains_nichandles_profile_id_domains_profiles_profile_id',
             'local' => 'profile_id',
             'foreign' => 'profile_id',
             'foreignTable' => 'domains_profiles',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('domains_nichandles', 'domains_nichandles_domain_id_domains_domain_id', array(
             'name' => 'domains_nichandles_domain_id_domains_domain_id',
             'local' => 'domain_id',
             'foreign' => 'domain_id',
             'foreignTable' => 'domains',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('domains_profiles', 'domains_profiles_type_id_company_types_type_id', array(
             'name' => 'domains_profiles_type_id_company_types_type_id',
             'local' => 'type_id',
             'foreign' => 'type_id',
             'foreignTable' => 'company_types',
             'onUpdate' => '',
             'onDelete' => 'Set Null',
             ));
        $this->createForeignKey('domains_profiles', 'domains_profiles_legalform_id_legalform_legalform_id', array(
             'name' => 'domains_profiles_legalform_id_legalform_legalform_id',
             'local' => 'legalform_id',
             'foreign' => 'legalform_id',
             'foreignTable' => 'legalform',
             'onUpdate' => '',
             'onDelete' => 'Set Null',
             ));
        $this->createForeignKey('domains_profiles', 'domains_profiles_status_id_statuses_status_id', array(
             'name' => 'domains_profiles_status_id_statuses_status_id',
             'local' => 'status_id',
             'foreign' => 'status_id',
             'foreignTable' => 'statuses',
             ));
        $this->createForeignKey('domains_profiles', 'domains_profiles_customer_id_customers_customer_id', array(
             'name' => 'domains_profiles_customer_id_customers_customer_id',
             'local' => 'customer_id',
             'foreign' => 'customer_id',
             'foreignTable' => 'customers',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->addIndex('domains_nichandles', 'domains_nichandles_profile_id', array(
             'fields' => 
             array(
              0 => 'profile_id',
             ),
             ));
        $this->addIndex('domains_nichandles', 'domains_nichandles_domain_id', array(
             'fields' => 
             array(
              0 => 'domain_id',
             ),
             ));
        $this->addIndex('domains_profiles', 'domains_profiles_type_id', array(
             'fields' => 
             array(
              0 => 'type_id',
             ),
             ));
        $this->addIndex('domains_profiles', 'domains_profiles_legalform_id', array(
             'fields' => 
             array(
              0 => 'legalform_id',
             ),
             ));
        $this->addIndex('domains_profiles', 'domains_profiles_status_id', array(
             'fields' => 
             array(
              0 => 'status_id',
             ),
             ));
        $this->addIndex('domains_profiles', 'domains_profiles_customer_id', array(
             'fields' => 
             array(
              0 => 'customer_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('domains_nichandles', 'domains_nichandles_profile_id_domains_profiles_profile_id');
        $this->dropForeignKey('domains_nichandles', 'domains_nichandles_domain_id_domains_domain_id');
        $this->dropForeignKey('domains_profiles', 'domains_profiles_type_id_company_types_type_id');
        $this->dropForeignKey('domains_profiles', 'domains_profiles_legalform_id_legalform_legalform_id');
        $this->dropForeignKey('domains_profiles', 'domains_profiles_status_id_statuses_status_id');
        $this->dropForeignKey('domains_profiles', 'domains_profiles_customer_id_customers_customer_id');
        $this->removeIndex('domains_nichandles', 'domains_nichandles_profile_id', array(
             'fields' => 
             array(
              0 => 'profile_id',
             ),
             ));
        $this->removeIndex('domains_nichandles', 'domains_nichandles_domain_id', array(
             'fields' => 
             array(
              0 => 'domain_id',
             ),
             ));
        $this->removeIndex('domains_profiles', 'domains_profiles_type_id', array(
             'fields' => 
             array(
              0 => 'type_id',
             ),
             ));
        $this->removeIndex('domains_profiles', 'domains_profiles_legalform_id', array(
             'fields' => 
             array(
              0 => 'legalform_id',
             ),
             ));
        $this->removeIndex('domains_profiles', 'domains_profiles_status_id', array(
             'fields' => 
             array(
              0 => 'status_id',
             ),
             ));
        $this->removeIndex('domains_profiles', 'domains_profiles_customer_id', array(
             'fields' => 
             array(
              0 => 'customer_id',
             ),
             ));
    }
}