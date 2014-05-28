<?php
class Admin_Form_DomainstldsForm extends Zend_Form
{   
    public function init()
    {
        // Set the custom decorator
    	$this->addElementPrefixPath('Shineisp_Decorator', 'Shineisp/Decorator/', 'decorator');
    	$translate = Shineisp_Registry::get('Zend_Translate');
    	
        $this->addElement('text', 'name', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('TLD Name'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
    	
        $this->addElement('textarea', 'description', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Description'),
            'decorators' => array('Bootstrap'),
            'class'      => 'col-lg-12 form-control wysiwyg',
        ));
    	
        $this->addElement('text', 'tags', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Tags/Type'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('select', 'ishighlighted', array(
            'label'      => $translate->_('Is Highlighted'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control',
            'multioptions' => array('0' => 'No', '1'=>'Yes')
        ));

        $this->addElement('select', 'isrefundable', array(
            'label'      => $translate->_('Is Refundable'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control',
            'multioptions' => array('0' => 'No', '1'=>'Yes')
        ));
        
        $this->addElement('text', 'resultcontrol', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Result String Control'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));

        $this->addElement('text', 'registration_price', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Registration Price'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));        

        $this->addElement('text', 'renewal_price', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Renewal Price'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));        

        $this->addElement('text', 'transfer_price', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Transfer Price'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));        

        $this->addElement('select', 'server_id', array(
            'decorators'  => array('Bootstrap'),
            'label'       => $translate->_('TLD Server'),
            'class'       => 'form-control'
        ));
                
        $this->getElement('server_id')
                  ->setAllowEmpty(false)
                  ->setRegisterInArrayValidator(false)
                  ->setMultiOptions(WhoisServers::getList()); 
                  
       $this->addElement('select', 'tax_id', array(
        'label' => $translate->_('Tax'),
        'decorators' => array('Bootstrap'),
        'class'      => 'form-control'
        ));
        
        $this->getElement('tax_id')
                  ->setAllowEmpty(false)
                  ->setRegisterInArrayValidator(false)
                  ->setMultiOptions(Taxes::getList())
        		  ->setRequired(true);                        
        
        $this->addElement('text', 'registration_cost', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Registration Cost'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));        

        $this->addElement('text', 'renewal_cost', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Renewal Cost'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));        

        $this->addElement('text', 'transfer_cost', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Transfer Cost'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));        
        
        $this->addElement('select', 'registrars_id', array(
                'label' => $translate->_('Registrars'),
                'decorators' => array('Bootstrap'),
                'class'      => 'form-control updatechkdomain'
        ));
        
        $this->getElement('registrars_id')
                  ->setAllowEmpty(false)
                  ->setRegisterInArrayValidator(false)
                  ->setMultiOptions(Registrars::getList(false))
                  ->setRequired(true);        
        
        $this->addElement('submit', 'save', array(
            'required' => false,
            'label'    => $translate->_('Save'),
            'decorators' => array('Bootstrap'),
            'class'    => 'btn'
        ));
        
        $this->addElement('hidden', 'tld_id');
    }
}