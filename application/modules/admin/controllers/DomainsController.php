<?php

/**
 * UsersController
 * Manage the users table
 * @author Michelangelo Turillo
 * @version 1.0
 */

class Admin_DomainsController extends Shineisp_Controller_Admin {
	
	protected $domains;
	protected $datagrid;
	protected $session;
	protected $translator;
	
	/**
	 * preDispatch
	 * Starting of the module
	 * (non-PHPdoc)
	 * @see library/Zend/Controller/Zend_Controller_Action#preDispatch()
	 */
	
	public function preDispatch() {
		$this->session = new Zend_Session_Namespace ( 'Admin' );
		$this->domains = new Domains ();
		$this->translator = Shineisp_Registry::getInstance ()->Zend_Translate;
		$this->datagrid = $this->_helper->ajaxgrid;
		$this->datagrid->setModule ( "domains" )->setModel ( $this->domains );
	}
	
	/**
	 * indexAction
	 * Redirect the user to the list action
	 * @return unknown_type
	 */
	public function indexAction() {
		$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper ( 'redirector' );
		$redirector->gotoUrl ( '/admin/domains/list' );
	}
	
	/**
	 * listAction
	 * Create the User object and get all the records.
	 * @return unknown_type
	 */
	public function listAction() {
		$this->view->title = $this->translator->translate("Domains list");
		$this->view->description = $this->translator->translate("Here you can see all the domains.");
		$this->view->buttons = array(array("url" => "/admin/domains/new/", "label" => $this->translator->translate('New'), "params" => array('css' => null)));
		$this->datagrid->setConfig ( Domains::grid () )->datagrid ();
	}
	
	/**
	 * Load Json Records
	 *
	 * @return string Json records
	 */
	public function loadrecordsAction() {
		$this->_helper->ajaxgrid->setConfig ( Domains::grid() )->loadRecords ($this->getRequest ()->getParams());
	}
	
	/**
	 * searchProcessAction
	 * Search the record 
	 * @return unknown_type
	 */
	public function searchprocessAction() {
		$this->_helper->ajaxgrid->setConfig ( Domains::grid () )->search ();
	}
	
	/*
	 *  bulkAction
	 *  Execute a custom function for each item selected in the list
	 *  this method will be call from a jQuery script 
	 *  @return string
	 */
	public function bulkAction() {
		$this->_helper->ajaxgrid->massActions ();
	}
	
	/**
	 * recordsperpage
	 * Set the number of the records per page
	 * @return unknown_type
	 */
	public function recordsperpageAction() {
		$this->_helper->ajaxgrid->setRowNum ();
	}
	
	/**
	 * newAction
	 * Create the form module in order to create a record
	 * @return unknown_type
	 */
	public function newAction() {
		$this->view->form = $this->getForm ( '/admin/domains/process' );
		$this->view->title = $this->translator->translate("New Domain");
		$this->view->description = $this->translator->translate("Here you can create a new domain.");
		$this->view->buttons = array(array("url" => "#", "label" => $this->translator->translate('Save'), "params" => array('css' => null,'id' => 'submit')),
									 array("url" => "/admin/domains/list", "label" => $this->translator->translate('List'), "params" => array('css' => null)));
		$this->view->mex = $this->getRequest ()->getParam ( 'mex' );
		$this->view->mexstatus = $this->getRequest ()->getParam ( 'status' );
		$this->render ( 'applicantform' );
	}
	
	/**
	 * Search the record for the Select2 JQuery Object by ajax
	 * @return json
	 */
	public function searchAction() {
	
	    if($this->getRequest()->isXmlHttpRequest()){
	
	        $term = $this->getParam('term');
	        $id = $this->getParam('id');
	
	        if(!empty($term)){
	            $term = "%$term%";
	            $records = Domains::findbyCustomFields("CONCAT(d.domain, '.', d.tld) LIKE ?", $term);
	            die(json_encode($records));
	        }
	
	        if(!empty($id)){
	            $records = Domains::find($id);
	            die(json_encode($records));
	        }
	
	        $records = Domains::get_domains_active();
	        die(json_encode($records));
	    }else{
	        die();
	    }
	}
	
