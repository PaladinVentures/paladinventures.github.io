<?php
/**
 * @package 	formfields
 * @version		1.0.0
 * @created		Oct 2012
 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support     Forum - http://bowthemes.com/forum/
 * @copyright   Copyright (C) 2012 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
   
class JFormFieldButton extends JFormField{

    protected $type = 'button';
 protected function getLabel() {
		return '';
	}
    protected function getInput() {
		
		$name = $this->element['name'];
		$text = $this->element['text'];
		$html = '<button id="bt-'.$name.'" class="bt-button">'.$text.'</button>';

		return $html;
        
      
    }    
}

?>
