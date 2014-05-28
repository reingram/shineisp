<?php

/**
 * BreadCrumb View Helper
 *@author Joey Adams
 *
 */
class Zend_View_Helper_BreadCrumb extends Zend_View_Helper_Abstract{
	
    public function breadCrumb() {
        $ns = new Zend_Session_Namespace ();
		$registry = Shineisp_Registry::getInstance ();
		$translation = Shineisp_Registry::get ( 'Zend_Translate' );
		$module = Zend_Controller_Front::getInstance ()->getRequest ()->getModuleName ();
		$l_m = strtolower ( $module );
		
		$controller = Zend_Controller_Front::getInstance ()->getRequest ()->getControllerName ();
		$l_c = strtolower ( $controller );
		
		$action = Zend_Controller_Front::getInstance ()->getRequest ()->getActionName ();
		
		$params = Zend_Controller_Front::getInstance ()->getRequest ()->getParams();
		
		$l_a = strtolower ( $action );
		
		// HomePage = No Breadcrumb
		if ($l_m == 'default' && $l_c == 'index' && $l_a == 'index') {
			return;
		}
		
		// Get our url and create a home crumb
		$fc = Zend_Controller_Front::getInstance();
		$url = $fc->getBaseUrl();
		$homeLink = "<ul class='breadcrumb'><li><a href='{$url}/'><i class='glyphicon glyphicon-home'></i> Home</a></li>";
		
		// Start crumbs
		$crumbs = $homeLink . " ";
		
		// If our module is default
		if ($l_m == 'default') {
		    
		    if($controller == "categories"){
		        $crumbs .= "<li>" . $translation->translate(ucwords("Categories")) . "</li>";
		    }elseif($controller == "cms"){
		        if(!empty($params['url'])){
    		        $crumbs .= "<li><a href=\"/cms/list\">" . $translation->translate(ucwords("Blog")) . "</a></li>";
		        }else{
		            $crumbs .= "<li>" . $translation->translate(ucwords("Blog")) . "</li>";
		        }
		    }elseif($controller == "products"){
		        $producturi = $params['q'];
		        $product = Products::getProductbyUriID($producturi, "p.product_id, pd.name, categories", $ns->langid);
		        if(!empty($product['cleancategories'][0])){
		            $crumbs .= "<li><a href='".$product['cleancategories'][0]['uri'].".html'>" . $product['cleancategories'][0]['name'] . "</a></li>";
		        }
		        $crumbs .= "<li class=\"active\"><a href='/$producturi.html'>". $product['ProductsData'][0]['name'] . "</a></li>";
		    }else{
		        $crumbs .= "<li><a href='{$url}/{$controller}/'>". $translation->translate($controller) . "</a></li><li>" . $translation->translate(ucwords($action)) . "</li>";
		    }
		    
		}
		$crumbs .= "</ul>";
		return $crumbs;
	}

}