	/**
	 * confirmAction
	 * Ask to the user a confirmation before to execute the task
	 * @return null
	 */
	public function confirmAction() {
		$id = $this->getRequest ()->getParam ( 'id' );
		$exec = $this->getRequest ()->getParam ( 'exec' );
		
		$controller = Zend_Controller_Front::getInstance ()->getRequest ()->getControllerName ();
		try {
			if (is_numeric ( $id )) {
				
				switch ($exec) {
					
					default :
						$this->view->title = $this->translator->translate ( 'Are you sure you want to delete this domain?' );
						$this->view->description = $this->translator->translate ( 'If a domain is deleted, you will not be able to restore it.' );
						$this->view->back = "/admin/$controller/edit/id/$id";
						$this->view->goto = "/admin/$controller/delete/id/$id";
						break;
				}
				
				$record = $this->domains->find ( $id, "CONCAT(d.domain, '.', d.tld) as domain", true );
				$this->view->recordselected = $record [0] ['domain'];
			} else {
				$this->_helper->redirector ( 'list', $controller, 'admin', array ('mex' => $this->translator->translate ( 'Unable to process the request at this time.' ), 'status' => 'danger' ) );
			}
		} catch ( Exception $e ) {
			echo $e->getMessage ();
		}
	}
	
	/**
	 * deleteAction
	 * Delete a record previously selected by the user
	 * @return unknown_type
	 */
	public function deleteAction() {
		$id = $this->getRequest ()->getParam ( 'id' );
		try {
			Domains::delete_by_id( $id ) ;
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
		return $this->_helper->redirector ( 'index', 'domains' );
	}
	
	/**
	 * deletednszone
	 * Delete a dns record 
	 * @return unknown_type
	 */
	public function deletednszoneAction() {
		$id = $this->getRequest ()->getParam ( 'id' );
		
		try {
			$domain = Dns_Zones::getDomain ( $id );
			if (! empty ( $domain [0] ['Domains'] ['domain_id'] )) {
				$domainid = $domain [0] ['Domains'] ['domain_id'];
				if(Dns_Zones::deleteZone ( $id )){
					$this->_helper->redirector ( 'edit', 'domains', 'admin', array ('id' => $domainid ) );
				}
			}
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
		
		$this->_helper->redirector ( 'list', 'domains', 'admin' );
	}
	
	/**
	 * editAction
	 * Get a record and populate the application form 
	 * @return unknown_type
	 */
	public function editAction() {
		$form = $this->getForm ( '/admin/domains/process' );
		
		$this->view->title = $this->translator->translate("Domain Edit");
		$this->view->description = $this->translator->translate("Here you can edit your own domain parameters.");
		
		$id = $this->getRequest ()->getParam ( 'id' );
		
		if (! empty ( $id ) && is_numeric ( $id )) {
			
			// Create the buttons in the edit form
			$this->view->buttons = array(
					array("url" => "#", "label" => $this->translator->translate('Save'), "params" => array('css' => null,'id' => 'submit')),
					array("url" => "/admin/domains/confirm/id/$id", "label" => $this->translator->translate('Delete'), "params" => array('css' => null)),
					array("url" => "/admin/domains/list", "label" => $this->translator->translate('List'), "params" => array('css' => null)),
					array("url" => "/admin/domains/new/", "label" => $this->translator->translate('New'), "params" => array('css' => null)),
					array("url" => "/admin/domains/newevent/id/$id", "label" => $this->translator->translate('Calendar Event'), "params" => array('css' => null)),
			);
			
			try {
				$rs = $this->domains->find ( $id, null );
			
				if (! empty ( $rs [0] )) {
					$rs [0] ['creation_date'] = Shineisp_Commons_Utilities::formatDateOut ( $rs [0] ['creation_date'] );
					$rs [0] ['expiring_date'] = Shineisp_Commons_Utilities::formatDateOut ( $rs [0] ['expiring_date'] );
					$rs [0] ['status_id'] = $rs [0] ['Statuses'] ['status_id'];
					
					// Domains NicHandles
					$rs [0] ['owner'] = DomainsNichandle::getProfile($id);
					$rs [0] ['admin'] = DomainsNichandle::getProfile($id, "admin");
					$rs [0] ['tech'] = DomainsNichandle::getProfile($id, "tech");
					$rs [0] ['billing'] = DomainsNichandle::getProfile($id, "billing");

					$form->populate ( $rs [0] );
					
					if(!empty($rs [0] ['DomainsTlds']['WhoisServers'])){
						$this->view->title = $rs [0] ['domain'] . "." . $rs [0] ['DomainsTlds']['WhoisServers']['tld'];
						$this->view->titlelink = "http://" . $rs [0] ['domain'] . "." . $rs [0] ['DomainsTlds']['WhoisServers']['tld'];
						$this->view->icon = "fa fa-globe";
					}
					
					$this->view->owner_datagrid = domains::ownerGrid ( $id );
					$this->view->actions = Registrars::getActions ( $rs [0] ['registrars_id'] );
				}
				
			} catch ( Exception $e ) {
				die ( $e->getMessage () );
			}
			$this->view->id = $id;
			// Get all the messages attached to the domain
			$this->view->messages = Messages::getbyDomainId($id);
		}
		
		$this->view->mex = $this->getRequest ()->getParam ( 'mex' );
		$this->view->mexstatus = $this->getRequest ()->getParam ( 'status' );
		
		$this->view->dns_datagrid = Domains::dnsGrid ();
		$this->view->form = $form;
		
		$this->view->services_datagrid = array ('records' => domains::Services ($id), 'edit' => array ('controller' => 'services', 'action' => 'edit' ) ); 
		$this->view->orders_datagrid = array ('records' => domains::Orders ($id), 'edit' => array ('controller' => 'orders', 'action' => 'edit' ) ); 
		$this->render ( 'applicantform' );
	}
	
	/**
	 * processAction
	 * Update the record previously selected
	 * @return unknown_type
	 */
	public function processAction() {
		$request = $this->getRequest ();
		
		// Check if we have a POST request
		if (! $request->isPost ()) {
			return $this->_helper->redirector ( 'index' );
		}
		
		// Get our form and validate it
		$form = $this->getForm ( '/admin/domains/process' );
		
		if (! $form->isValid ( $request->getPost () )) {
			// Invalid entries
			$this->view->form = $form;
			$this->view->title = $this->translator->translate("Domain process task");
			$this->view->description = $this->translator->translate("Here you have to fix the domain parameters set.");
			return $this->render ( 'applicantform' ); // re-render the login form
		}
		
		// Get the values posted
		$params = $form->getValues ();
		
		// Get the id 
		$id = $this->getRequest ()->getParam ( 'domain_id' );
		
		// Create the buttons in the edit form
		$this->view->buttons = array(
				array("url" => "#", "label" => $this->translator->translate('Save'), "params" => array('css' => null,'id' => 'submit')),
				array("url" => "/admin/domains/list", "label" => $this->translator->translate('List'), "params" => array('css' => null,'id' => 'submit')),
				array("url" => "/admin/domains/new/", "label" => $this->translator->translate('New'), "params" => array('css' => null)),
		);
		
		try {
			$id = Domains::saveAll($id, $params);
			Domains::saveDnsZones ( $id, $params );
			Domains::setAutorenew($id, ($params['autorenew'] == 0) ? false: true);
		    DomainsNichandle::setNicHandles($id, $params['owner'], $params['admin'], $params['tech'], $params['billing']);
			
			// If the domain status has been set as active
			// the registrar task record will be set as completed  
			if ($params ['status_id'] == Statuses::id("active", 'domains')) {
				DomainsTasks::setStatusTask ( $id, Statuses::id("complete", 'domains_tasks') ); // Complete
			}
			
			// Save the message note
			if (! empty ( $params ['message'] )) {
				$message = new Messages ();
				$message->dateposted = date ( 'Y-m-d H:i:s' );
				$message->message = $params ['message'];
				$message->isp_id = 1;
				$message->domain_id = $id;
				$message->save ();
			}
			
			$this->_helper->redirector ( 'edit', 'domains', 'admin', array ('id' => $id, 'mex' => 'The task requested has been executed successfully.', 'status' => 'success' ) );
		
		} catch ( Exception $e ) {
			$this->_helper->redirector ( 'list', 'domains', 'admin', array ('mex' => $e->getMessage (), 'status' => 'danger' ) );
		}
	}
	
	/**
	 * getForm
	 * Get the customized application form 
	 * @return unknown_type
	 */
	private function getForm($action) {
		$form = new Admin_Form_DomainsForm ( array ('action' => $action, 'method' => 'post' ) );
		return $form;
	}
	
	/*
	 * update
	 * Update the domain information like expiring date etc...
	 */
	public function updateAction() {
		$domain_id = $this->getRequest ()->getParam ( 'id' );
		if (is_numeric ( $domain_id )) {
			try {
				$record = $this->domains->find ( $domain_id, "d.domain as domain, d.tld as tld, d.authinfocode, cdr.customer_id as customer_id, cdr.value as nichandle", true );
				
				if (is_array ( $record [0] )) {
					$product = Domains::findProduct ( $domain_id, 'domain_id' );
					$domain = $record [0] ['domain'] . "." . $record [0] ['tld'];
					$domain_id = $record [0] ['domain_id'];
					
					$retval = DomainsTasks::AddTask ( $domain, 'update' );
					if ($retval) {
						$this->_helper->redirector ( 'edit', 'domains', 'admin', array ('id' => $domain_id, 'mex' => 'Domain task added to queue.', 'status' => 'success' ) );
					} else {
						$this->_helper->redirector ( 'edit', 'domains', 'admin', array ('id' => $domain_id, 'mex' => 'Domain task has been not added to the queue.', 'status' => 'danger' ) );
					}
				}
			} catch ( Exception $e ) {
				$this->_helper->redirector ( 'edit', 'domains', 'admin', array ('id' => $domain_id, 'mex' => $e->getMessage (), 'status' => 'danger' ) );
			}
			$this->_helper->redirector ( 'index', 'domains' );
		}
	}
	
	/**
	 * getdomaincreationdateAction
	 * @return json array
	 */
	public function getdomaincreationdateAction() {
		// Check if it is an ajax request
		if ($this->_request->isXmlHttpRequest ()) {
			$id = $this->getRequest ()->getParam ( 'id' );
			$domain = Domains::find ( $id, "DATE_FORMAT(d.creation_date, '%d/%m/%Y') as creationdate", true );
			if (isset ( $domain [0] )) {
				die ( json_encode ( array ('creationdate' => $domain [0] ['creationdate'] ) ) );
			} else {
				die ( json_encode ( array ('creationdate' => null ) ) );
			}
		}
	}
	
	/**
	 * addtask [Ajax call]
	 * Add a Task for the domain selected
	 * @return json array
	 */
	public function addtaskAction() {
		// Check if it is an ajax request
		if ($this->_request->isXmlHttpRequest ()) {
			
			// Get the parameters
			$domain_id = $this->getRequest ()->getParam ( 'id' );
			$method = $this->getRequest ()->getParam ( 'method' );
			
			// Check the parameters
			if (is_numeric ( $domain_id ) && ! empty ( $method )) {
				
				$record = $this->domains->find ( $domain_id, "d.domain as domain, ws.tld as tld, d.authinfocode, d.customer_id as customer_id, cdr.value as nichandle", true );
				if (is_array ( $record [0] )) {
					$domain = $record [0] ['domain'] . "." . $record [0] ['tld'];
					$domain_id = $record [0] ['domain_id'];
					$retval = DomainsTasks::AddTask ( $domain, $method, $domain_id );
					if ($retval) {
						die ( json_encode ( array ('result' => 1 ) ) );
					}else{
						die ( json_encode ( array ('result' => 0 ) ) );
					}
				}
			}
		}
		die ( json_encode ( array ('result' => 0 ) ) );
	}
	
	
	/**
	 * get all the domains using an Ajax call
	 * @return json array
	 */
	public function getdomainsbyajaxAction() {
		// Check if it is an ajax request
		if ($this->_request->isXmlHttpRequest ()) {
			$theitems = array ();
			$item = $this->getRequest ()->getParam ( 'search' );
			if (! empty ( $item )) {
				$domains = Domains::findByLikeDomainName ( $item, "d.domain_id, CONCAT(d.domain, '.', ws.tld) as domain", true );
				if ($domains) {
					foreach ( $domains as $c ) {
						$theitems [$c ['domain_id']] = $c ['domain'];
					}
					if (count ( $theitems ) > 0) {
						die ( json_encode ( $theitems ) );
					} else {
						die ( json_encode ( $theitems ) );
					}
				}
			}
		}
		die ();
	}
	
	/**
	 * Redirect the administrator to the domain selected
	 */
	public function gotoAction() {
		$id = $this->getRequest ()->getParam ( 'id' );
		if (! empty ( $id ) && is_numeric ( $id )) {
			$domain = Domains::find($id, "CONCAT('http://www.', domain, '.', ws.tld) as url");
			if(!empty($domain[0])){
				header('Location: ' . $domain[0]['url']);
				die;
			}
		}
	}
	
	/**
	 * Create a google calendar event
	 */
	public function neweventAction() {
		$id = $this->getRequest ()->getParam ( 'id' );
		if (! empty ( $id ) && is_numeric ( $id )) {
		    if(Shineisp_Plugins_Calendar_Main::isReady()){
    			$domain = Domains::find($id, "expiring_date as end, CONCAT(domain, '.', ws.tld) as domain, CONCAT('http://www.', domain, '.', ws.tld) as url");
    			if(!empty($domain[0])){
    			    $summary = $domain[0]['domain'];
    			    $description = $this->translator->_("The domain %s expires today!", $domain[0]['url']);
    			    $location = "";
    				if(true === Shineisp_Plugins_Calendar_Main::newEvent($summary, $location, $description, $domain[0]['end'], $domain[0]['end'])){
    				    $this->_helper->redirector ( 'edit', 'domains', 'admin', array ('id' => $id, 'mex' => 'The task requested has been executed successfully.', 'status' => 'success' ) );
    				}
    			}
		    }
		    $this->_helper->redirector ( 'edit', 'domains', 'admin', array ('id' => $id, 'mex' => $this->translator->translate ( 'Unable to process the request at this time.' ), 'status' => 'danger' ) );
		}
		
		$this->_helper->redirector ( 'edit', 'domains', 'admin', array ('id' => $id, 'mex' => $this->translator->translate ( 'Domain id has not been found.' ), 'status' => 'danger' ) );
	}

}