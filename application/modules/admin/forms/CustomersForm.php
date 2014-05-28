<?php
class Admin_Form_CustomersForm extends Zend_Form
{   
    public function init()
    {
        // Set the custom decorator
    	$this->addElementPrefixPath('Shineisp_Decorator', 'Shineisp/Decorator/', 'decorator');
    	$translate = Shineisp_Registry::get('Zend_Translate');
    	
    	$this->addElement('text', 'firstname', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Firstname'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('text', 'lastname', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Lastname'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('select', 'gender', array(
        'label' => $translate->_('Gender'),
        'decorators' => array('Bootstrap'),
        'class'      => 'form-control'
        ));
        
        $this->getElement('gender')
                  ->setAllowEmpty(true)
                  ->setMultiOptions(array('M'=>'Man', 'F'=>'Female'));
        
        $this->addElement('select', 'taxfree', array(
        'label' => $translate->_('Tax free'),
        'description' => $translate->_('If it is set as Yes all the taxes will be not added in the orders'),
        'decorators' => array('Bootstrap'),
        'class'      => 'form-control'
        ));

        $this->getElement('taxfree')
                  ->setAllowEmpty(true)
                  ->setMultiOptions(array('0'=>'No', '1'=>'Yes'));

        $this->addElement('select', 'ignore_latefee', array(
        'label' => $translate->_('Ignore late fee'),
        'description' => $translate->_('If it is set as Yes this customers is not subject to late fee'),
        'decorators' => array('Bootstrap'),
        'class'      => 'form-control'
        ));

        $this->getElement('ignore_latefee')
                  ->setAllowEmpty(true)
                  ->setMultiOptions(array('0'=>'No', '1'=>'Yes'));



        $this->addElement('select', 'language_id', array(
        'label' => $translate->_('Default Language'),
        'description' => $translate->_('All the messages sent to the customer will be send using the default language selected'),
        'decorators' => array('Bootstrap'),
        'class'      => 'form-control'
        ));
        
        $this->getElement('language_id')
                  ->setAllowEmpty(true)
                  ->setMultiOptions(Languages::getList());

        $this->addElement('select', 'issubscriber', array(
        'label' => $translate->_('Newsletter Subscription'),
        'decorators' => array('Bootstrap'),
        'class'      => 'form-control'
        ));
        
        $this->getElement('issubscriber')
                  ->setAllowEmpty(true)
                  ->setMultiOptions(array('0'=>'No', '1'=>'Yes'));
                  
        $this->addElement('select', 'isreseller', array(
        'label' => $translate->_('Is Reseller'),
        'description' => 'Set the user as reseller',
        'decorators' => array('Bootstrap'),
        'class'      => 'form-control'
        ));
        
        $this->getElement('isreseller')
                  ->setAllowEmpty(true)
                  ->setMultiOptions(array('0'=>'No', '1'=>'Yes'));
        
        $this->addElement('text', 'birthdate', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Birth date'),
            'decorators' => array('Bootstrap'),
            'class'        => 'form-control date'
        ));
        
        $this->addElement('text', 'birthplace', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Birth place'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('text', 'birthdistrict', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Birth district'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('text', 'birthcountry', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Birth country'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('text', 'birthnationality', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Birth nationality'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('select', 'group_id', array(
        'label' => $translate->_('Group'),
        'decorators' => array('Bootstrap'),
        'class'      => 'form-control'
        ));
        
        $this->getElement('group_id')
                  ->setAllowEmpty(true)
                  ->setMultiOptions(CustomersGroups::getList());
        
        $this->addElement('select', 'type_id', array(
        'label' => $translate->_('Company Type'),
        'decorators' => array('Bootstrap'),
        'class'      => 'form-control'
        ));
        
        $this->getElement('type_id')
                  ->setAllowEmpty(true)
                  ->setMultiOptions(CompanyTypes::getList(true));
        
        $this->addElement('select', 'legalform_id', array(
        'label' => 'Legal form',
        'decorators' => array('Bootstrap'),
        'class'      => 'form-control'
        ));
        
        $this->getElement('legalform_id')
                  ->setAllowEmpty(true)
                  ->setMultiOptions(Legalforms::getList());
        
        $this->addElement('text', 'company', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Company Name'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('button', 'customerupdate', array(
            'label'    => 'Customer Update',
            'description' => 'Update the customer information retrieving the data from the registrar database.',
            'decorators' => array('Bootstrap'),
            'class'    => 'button red customerupdate'
        ));
        
        $this->addElement('text', 'vat', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('VAT Number'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control',
        ));
        
        $this->addElement('text', 'taxpayernumber', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Taxpayer Number'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control',
        ));
        
         // If the browser client is an Apple client hide the file upload html object  
        if(false == Shineisp_Commons_Utilities::isAppleClient()){
	        $MBlimit = Settings::findbyParam('adminuploadlimit', 'admin', Isp::getActiveISPID());
	        $filetypes = Settings::findbyParam('adminuploadfiletypes', 'Admin');
	        $Byteslimit = Shineisp_Commons_Utilities::MB2Bytes($MBlimit);
	        
			$file = $this->createElement('file', 'attachments', array(
	            'label'      => $translate->_('Attachment'),
				'decorators' => array('File', array('ViewScript', array('viewScript' => 'partials/file.phtml', 'placement' => false))),
	            'description'  => $translate->_('Select the document to upload. Files allowed are (%s) - Max %s',  $filetypes, Shineisp_Commons_Utilities::formatSizeUnits($Byteslimit)),
	            'data-classButton' => 'btn btn-primary',
	            'data-input'       => 'false',
	            'class'            => 'filestyle'
	        ));
			
			if($filetypes){
				$file->addValidator ( 'Extension', false, $filetypes );
			}
			
			if($Byteslimit){
				$file->addValidator ( 'Size', false, $Byteslimit );
			}
			
	        $file->addValidator ( 'Count', false, 1 );
	        
			$this->addElement($file);          
        
	        $this->addElement('select', 'filecategory', array(
	            'label'      => $translate->_('File Category'),
	            'decorators' => array('Bootstrap'),
	            'class'      => 'text-input'
	        ));
	        
	        $this->getElement('filecategory')
	                  ->setAllowEmpty(true)
	                  ->setMultiOptions(FilesCategories::getList());
        }
        
        $this->addElement('text', 'address', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Address'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('text', 'code', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Zip code'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('text', 'area', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Area'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));        
        
        $this->addElement('text', 'city', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('City'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('select', 'country_id', array(
				        'label' => $translate->_('Country'),
				        'decorators' => array('Bootstrap'),
				        'class'      => 'form-control',
                        'onchange'   => 'onChangeCountry( this );')
        );
        
        
        $this->getElement('country_id')
                  ->setAllowEmpty(false)
                  ->setMultiOptions(Countries::getList( true ));

        $this->addElement('select', 'region_id', array(
                        'label' => $translate->_('Region'),
                        'decorators' => array('Bootstrap'),
                        'class'      => 'form-control',
                        'onchange'   => 'onChangeRegions( this );')
        );
        
        $this->getElement('region_id')
            ->setRegisterInArrayValidator(false)
            ->addValidator( new Shineisp_Validate_Regions( ) );

                  
        $this->addElement('select', 'contacttypes', array(
        'label' => $translate->_('Contact Types'),
        'decorators' => array('Bootstrap'),
        'class'      => 'form-control'));
        
        $this->getElement('contacttypes')
                  ->setAllowEmpty(false)
                  ->setMultiOptions(ContactsTypes::getList());
        
        $this->addElement('text', 'contact', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Contact'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'decorators' => array('Bootstrap'),
            'validators' => array(
                'EmailAddress'
            ),
            'required'   => true,
            'label'      => $translate->_('Email'),
            'class'      => 'form-control'
        ));
        
        $this->addElement('textarea', 'note', array(
            'filters'    => array('StringTrim'),
            'label'      => $translate->_('Private Notes'),
            'decorators' => array('Bootstrap'),
            'class'      => 'col-lg-12 form-control wysiwyg'
        ));
        
        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'decorators' => array('Bootstrap'),
            'validators' => array(
                //'Alnum',
                array('regex', false, '/^[a-zA-Z0-9\-\_\.\%\!\$]{6,20}$/')
                //array('StringLength', false, array(6, 20)),
            ),
            'description'      => 'Write here at least 6 characters.',
            'label'      => $translate->_('Password'),
            'class'      => 'form-control'
        ));
        
        
        $this->addElement('select', 'status_id', array(
        'label' => $translate->_('Status'),
        'decorators' => array('Bootstrap'),
        'class'      => 'form-control'
        ));
        
        $this->getElement('status_id')
                  ->setAllowEmpty(false)
                  ->setMultiOptions(Statuses::getList('customers'));
        
        
        $this->addElement('select', 'parent_id', array(
                            'label' => 'Reseller',
                            'decorators' => array('Bootstrap'),
        					'description' => 'Select the client who you want to join with the selected customer.',
                            'class'      => 'form-control'
        ));
        $criterias = array(array('where'=>'isreseller = ?', 'params'=>'1'));
        $this->getElement('parent_id')
                  ->setAllowEmpty(true)
                  ->setMultiOptions(Customers::getList(true, $criterias));         
                  
        $this->addElement('submit', 'save', array(
            'label'    => $translate->_('Save'),
            'decorators' => array('Bootstrap'),
            'class'    => 'btn'
        ));
                
        $this->addElement('hidden', 'customer_id');
    }
}