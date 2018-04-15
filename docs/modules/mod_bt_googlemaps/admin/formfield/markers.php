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

class JFormFieldMarkers extends JFormField{
    protected $type = 'markers';
    
    protected static $initialisedMedia = false;
    
    protected function getLabel(){
    	return '';
    	
    } 
    protected function  getInput() {
    	
      $checkJqueryLoaded = false;
      $document = JFactory::getDocument();
      $header = $document->getHeadData();
      foreach ($header['scripts'] as $scriptName => $scriptData) {
          if (substr_count($scriptName, '/jquery')) {
                $checkJqueryLoaded = true;
          }
      }
	  
	  if(version_compare(JVERSION, '3.0', 'ge')){
		$document->addStyleDeclaration(
			'.btg-icon-marker .button2-left{float: left; margin: 0 5px;} '.
			'#btg-submit-btn{padding: 0px !important; margin-top: 10px;}'.
			'#btg-markers-container{ margin-left: 0px !important; } '.
			'#btg-markers-container li {padding: 7px 7px 8px !important; list-style: none;} '
		);
	  }
	  
      $moduleID = $this->form->getValue('id');
        if ($moduleID == '')
            $moduleID = 0;
      // for maker field
        $html =
                ' <div id="btg-message" class="clearfix" style="clear:both;"></div>'
                . '    <ul id="btg-warning"></ul>'
                . '<ul id="bt-makers">'
                . '		<li id="btg-main-button">'
                . '     	<label>&nbsp;</label>'
                . '     	<span id="btnAddMarker" class="btt-green-btn" versionjl="'.JVERSION.'" baseurl=" '.JURI::root().'">' . JText::_('MOD_BT_GOOGLE_MAP_ADD_MARKER_LABEL') . '</span>'
                . '     	<span id="btnDeleteAll" class="btt-red-btn">' . JText::_('MOD_BT_GOOGLE_MAP_DELETE_ALL_MARKER_LABEL') . '</span>'
                . '		</li>'
                . '		<li id="marker-form">&nbsp;</li>'
                . '		<li >'
                . '			<div class="btg-head">' . JText::_('MARKER_LIST') . '</div>'
                . '			<input id="btg-hidden" type="hidden" name="' . $this->name . '" value="' . $this->value . '" />'
                . '  		<ul id="btg-markers-container" class="clearfix adminformlist"></ul>'
                . '		</li>'
                . '</ul>';
                
      /**
       * for media field
       * 
       */
      # create script
   		$assetField = $this->element['asset_field'] ? (string) $this->element['asset_field'] : 'asset_id';
		$authorField = $this->element['created_by_field'] ? (string) $this->element['created_by_field'] : 'created_by';
		$asset = $this->form->getValue($assetField) ? $this->form->getValue($assetField) : (string) $this->element['asset_id'];
		if ($asset == '')
		{
			$asset = JRequest::getCmd('option');
		}

		if (!self::$initialisedMedia)
		{

			// Load the modal behavior script.
			JHtml::_('behavior.modal');

			// Build the script.
			$script = array();
			$script[] = '	function jInsertFieldValue(value, id) {';
			$script[] = '		var old_value = document.id(id).value;';
			$script[] = '		if (old_value != value) {';
			$script[] = '			var elem = document.id(id);';
			$script[] = '			elem.value = value;';
			$script[] = '			elem.fireEvent("change");';
			$script[] = '			if (typeof(elem.onchange) === "function") {';
			$script[] = '				elem.onchange();';
			$script[] = '			}';
			$script[] = '			jMediaRefreshPreview(id);';
			$script[] = '		}';
			$script[] = '	}';

			$script[] = '	function jMediaRefreshPreview(id) {';
			$script[] = '		var value = document.id(id).value;';
			$script[] = '		var img = document.id(id + "_preview");';
			$script[] = '		if (img) {';
			$script[] = '			if (value) {';
			$script[] = '				img.src = "' . JURI::root() . '" + value;';
			$script[] = '				document.id(id + "_preview_empty").setStyle("display", "none");';
			$script[] = '				document.id(id + "_preview_img").setStyle("display", "");';
			$script[] = '			} else { ';
			$script[] = '				img.src = ""';
			$script[] = '				document.id(id + "_preview_empty").setStyle("display", "");';
			$script[] = '				document.id(id + "_preview_img").setStyle("display", "none");';
			$script[] = '			} ';
			$script[] = '		} ';
			$script[] = '	}';

			$script[] = '	function jMediaRefreshPreviewTip(tip)';
			$script[] = '	{';
			$script[] = '		tip.setStyle("display", "block");';
			$script[] = '		var img = tip.getElement("img.media-preview");';
			$script[] = '		var id = img.getProperty("id");';
			$script[] = '		id = id.substring(0, id.length - "_preview".length);';
			$script[] = '		jMediaRefreshPreview(id);';
			$script[] = '	}';

			// Add the script to the document head.
			JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

			self::$initialisedMedia = true;
		}
		$htmlMediaCode = array('icon'=>'');
		foreach ($htmlMediaCode as $key => $value){
			#create media html code
			// Initialize variables.
			$htmlMedia = array();
			$attr = '';
	
			// Initialize some field attributes.
			$attr .= 'class="media-marker"';
			//$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
	
			// Initialize JavaScript field attributes.
			$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
	
			// The text field.
			$htmlMedia[]= '<label class="hasTip" title="'.JText::_('BT_GOOGLE_MAP_ICON_MARKER_DESC').'" for="btg-'.$key.'" aria-invalid="false">'.JText::_('BT_GOOGLE_MAP_ICON_MARKER_LABEL').'</label>';
			$htmlMedia[] = '<div class="fltlft">';
			$htmlMedia[] = '	<input type="text" name="media-google-map['.$key.']" id="btg-'.$key.'" value="" readonly="readonly" ' . $attr . ' />';
			$htmlMedia[] = '</div>';
	
			// The button.
			$htmlMedia[] = '<div class="button2-left">';
			$htmlMedia[] = '	<div class="blank">';
			$htmlMedia[] = '		<a class="modal"'
									 .'title="' . JText::_('JLIB_FORM_BUTTON_SELECT') . '"' 
									 . ' href="index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=' . $asset . '&amp;fieldid=btg-'.$key. '&amp;folder=' . '"'
									 . ' rel="{handler: &apos;iframe&apos;, size: {x: 800, y: 500}}">';
			$htmlMedia[] = JText::_('JLIB_FORM_BUTTON_SELECT') . '</a>';
			$htmlMedia[] = '	</div>';
			$htmlMedia[] = '</div>';
	
			$htmlMedia[] = '<div class="button2-left">';
			$htmlMedia[] = '	<div class="blank">';
			$htmlMedia[] = '		<a title="' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '"' . ' href="#" onclick="';
			$htmlMedia[] = 'jInsertFieldValue(&apos;&apos;, &apos;btg-' . $key . '&apos;);';
			$htmlMedia[] = 'return false;';
			$htmlMedia[] = '">';
			$htmlMedia[] = JText::_('JLIB_FORM_BUTTON_CLEAR') . '</a>';
			$htmlMedia[] = '	</div>';
			$htmlMedia[] = '</div>';
	
 			$preview = '';
			$showPreview = true;
			$showAsTooltip = false;
			switch ($preview)
			{
				case 'false':
				case 'none':
					$showPreview = false;
					break;
				case 'true':
				case 'show':
					break;
				case 'tooltip':
				default:
					$showAsTooltip = true;
					$options = array(
						'onShow' => 'jMediaRefreshPreviewTip',
					);
					JHtml::_('behavior.tooltip', '.hasTipPreview', $options);
					break;
			}
	
			if ($showPreview)
			{
				$src = '';
				$attr = array(
					'id' => 'btg-'.$key . '_preview',
					'class' => 'media-preview',
					'style' => 'max-width:200px; max-height:30px;'
				);
				$img = JHtml::image($src, JText::_('JLIB_FORM_MEDIA_PREVIEW_ALT'), $attr);
				$previewImg = '<div id="btg-' . $key . '_preview_img"' . ($src ? '' : ' style="display:none"') . '>' . $img . '</div>';
				$previewImgEmpty = '<div id="btg-' .$key  . '_preview_empty"' . ($src ? ' style="display:none"' : '') . '>'
					. JText::_('JLIB_FORM_MEDIA_PREVIEW_EMPTY') . '</div>';
	
				$htmlMedia[] = '<div class="media-preview fltlft">';
				
					$htmlMedia[] = ' ' . $previewImgEmpty;
					$htmlMedia[] = ' ' . $previewImg;
				
				$htmlMedia[] = '</div>';
			}
	
			$htmlMediaCode[$key] = implode("'\n+'", $htmlMedia);    
		}      
      #end setup to create media field
      ?>
       <script type="text/javascript">
        	var dialogHtml =  '<div id="btg-form-marker"><div class= "btg-messages" id="btg-messages" class="clearfix"></div>'
                + '<ul class="btg-dialog-container">'
                + '     <li class="marker-title">'
                + '         <label for="btg-marker-title" title="<?php echo JText::_('MOD_BT_GOOGLE_MAP_MARKER_TITLE_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_MARKER_TITLE_LABEL') ?></label>'
                + '         <input type="text" name="btg-marker-title" id="btg-marker-title"/>'
				+ '			<div style="clear:both;"></div>'
                + '     </li>'
                + '     <li>'
                + '         <label for="btg-maker-type" class="hasTip" title="<?php echo JText::_('MOD_BT_GOOGLE_MAP_TYPE_MARKER_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_TYPE_MARKER_LABEL') ?></label>'
                + '         <select  name="btg-marker-type" id="btg-marker-type">'
                + '             <option value="address">Address</option>'
                + '             <option value="coordinate">Coordinate</option>'
                + '         </select>'
				+ '			<div style="clear:both;"></div>'
                + '     </li>'
                + '     <li class="btg-optional address">'
                + '     <label for="btg-marker-address" title ="<?php echo JText::_('MOD_BT_GOOGLE_MAP_ADDRESS_MARKER_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_ADDRESS_MARKER_LABEL') ?></label>'
                + '         <input type="text" name="btg-marker-address" id="btg-marker-address"/>'
				+ '			<div style="clear:both;"></div>'
                + '     </li>'
                + '     <li class="btg-optional coordinate">'
                + '     <label for="btg-marker-coordinate" title ="<?php echo JText::_('MOD_BT_GOOGLE_MAP_COORDINATE_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_COORDINATE_LABEL') ?></label>'
                + '         <input type="text" name="btg-marker-coordinate" id="btg-marker-coordinate"/>'
				+ '			<div style="clear:both;"></div>'
                + '     </li>'
                + '     <li class="btg-icon-marker">'
                + '		<?php echo $htmlMediaCode['icon'];?> '
				+ '			<div style="clear:both;"></div>'
                + '     </li>'
                + '     <li>'
                + '         <label for="btg-maker-showInfoOnload"  title="<?php echo JText::_('MOD_BT_GOOGLE_MAP_SHOWINFO_ONLOAD_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_SHOWINFO_ONLOAD_LABEL') ?></label>'
                + '         <select  name="btg-marker-showInfoOnload" class="bt_switch" id="btg-marker-showInfoOnload">'
                + '             <option value="1">Yes</option>'
                + '             <option value="0">No</option>'
                + '         </select>'
				+ '			<div style="clear:both;"></div>'
                + '     </li>'
                + '     <li class="btg-infowindow">'
                + '     <label for="btg-infowindow" title ="<?php echo JText::_('MOD_BT_GOOGLE_MAP_INFO_WINDOW_DESC') ?>"><?php echo JText::_('MOD_BT_GOOGLE_MAP_INFO_WINDOW_LABEL') ?></label>'
                + '         <textarea type="text" name="btg-infowindow" id="btg-infowindow"></textarea>'
				+ '			<div style="clear:both;"></div>'
                + '     </li>'
                + '     <li id="btg-submit-btn">'
                + '         <span id="btnCreateMarker" class="btg-green-btn btg-panel-btn"><?php echo JText::_('MOD_BT_GOOGLE_MAP_BUTTON_CREATE_MARKER') ?></span>'
                + '         <span id="btnUpdateMarker" class="btg-green-btn btg-panel-btn"><?php echo JText::_('MOD_BT_GOOGLE_MAP_BUTTON_UPDATE_MARKER') ?></span>'
                + '         <span id="btnCancel" class="btg-red-btn btg-panel-btn"><?php echo JText::_('MOD_BT_GOOGLE_MAP_BUTTON_CANCEL') ?></span>'
                + '     </li>'
                + '</ul>'
                + '<div style="clear:both;"></div>'
                + '</div>'
				+ '<script type="text/javascript">'
                + 'window.addEvent("domready", function() {'
		    	+ '		SqueezeBox.initialize({});'
    			+ '		SqueezeBox.assign($$("a.modal"), {'
    			+ '			parse: "rel"'
    			+ '		});'
    			+ '});'
    			+ '<\/script>';
				
                
                var jQ = jQuery.noConflict();
                
                jQ(document).ready(function(){
                      
                    //init form preview
                    var markerList = new BT.MarkerList({
                        liveURL : '<?php echo JURI::root() . 'modules/mod_bt_googlemaps' ?>',
                        warningText: {
                            tabTitleRequired: '<?php echo JText::_('MARKER_TITLE_REQUIRED') ?>',
                            tabValueRequired: '<?php echo JText::_('MARKER_POSITION_REQUIRED') ?>',
                            addMarkerSuccess: '<?php echo JText::_('ADD_MARKER_SUCCESS') ?>',
                            updateMarkerSuccess: '<?php echo JText::_('UPDATE_MARKER_SUCCESS') ?>',
                            confirmDeleteAll: '<?php echo JText::_('CONFIRM_DELETE_ALL') ?>',
                            confirmDelete: '<?php echo JText::_('CONFIRM_DELETE') ?>',
                            deleteAllSuccess: '<?php echo JText::_('DELETE_ALL_SUCCESS') ?>'
                        },
                        dialogTemplate: dialogHtml,
                        encodedItems : '<?php echo $this->value ?>',
                        moduleID: '<?php echo $moduleID ?>',
                        container: 'btg-markers-container',
                        messageContainer: 'btg-warning',
                        btnCreateID: '#btnCreateMarker',
                        btnUpdateID: '#btnUpdateMarker',
                        btnCancelID: '#btnCancel'

                    });
                                    
                    /**
                     * Open marker options when click add marker
                     * 
                     */
                    jQ("#btnAddMarker").click(function(){
                        markerList.openDialog();
                        
                        return false;
                    });
                    /**
                     * Close all options when click cancel
                     */

                    //remove all
                    jQ('#btnDeleteAll').click(function(){
                        if(jQ('#btg-markers-container li').length > 0 )
                            markerList.removeAll();
                        return false;
                    });
                });
           


      </script>          
      <?php   
        return $html;
    }
}