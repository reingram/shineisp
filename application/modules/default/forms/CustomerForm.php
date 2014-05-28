<?php
class Default_Form_CustomerForm extends Zend_Form
{   
	
    public function init()
    {
        // Set the custom decorator
    	$this->addElementPrefixPath('Shineisp_Decorator', 'Shineisp/Decorator/', 'decorator');
    	$translate = Shineisp_Registry::get('Zend_Translate');
    	
    	$this->addElement('text', 'firstname', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('First name'),
            'description' => $translate->_('Write here your firstname.'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('text', 'lastname', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Last name'),
            'description'      => $translate->_('Write here your lastname.'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('text', 'birthdate', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'validators' => array(new Zend_Validate_Date('dd/mm/yyyy','it')),
            'label'      => $translate->_('Birth date'),
            'description'  => $translate->_('Write here your birth date (eg. dd/mm/yyyy)'),
            'decorators' => array('Bootstrap'),
            'class'        => 'form-control medium-input'
        ));
        
        $this->addElement('text', 'birthplace', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Birth place'),
            'required'   => true,
            'description'      => $translate->_('Write here the birth place.'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('select', 'company_type_id', array(
        'label' => $translate->_('Company Type'),
        'decorators' => array('Bootstrap'),
        'description'      => $translate->_('Select the company type'),
        'class'      => 'form-control'
        ));
        
        $this->getElement('company_type_id')
                  ->setAllowEmpty(false)
                  ->setMultiOptions(CompanyTypes::getList(true));        
        
        $this->addElement('select', 'legalform', array(
        'label' => $translate->_('Legal form'),
        'required'   => true,
        'decorators' => array('Bootstrap'),
        'description'      => $translate->_('Select the type of company.'),
        'class'      => 'form-control'
        ));
        
        $this->getElement('legalform')
                  ->setAllowEmpty(false)
                  ->setMultiOptions(Legalforms::getList(true))
                  ->addValidator( new Shineisp_Validate_Customer( ) );
        
        $this->addElement('text', 'company', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Company Name'),
            'decorators' => array('Bootstrap'),
            'description'      => $translate->_('Write here your company name.'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('text', 'birthplace', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Birth place'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('text', 'birthdistrict', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Birth District'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control medium-input',
            'maxlength' => 2
        ));
        
        $this->addElement('text', 'birthcountry', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Country of Birth'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('text', 'birthnationality', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Birth Nationality'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $vatValidator = new Shineisp_Validate_Vat();
        $this->addElement('text', 'vat', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('VAT Number'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control',
            'description'      => $translate->_('Write here the VAT code. Eg: IT123456789')
        ));
        $this->getElement('vat')->addValidator($vatValidator);
        
        $this->addElement('text', 'area', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Area'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control medium-input',
            'description'      => $translate->_('Write the area code')
        ));        
        
        $fiscalcodeValidator = new Shineisp_Validate_Fiscalcode();
        $this->addElement('text', 'taxpayernumber', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Tax payer number'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control',
            'description'      => $translate->_('Write the tax payer number.')
        ));
        $this->getElement('taxpayernumber')->addValidator($fiscalcodeValidator);
        
        $this->addElement('text', 'address', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Address'),
            'decorators' => array('Bootstrap'),
            'description'      => $translate->_('Write the address'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('text', 'code', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Zip'),
            'description'      => $translate->_('Write the zip code'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control medium-input'
        ));
        
        $this->addElement('text', 'city', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('City'),
            'description'      => $translate->_('Write here your city name'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('select', 'country_id', array(
		        'label' => $translate->_('Country'),
		        'required'   => true,
		        'description'      => $translate->_('Select your own country'),
		        'decorators' => array('Bootstrap'),
        		'class' 	 => 'form-control')
        );
        $this->getElement('country_id')
                  ->setAllowEmpty(false)
                  ->setMultiOptions(Countries::getList())
                  ->setRequired(true);
                  
        $this->addElement('select', 'gender', array(
        'label' => $translate->_('Gender'),
        'required'   => true,
        'decorators' => array('Bootstrap'),
        'class'      => 'form-control medium-input'));
        
        $this->getElement('gender')
                  ->setAllowEmpty(false)
                  ->setMultiOptions(array('M'=>'M', 'F'=>'F'))
                  ->setRequired(true);                  
                  
        $this->addElement('text', 'contact', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Telephone'),
            'required'   => true,
            'decorators' => array('Bootstrap'),
            'description'      => $translate->_('Write here the contact (eg. +39.98368276)'),
            'class'      => 'form-control medium-input'
        ));
                  
        $this->addElement('hidden', 'contacttypes', array('decorators' => array('Bootstrap')));
        
        
        $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'required'   => true,
            'decorators' => array('Bootstrap'),
            'validators' => array(
                'EmailAddress'
            ),
            'required'   => true,
            'label'      => $translate->_('Email'),
            'description'      => $translate->_('Write here your email'),
            'class'      => 'form-control'
        ));
        $mailValidator = new Shineisp_Validate_Email();
        $this->getElement('email')->addValidator($mailValidator);
                
        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'decorators' => array('Bootstrap'),
            'validators' => array(
                array('regex', false, '/^[a-zA-Z0-9\-\_\.\%\!\$]{6,20}$/')
            ),
            'description'      => $translate->_('Write here your password. (min.6 chars - max.20 chars)'),
            'label'      => $translate->_('Password'),
            'class'      => 'form-control'
        ));
        
        
        $this->addElement('submit', 'submit', array(
            'required' => false,
            'label'      => $translate->_('Save'),
            'decorators' => array('Bootstrap'),
            'class'    => 'btn btn-success btn-lg'
        ));
        
        $this->addElement('hidden', 'customer_id');
    }
}