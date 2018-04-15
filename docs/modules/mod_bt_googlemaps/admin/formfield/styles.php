<?php
/**
 * @package 	formfields
 * @version		1.0.0
 * @created		Oct 2011
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

class JFormFieldStyles extends JFormField{
    protected $type = 'styles';
    protected function getLabel(){
    	return '';
    	
    } 
    protected function  getInput() {
    	
        $html  = ' <div id="btg-style-message" class="clearfix" style="clear:both;"></div>'
                . '    <ul id="btg-style-warning"></ul>'
                . '<ul id="btg-style">'
                . '		<li id="btg-style-main-button">'
                . '     	<label>&nbsp;</label>'
                . '     	<span id="btnAddStyle" class="btg-green-btn" versionjl="'.JVERSION.'" baseurl=" '.JURI::root().'">' . JText::_('MOD_BT_GOOGLE_MAP_ADD_STYLE_LABEL') . '</span>'
                . '     	<span id="btnDeleteAllStyle" class="btg-red-btn">' . JText::_('MOD_BT_GOOGLE_MAP_DELETE_ALL_STYLE_LABEL') . '</span>'
                . '		</li>'
                . '		<li id="style-form">&nbsp;</li>'
                . '		<li >'
                . '			<div class="btg-style-head">' . JText::_('STYLE_LIST') . '</div>'
                . '			<input id="btg-style-hidden" type="hidden" name="' . $this->name . '" value="' . $this->value . '" />'
                . '  		<ul id="btg-styles-container" class="clearfix adminformlist">'
                . '			</ul>'
                . '		</li>'
                . '</ul>';
		 $moduleID = $this->form->getValue('id');
        if ($moduleID == '')
            $moduleID = 0;
                
 ?>
 <script type="text/javascript">
 // setup for manager Styles 
 	var dialogStyleHtml = '<div id="btg-form-style"><div class= "btg-messages" id="btg-sylte-messages" class="clearfix"></div>'
    + '<ul class="btg-style-dialog-container">'
    + '     <li >'
    + '         <label for="btg-feature-type"  title="<?php echo JText::_('MOD_BT_GOOGLE_MAP_FEATURE_TYPE_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_FEATURE_TYPE_LABEL') ?></label>'
    + '         <select id="btg-feature-type" name="btg-feature-type" >'
    + '				<option value="all">All</option>'
    + '				<option value="administrative">Administrative</option>'
    + '				<option value="administrative.country">Country</option>'
    + '				<option value="administrative.province">Province</option>'
    + '				<option value="administrative.locality">Locality</option>'
    + '				<option value="administrative.neighborhood">Neighborhood</option>'
    + '				<option value="administrative.land_parcel">Land Parcel</option>'
    + '				<option value="landscape">Land Scape</option>'
    + '				<option value="landscape.man_made">Man Made</option>'
    + '				<option value="landscape.natural">Natural</option>'
    + '				<option value="poi">Point of interest(Poi)</option>'
    + '				<option value="poi.attraction">Poi.Attractioin</option>'
    + '				<option value="poi.business">Poi.Business</option>'
    + '				<option value="poi.government">Poi.Goverment</option>'
    + '				<option value="poi.medical">Poi.Medical</option>'
    + '				<option value="poi.park">Poi.Park</option>'
    + '				<option value="poi.place_of_worship">Poi.Place of Worship</option>'
    + '				<option value="poi.school">Poi.School</option>'
    + '				<option value="poi.sports_complex">Poi.Sports Complex</option>'
    + '				<option value="road">Road</option>'
    + '				<option value="road.arterial">Road.Arterial</option>'
    + '				<option value="road.highway">Road.Highway</option>'
    + '				<option value="road.highway.controlled_access">Road.Highway.Controlled Access</option>'
    + '				<option value="road.local">Road.Local</option>'
    + '				<option value="transit">Transit</option>'
    + '				<option value="transit.line">Transit.Line</option>'
    + '				<option value="transit.station">Transit.Station</option>'
    + '				<option value="transit.station.airport">Transit.Station.Airport</option>'
    + '				<option value="transit.station.bus">Transit.Station.Bus</option>'
    + '				<option value="transit.station.rail">Transit.Station.Rail</option>'
    + '				<option value="water">Water</option>'
    + '			</select>'
    + '     </li>'
    + '     <li>'
    + '         <label for="btg-style-element-type"  title="<?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_ELEMENT_TYPE_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_ELEMENT_TYPE_LABEL') ?></label>'
    + '         <select  name="btg-style-element-type" id="btg-style-element-type">'
    + '             <option value="all">All</option>'
    + '             <option value="geometry">Geometry</option>'
    + '             <option value="geometry.fill">Geometry.Fill</option>'
    + '             <option value="geometry.stroke">Geometry.Stroke</option>'
    + '             <option value="labels">Labels</option>'
    + '             <option value="labels.icon">Labels.Icon</option>'
    + '             <option value="labels.text">Labels.Text</option>'
    + '             <option value="labels.text.fill">Labels.Text.Fill</option>'
    + '             <option value="labels.text.stroke">Labels.Text.Stroke</option>'
    + '         </select>'
    + '     </li>'
    + '     <li >'
    + '     	<label for="btg-style-visibility"  title ="<?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_VISIBILITY_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_VISIBILITY_LABEL') ?></label>'
    + '         <select id="btg-style-visibility" name="btg-style-visibility">'
	+ '				<option value="">None</option>'
    + '				<option value="simply">Simply</option>'
    + '				<option value="on">On</option>'
    + '				<option value="off">Off</option>'
    + '			</select>'
    + '     </li>'
    + '     <li >'
    + '     	<label for="btg-style-invert-lightness" title ="<?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_INVERT_LIGHTNESS_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_INVERT_LIGHTNESS_LABEL') ?></label>'
    + '     	<select id="btg-style-invert-lightness" name="btg-style-invert-lightness" class="bt_switch">'
    + '				<option value="false">No</option>'
    + '				<option value="true">Yes</option>'
    + '			</select>'
    + '     </li>'
    + '     <li >'
    + '			<label for="btg-style-map-color"  title="<?php echo JText::_('MOD_BT_GOOGLE_MAP_MAP_COLOR_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_MAP_COLOR_LABEL') ?></label>'
    + '			<input type="text" id="btg-style-map-color" name="btg-style-map-color" class="bt_color"/>'
    + '     </li>'
    + '     <li >'
    + '			<label for="btg-style-weight"  title="<?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_WEIGHT_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_WEIGHT_LABEL') ?></label>'
    + '			<input type="text" id="btg-style-weight" name="btg-style-weight" />'
    + '     </li>'
    + '     <li >'
    + '			<label for="btg-style-hue"  title="<?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_HUE_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_HUE_LABEL') ?></label>'
    + '			<input type="text" id="btg-style-hue" name="btg-style-hue" class="bt_color"/>'
    + '     </li>'
    + '     <li >'
    + '			<label for="btg-style-saturation"  title="<?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_SATURATION_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_SATURATION_LABEL') ?></label>'
    + '			<input type="text" id="btg-style-saturation" name="btg-style-saturation" />'
    + '     </li>'
    + '     <li >'
    + '			<label for="btg-style-lightness"  title="<?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_LIGHTNESS_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_LIGHTNESS_LABEL') ?></label>'
    + '			<input type="text"  name="btg-style-lightness" id="btg-style-lightness"/>'
    + '     </li>'
    + '     <li >'
    + '     	<label for="btg-style-gamma" title ="<?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_GAMMA_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_STYLE_GAMMA_LABEL') ?></label>'
    + '         <input type="text" name="btg-style-gamma" id="btg-style-gamma"/>'
    + '     </li>'
    + '     <li id="btg-submit-btn">'
    + '         <span id="btnCreateStyle" class="btg-green-btn btg-panel-btn"><?php echo JText::_('MOD_BT_GOOGLE_MAP_BUTTON_CREATE_STYLE') ?></span>'
    + '         <span id="btnUpdateStyle" class="btg-green-btn btg-panel-btn"><?php echo JText::_('MOD_BT_GOOGLE_MAP_BUTTON_UPDATE_STYLE') ?></span>'
    + '         <span id="btnCancel" class="btg-red-btn btg-panel-btn"><?php echo JText::_('MOD_BT_GOOGLE_MAP_BUTTON_CANCEL') ?></span>'
    + '     </li>'
    + '</ul>'
    + '<div style="clear:both"></div>'
    + '</div>';
    
    var jQ = jQuery.noConflict();
    
    jQ(document).ready(function(){
    	  //init form preview
        var styleList = new BT.StyleList({
            liveURL : '<?php echo JURI::root() . 'modules/mod_bt_googlemaps' ?>',
            warningText: {
                addStyleSuccess: '<?php echo JText::_('ADD_STYLE_SUCCESS') ?>',
                updateStyleSuccess: '<?php echo JText::_('UPDATE_STYLE_SUCCESS') ?>',
                confirmDeleteAll: '<?php echo JText::_('CONFIRM_DELETE_ALL') ?>',
                confirmDelete: '<?php echo JText::_('CONFIRM_DELETE') ?>',
                deleteAllSuccess: '<?php echo JText::_('DELETE_ALL_SUCCESS') ?>'
            },
            dialogTemplate: dialogStyleHtml,
            encodedItems : '<?php echo $this->value ?>',
            moduleID: '<?php echo $moduleID ?>',
            container: 'btg-styles-container',
            messageContainer: 'btg-warning',
            btnCreateID: '#btnCreateStyle',
            btnUpdateID: '#btnUpdateStyle',
            btnCancelID: '#btnCancel'

        });
                        
        /**
         * Open style options when click add style
         * 
         */
        jQ("#btnAddStyle").click(function(){
        	styleList.openDialog();
            
            return false;
        });
        /**
         * Close all options when click cancel
         */

        //remove all
        jQ('#btnDeleteAllStyle').click(function(){
            if(jQ('#btg-styles-container li').length > 0 )
                styleList.removeAll();
            return false;
        });
    });
 </script>
 
 <?php 
               
        return $html;
    }
}