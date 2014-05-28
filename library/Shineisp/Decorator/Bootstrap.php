<?php

/**
 * ShineISP Input objects creator for the Bootstrap Framework
 * 
 * @author shinesoftware
 */

class Shineisp_Decorator_Bootstrap extends Zend_Form_Decorator_Abstract {
	
	/**
	 * Build the label of the object
	 */
	public function buildLabel() {
		$nolabel = array ();
		$element = $this->getElement ();
		$label = $element->getLabel ();
		$translator = $element->getTranslator ();
		$attrs ['class'] = $element->hasErrors () ? "error" : "";
		
		if ($translator) {
			$label = $translator->translate ( $label );
		}
		
		if ($element->isRequired ()) {
			$label .= '';
		}
		
		$nolabel [] = "Zend_Form_Element_Submit";
		$nolabel [] = "Zend_Form_Element_Reset";
		$nolabel [] = "Zend_Form_Element_Button";
		
		if (! in_array ( $element->getType (), $nolabel )) {
			if (! empty ( $label )) { // If the label is not an empty value
				return $element->getView ()->formLabel ( $element->getName (), $label, $attrs );
			}
		}
	}
	
	/**
	 * Build the input object
	 */
	public function buildInput() {
		$element = $this->getElement ();
		$helper = $element->helper;
		$attrs = $element->getAttribs ();
		$translate = Shineisp_Registry::get ( 'Zend_Translate' );
		
		if($element->isRequired ()){
			$attrs ['required'] = true;
		}
		
		if (! isset ( $attrs ['class'] )) {
			$attrs ['class'] = "";
		}
		
		$attrs ['class'] .= $element->hasErrors () ? " error" : "";
		$attrs ['title'] = ! empty ( $attrs ['title'] ) ? $translate->translate ( $attrs ['title'] ) : "";
// 		$attrs ['placeholder'] = $element->getDescription ();
		
		if ($element->getType () == "Zend_Form_Element_Submit" || $element->getType () == "Zend_Form_Element_Button") {
			$el = $element->getView ()->$helper ( $element->getName (), $translate->translate ( $element->getLabel () ), $attrs, $element->options );
		} else {
			$el = $element->getView ()->$helper ( $element->getName (), $element->getValue (), $attrs, $element->options );
		}
		
		return $el;
	}
	
	/**
	 * Build the errors messages
	 * 
	 * @return string
	 */
	public function buildErrors() {
		$element = $this->getElement ();
		$messages = $element->getMessages ();
		if (empty ( $messages )) {
			return '';
		}
		return '<span class="alert alert-danger help-inline">' . $element->getView ()->formErrors ( $messages ) . '</span>';
	}
	
	/**
	 * This function creates the comment of the object
	 */
	public function buildDescription() {
		$element = $this->getElement ();
		$desc = $element->getDescription ();
		$translator = $element->getTranslator ();
		
		if ($translator) {
			$desc = $translator->translate ( $desc );
		}
		
		if (empty ( $desc )) {
			return '';
		}
		return '<span class="help-block">' . $desc . '</span>';
	}
	
	/**
	 * Render the html object
	 * 
	 * (non-PHPdoc)
	 * @see Zend_Form_Decorator_Abstract::render()
	 */
	public function render($content) {
		$element = $this->getElement ();
		if (! $element instanceof Zend_Form_Element) {
			return $content;
		}
		
		if (null === $element->getView ()) {
			return $content;
		}
		
		$separator = $this->getSeparator ();
		$placement = $this->getPlacement ();
		$label = $this->buildLabel ();
		$input = $this->buildInput ();
		$errors = $this->buildErrors ();
		$desc = $this->buildDescription ();
		$name = $this->getElement ()->getName ();
		
		$iserror = !empty($errors) ? "error" : null;
		switch ($element->getType ()) {
			case "Zend_Form_Element_Text":
				$output = "<div class=\"form-group $iserror style-$name\">";
				$output .= $label;
				$output .= $input;
				$output .= $desc;
				$output .= $errors;
				$output .= "</div>";
			break;
			
			case "Zend_Form_Element_Password":
				$output = "<div class=\"form-group $iserror style-$name\">" . $label;
				$output .= "$input $desc $errors";
				$output .= "</div>";
			break;
			
			case "Zend_Form_Element_Checkbox":
				/* Implement http://www.bootstrap-switch.org/ */
				$this->getElement()->setAttrib("class", "label-change-switch");
				$label = $element->getView ()->formLabel($element->getFullyQualifiedName(), trim($label), array('class' => 'label-change-switch'));
				$pos = strpos($label, ">");
				$start_label =  substr($label, 0, $pos+1);

				$output = "<div class=\"form-group $iserror style-$name\">";
				$output .= "<label>" . $this->getElement()->getLabel() . "</label> <br/>";
				$output .= "<div class=\"make-switch\">" . $input . "</div>";
				$output .= "</div>";
			break;
			
			case "Zend_Form_Element_Submit":
				$output = $input;
			break;
			
			case "Zend_Form_Element_Select":
				$output = "<div class=\"form-group $iserror style-$name\">" . $label;
				$output .= $input;
				$output .= $desc;
				$output .= "</div>";
			break;
			
			case "Zend_Form_Element_Button":
				$output = $input;
			break;
			
			default:
				$output = "<div class=\"form-group $iserror style-$name\">" . $label;
				$output .= $input;
				$output .= $desc;
				$output .= $errors;
				$output .= "</div>";
			break;
		}
		
		switch ($placement) {
			case (self::PREPEND) :
				return $output . $separator . $content;
			
			default :
				return $content . $separator . $output;
		}
	}
}