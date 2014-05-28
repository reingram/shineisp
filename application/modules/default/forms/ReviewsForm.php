<?php
class Default_Form_ReviewsForm extends Zend_Form
{
    
    public function init()
    {
        // Set the custom decorator
        $this->addElementPrefixPath('Shineisp_Decorator', 'Shineisp/Decorator/', 'decorator');
        $translate = Shineisp_Registry::get('Zend_Translate');
        
        $this->addElement('text', 'nick', array(
            'filters'     => array('StringTrim'),
            'required'    => true,
        	'description' => $translate->_('Add your own nickname'),
            'decorators'  => array('Bootstrap'),
            'label'      => $translate->_('Nick'),
            'class'       => 'form-control'
        ));
                  
        $this->addElement('text', 'subject', array(
            'filters'     => array('StringTrim'),
            'required'    => false,
            'decorators'  => array('Bootstrap'),
        	'description' => $translate->_('Write down a subject of the review'),
            'label'      => $translate->_('Subject'),
            'class'       => 'form-control'
        ));
                  
        $this->addElement('select', 'referer', array(
            'filters'     => array('StringTrim'),
            'decorators'  => array('Bootstrap'),
        	'description' => $translate->_('Where did you find us?'),
            'label'      => $translate->_('Who is Talking About Us?'),
            'class'       => 'form-control',
        	'multiOptions' => array('Google' => 'Google', 'Bing' => 'Bing', 'Yahoo' => 'Yahoo', 'Other Search Engine' => 'Other Search Engine', 'Websites' => 'Websites/Blogs', 'Magento Commerce' => 'Magento Commerce', 'Friend suggestion' => 'Friend suggestion')
        ));
                  
        $this->addElement('text', 'city', array(
            'filters'     => array('StringTrim'),
            'decorators'  => array('Bootstrap'),
        	'description' => $translate->_('Which is your own city? If added we will promote your review in our website using Google Maps'),
            'label'      => $translate->_('City'),
            'class'       => 'form-control'
        ));
                  
        $this->addElement('text', 'email', array(
            'filters'     => array('StringTrim'),
            'required'    => true,
        	'validators' => array(
                'EmailAddress'
            ),
            'decorators'  => array('Bootstrap'),
        	'description' => 'Your email will be not published',
            'label'      => $translate->_('Email'),
            'class'       => 'form-control'
        ));
        
    	$this->addElement('select', 'stars', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => $translate->_('Stars'),
            'decorators' => array('Bootstrap'),
            'class'      => 'form-control',
    		'multiOptions' => array(1 => '1 ' . $translate->_('Star'), 2 => '2 ' . $translate->_('Stars'), 3 => '3 ' . $translate->_('Stars'), 4 => '4 ' . $translate->_('Stars'), 5 => '5 ' . $translate->_('Stars'))
        ));        
        
        $this->addElement('textarea', 'review', array(
            'filters'     => array('StringTrim'),
            'decorators'  => array('Bootstrap'),
        	'required'    => true,
        	'description' => $translate->_('Write down your review with details and you will earn points and discounts'),
            'label'      => $translate->_('Review'),
            'class'       => 'form-control',
            'rows'       => '8'
        ));
        
        $privKey = Settings::findbyParam('recaptcha_private_key');
        $pubKey = Settings::findbyParam('recaptcha_public_key');
        
        if(!empty($pubKey) && !empty($privKey)){
        
        	$recaptcha = new Zend_Service_ReCaptcha($pubKey, $privKey);
        	$captcha = new Zend_Form_Element_Captcha('captcha',
        			array(
        					'label' => $translate->_('Captcha Check'),
        					'description' => $translate->_('Type the characters you see in the picture below.'),
        					'captcha' =>  'ReCaptcha',
        					'captchaOptions'        => array(
        							'captcha'   => 'ReCaptcha',
        							'service'   => $recaptcha
        					)
        			)
        	);
        
        	$this->addElement($captcha);
        }else{

       		$captcha = new Zend_Form_Element_Captcha(
        				'captcha', // This is the name of the input field
        				array('label' => $translate->_('Write the chars to the field'),
        						'captcha' => array( // Here comes the magic...
        								// First the type...
        								'captcha' => 'Image',
        								// Length of the word...
        								'wordLen' => 6,
        								// Captcha timeout, 5 mins
        								'timeout' => 300,
        								// What font to use...
        								'font' => PUBLIC_PATH . '/resources/fonts/arial.ttf',
        								// Where to put the image
        								'imgDir' => PUBLIC_PATH . '/tmp',
        								// URL to the images
        								// This was bogus, here's how it should be... Sorry again :S
        								'imgUrl' => '/tmp/',
        						)));
        	$this->addElement($captcha);
        }
   
        $this->addElement('submit', 'save', array(
            'required' => false,
            'label'      => $translate->_('Publish your Review'),
            'decorators' => array('Bootstrap'),
            'class'    => 'btn btn-primary'
        ));
        
        $this->addElement('hidden', 'product_id');

    }
    
}