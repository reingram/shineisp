<?php
/**
 * Message helper
 * 
 */
class Admin_View_Helper_Message extends Zend_View_Helper_Abstract{

    /**
     * Create the message box
     * 
     * @param string $message 
     * @param string $type [error, success, info]
     * @param string $title
     * 
     * @return string
     */
    public function message($message, $type = "danger", $title = null)
    {
        if(!empty($message)){
            $this->view->message = strip_tags($message);
            $this->view->title = strip_tags($title);
            $this->view->type = "alert-$type";
            
            return $this->view->render ( 'partials/message.phtml' );
        }
        
        return null;
    }
}