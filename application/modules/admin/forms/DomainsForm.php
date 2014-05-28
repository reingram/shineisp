<?php
class Admin_Form_DomainsForm extends Zend_Form
{
    
    public function init()
    {
        // Set the custom decorator
        $this->addElementPrefixPath('Shineisp_Decorator', 'Shineisp/Decorator/', 'decorator');
        $translate = Shineisp_Registry::get('Zend_Translate');
        
        $this->addElement('text', 'domain', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'decorators' => array('Bootstrap'),
            'label'      => $translate->_('Domain'),
            'description' => $translate->_('Write down the name of the domain without any extension, white spaces, or symbols.'),
            'class'      => 'form-control updatechkdomain'
        ));
      
        $this->addElement('select', 'tld_id', array(
                'label' => $translate->_('TLD'),
                'description' => $translate->_('Select the TLD from the list'),
                'decorators' => array('Bootstrap'),
                'class'      => 'form-control updatechkdomain'
        ));
        $this->getElement('tld_id')
                  ->setAllowEmpty(false)
                  ->setMultiOptions(DomainsTlds::getList())
                  ->setRequired(true);
                  
        $this->addElement('select', 'registrars_id', array(
                'label' => $translate->_('Registrar'),
                'decorators' => array('Bootstrap'),
                'class'      => 'form-control'
        ));
        $this->getElement('registrars_id')
                ->setAllowEmpty(true)
                ->setMultiOptions(Registrars::getList());
                  
        // Domain Ownership
        $this->addElement('select', 'owner', array(
                'label' => $translate->_('Owner'),
                'description' => $translate->_("If the domain owner's profile is not set, the domain customer information will be used."),
                'decorators' => array('Bootstrap'),
                'class'      => 'form-control'
        ));
        
        $this->getElement('owner')
                ->setAllowEmpty(true)
                ->setMultiOptions(DomainsProfiles::getList(true));
                  
        $this->addElement('select', 'admin', array(
                'label' => $translate->_('Admin-C'),
                'decorators' => array('Bootstrap'),
                'class'      => 'form-control'
        ));
        $this->getElement('admin')
                ->setAllowEmpty(true)
                ->setMultiOptions(DomainsProfiles::getList(true));
                  
        $this->addElement('select', 'tech', array(
                'label' => $translate->_('Tech'),
                'decorators' => array('Bootstrap'),
                'class'      => 'form-control'
        ));
        $this->getElement('tech')
                ->setAllowEmpty(true)
                ->setMultiOptions(DomainsProfiles::getList(true));
                  
        $this->addElement('select', 'billing', array(
                'label' => $translate->_('Billing'),
                'decorators' => array('Bootstrap'),
                'class'      => 'form-control'
        ));
        $this->getElement('billing')
                ->setAllowEmpty(true)
                ->setMultiOptions(DomainsProfiles::getList(true));
                  
        $this->addElement('text', 'creation_date', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Creation date'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control date',
            'dateformat'      => Settings::getJsDateFormat()
        ));
        
        $this->addElement('text', 'expiring_date', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Expiry Date'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control date',
            'dateformat'      => Settings::getJsDateFormat()
        ));
        
        $this->addElement('text', 'authinfocode', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('AUTHINFO CODE'),
            'description'      => $translate->_('Write down the Authinfo code in order to transfer the domain to this ISP'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
      
        $this->addElement('checkbox', 'autorenew', array(
            'filters'     => array('StringTrim'),
            'decorators'  => array('Bootstrap'),
            'label'       => $translate->_('Auto renewal'),
            'class'       => 'form-control'
        ));
        
        $this->addElement('select', 'customer_id', array(
                            'label' => $translate->_('Customer'),
                            'decorators' => array('Bootstrap'),
                            'class'      => 'form-control'
        ));
        
        $this->getElement('customer_id')
                  ->setAllowEmpty(false)
                  ->setMultiOptions(Customers::getList())
                  ->setRequired(true); 
//                  
        $note = $this->addElement('textarea', 'note', array(
            'filters'    => array('StringTrim'),
            'decorators' => array('Bootstrap'),
            'label'      => $translate->_('Private Notes'),
            'class'      => 'col-lg-12 form-control'
        ));
        
        $note = $this->addElement('textarea', 'message', array(
            'filters'    => array('StringTrim'),
            'required'   => false,
            'decorators' => array('Bootstrap'),
            'label'      => $translate->_('Message'),
            'class'      => 'col-lg-12 form-control wysiwyg'
        ));        

        $status = $this->addElement('select', 'status_id', array(
        'label' => 'Status',
        'required'    => true,
        'decorators' => array('Bootstrap'),
        'class'      => 'form-control'
        ));
        
        // DNS SECTION
        // ==============
         $this->addElement('text', 'subdomain', array(
            'filters'    => array('StringTrim'),
            'decorators' => array('Bootstrap'),
            'label'      => $translate->_('Subdomain'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('text', 'target', array(
            'filters'    => array('StringTrim'),
            'decorators' => array('Bootstrap'),
            'label'      => $translate->_('Target'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('select', 'zone', array(
                'label' => $translate->_('Zone'),
                'decorators' => array('Bootstrap'),
                'class'      => 'form-control'
        ));
        
        $this->getElement('zone')
                  ->setAllowEmpty(false)
                  ->setMultiOptions(Dns_Zones_Types::getZones());
        
        $status = $this->getElement('status_id')
                  ->setAllowEmpty(false)
                  ->setMultiOptions(Statuses::getList('domains'));
                  
        $save = $this->addElement('submit', 'save', array(
            'required' => false,
            'label'    => $translate->_('Save'),
            'decorators' => array('Bootstrap'),
            'class'    => 'btn'
        ));
        
        $id = $this->addElement('hidden', 'domain_id');

    }
    
}