<?php
/**
 * @package 	formfield
 * @version		2.0
 * @created		April 2012

 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2011 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldAsset extends JFormField {

    protected $type = 'Asset';

    protected function getInput() {		

		
		$document	= JFactory::getDocument();
		$header = $document->getHeadData();
		if(version_compare(JVERSION, '3.0', 'ge')){
			JHtml::_('jquery.framework');
		}else {
			$checkJqueryLoaded = false;
			foreach($header['scripts'] as $scriptName => $scriptData)
			{
				if(substr_count($scriptName,'/jquery')){
					$checkJqueryLoaded = true;
					break;
				}
			}
				
			//Add js
			if(!$checkJqueryLoaded) 
			$document->addScript(JURI::root().$this->element['path'].'js/jquery.min.js');
		}
		
		$document->addScript(JURI::root().$this->element['path'].'js/colorpicker/colorpicker.js');
		$document->addScript(JURI::root().$this->element['path'].'js/btbase64.min.js');
		$document->addScript(JURI::root().$this->element['path'].'js/btstyles.js');
		$document->addScript(JURI::root().$this->element['path'].'js/btmakers.js');
		
		if(version_compare(JVERSION, '3.0', 'ge')){
	        $document->addScript(JURI::root().$this->element['path'].'js/bt30.js');
		}else{
			$document->addScript(JURI::root().$this->element['path'].'js/chosen.jquery.min.js');		
	        $document->addScript(JURI::root().$this->element['path'].'js/bt.js');
			$document->addStyleSheet(JURI::root().$this->element['path'].'css/chosen.css');   
		}

		//Add css         
		$document->addStyleSheet(JURI::root().$this->element['path'].'css/bt.css');
        $document->addStyleSheet(JURI::root().$this->element['path'].'js/colorpicker/colorpicker.css');        
             
                
        return null;
    }
}
?>