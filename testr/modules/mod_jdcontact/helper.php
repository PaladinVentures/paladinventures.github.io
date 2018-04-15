<?php

/*------------------------------------------------------------------------
# J DContact
# ------------------------------------------------------------------------
# author                Md. Shaon Bahadur
# copyright             Copyright (C) 2013 j-download.com. All Rights Reserved.
# @license -            http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites:             http://www.j-download.com
# Technical Support:    http://www.j-download.com/request-for-quotation.html
-------------------------------------------------------------------------*/

defined('_JEXEC') or die;

ini_set("display_errors" , 0);

class modJdcontactHelper
{
	static function preLoadprocess(&$params)
	{
         if($_POST){
            $javascript_enabled         =       trim($_REQUEST['browser_check']);
            $department                 =       trim($_REQUEST['dept']);
            $name                       =       trim($_REQUEST['name']);
            $email                      =       trim($_REQUEST['email']);
            $phno                       =       trim($_REQUEST['phno']);
            $subject                    =       trim($_REQUEST['subject']);
            $msg                        =       trim($_REQUEST['msg']);
            $sales_address              =       $params->get( 'sales_address', 'sales@yourdomain.com' );
            $support_address            =       $params->get( 'support_address', 'support@yourdomain.com' );
            $billing_address            =       $params->get( 'billing_address', 'billing@yourdomain.com' );
            $selfcopy                   =       isset($_REQUEST['selfcopy']) ? $_REQUEST['selfcopy'] : "";
            $humantest                  =       $_REQUEST['human_test'];
            $sum_test                   =       $_REQUEST['sum_test'];
            $humantestpram              =        $params->get( 'humantestpram', '1' );
            $headers  = 'MIME-Version: 1.0rn';
            $headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
            $headers .= 'From: '.$name.' <'.$email.'>'."\r\n";
 			$pageURL = $_SERVER['HTTP_REFERER'];

            $message = "URL: $pageURL\nContact name: $name\nContact Email: $email\nSubject: $subject\nMessage: $msg";
        	if ( $department == "sales")        $to     =   $sales_address;
        	elseif ( $department == "support")  $to     =   $support_address;
        	elseif ( $department == "billing")  $to     =   $billing_address;
            else                                $to     =   $sales_address;

        	if ( $name == "" )
        	{
        		$result = "".JText::_('MOD_JDCONTACT_VLDNAME')."";
        	}
        	elseif (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email))
        	{
        		$result = "".JText::_('MOD_JDCONTACT_VALIDEMAIL')."";
        	}
        	
        	elseif ( $subject == "" )
        	{
        		$result = "".JText::_('MOD_JDCONTACT_MSGSUBJECT')."";
        	}
        	elseif ( strlen($msg) < 10 )
        	{
        		$result = "".JText::_('MOD_JDCONTACT_MORETENWRD')."";
        	}
            else if($humantestpram=='1' && $humantest!=$sum_test){
        	    $result = "".JText::_('MOD_JDCONTACT_CORRECTNUM')."";
            }
        	else
        	{
        	    if(@mail($to, $subject, $message, $headers)){
                    $sucs=1;
        	    }
        		if( $selfcopy == "yes" ){
        		    if(@mail($email, $subject, $message, $headers)){
                        $sucs=1;
        		    }
                }
                if($sucs==1){
        		    $result = "".JText::_('MOD_JDCONTACT_SUCCESSMSG')."";
                }
                else{
                    $result = "".JText::_('MOD_JDCONTACT_MAILSERVPROB')."";
                }
        	}

        	if($javascript_enabled == "true") {
        		echo $result;
        		die();
        	}
        }
	}
}

?>