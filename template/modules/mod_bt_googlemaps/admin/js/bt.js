jQuery.noConflict();

//var optionObject = [];
//var idEdit = -1;

window.addEvent("domready",function(){
	$$("#jform_params_asset-lbl").getParent().destroy();

	jQuery('#module-sliders li > .btn-group').each(function(){
		if(jQuery(this).find('input').length != 2 ) return;
		if(this.id.indexOf('advancedparams') ==0) return;
		el = jQuery(this).find('input:checked').val();
		if( el != '0' && el != '1' && el != 'false' && el != 'true' && el != 'no' && el != 'yes' ){
			return;
		}
		jQuery(this).hide();
		var group = this;


		var el = jQuery(group).find('input:checked');
		var switchClass ='';

		if(el.val()=='' || el.val()=='0' || el.val()=='no' || el.val()=='false'){
			switchClass = 'no';
		}else{
			switchClass = 'yes';
		}
		var switcher = new Element('div',{'class' : 'switcher-'+switchClass});
		switcher.inject(group, 'after');
		switcher.addEvent("click", function(){
			var el = jQuery(group).find('input:checked');
			if(el.val()=='' || el.val()=='0' || el.val()=='no' || el.val()=='false'){
				switcher.setProperty('class','switcher-yes');
			}else {
				switcher.setProperty('class','switcher-no');
			}
			jQuery(group).find('input:not(:checked)').attr('checked',true).trigger('click');
		});
	})
	jQuery('#jform_params_boxcss').parent().css("overflow","hidden");

	jQuery('.bt_color').ColorPicker({
		color: '#0000ff',
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onSubmit: function(hsb, hex, rgb, el) {
			jQuery(el).val("#"+hex);
			//jQuery(el).css('background',jQuery(el).val())
			jQuery(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			jQuery(this).ColorPickerSetColor(this.value);
		}
	})
	.bind('keyup', function(){
		jQuery(this).ColorPickerSetColor(this.value);
	});
	jQuery(".pane-sliders select").each(function(){
		if(this.id.indexOf('advancedparams') ==0) return;
		if(jQuery(this).is(":visible")) {
		jQuery(this).css("width",parseInt(jQuery(this).outerWidth(true))+50);
		jQuery(this).chosen()
		};
	})
	jQuery(".chzn-container").click(function(){
		jQuery(".panel .pane-slider,.panel .panelform").css("overflow","visible");
	})
	jQuery(".panel .title").click(function(){
		jQuery(".panel .pane-slider,.panel .panelform").css("overflow","hidden");
	})

	// show/hide weather option
	if(jQuery('input[name="jform[params][weather]"]:checked').val() == 1){
		jQuery('#jform_params_temperatureUnit').parent().show();
		jQuery('#jform_params_replace_marker_icon').parent().show();
		jQuery('#jform_params_display_weather_info').parent().show();
		jQuery('#jform_params_owm_api').parent().show();
	}else{
		jQuery('#jform_params_temperatureUnit').parent().hide();
		jQuery('#jform_params_replace_marker_icon').parent().hide();
		jQuery('#jform_params_display_weather_info').parent().hide();
		jQuery('#jform_params_owm_api').parent().hide();
	}
	jQuery("input[name='jform[params][weather]']").click(function(){
		if(jQuery('input[name="jform[params][weather]"]:checked').val() == 1){
			jQuery('#jform_params_temperatureUnit').parent().show();
			jQuery('#jform_params_replace_marker_icon').parent().show();
			jQuery('#jform_params_display_weather_info').parent().show();
			jQuery('#jform_params_owm_api').parent().show();
		}else{
			jQuery('#jform_params_temperatureUnit').parent().hide();
			jQuery('#jform_params_replace_marker_icon').parent().hide();
			jQuery('#jform_params_display_weather_info').parent().hide();
			jQuery('#jform_params_owm_api').parent().hide();
		}
	});
	
	jQuery("input[name='jform[params][enable_map_api]']").click(function(){
		if(jQuery('input[name="jform[params][enable_map_api]"]:checked').val() == 1){
			jQuery('#jform_params_map_api').parent().show();
		}else{
			jQuery('#jform_params_map_api').parent().hide();
		}
	});
	if(jQuery('input[name="jform[params][enable_map_api]"]:checked').val() == 1){
		jQuery('#jform_params_map_api').parent().show();
	}else{
		jQuery('#jform_params_map_api').parent().hide();
	}

	// show/hide input of map address.
	if(jQuery('input[name="jform[params][mapCenterType]"]:checked').val() == "address"){
		jQuery('#jform_params_mapCenterAddress').parent().show();
		jQuery('#jform_params_mapCenterCoordinate').parent().hide();
	}else{
		jQuery('#jform_params_mapCenterCoordinate').parent().show();
		jQuery('#jform_params_mapCenterAddress').parent().hide();
	}
	jQuery("input[name='jform[params][mapCenterType]']").click(function(){
		if(jQuery('input[name="jform[params][mapCenterType]"]:checked').val() == "address"){
			jQuery('#jform_params_mapCenterAddress').parent().show();
			jQuery('#jform_params_mapCenterCoordinate').parent().hide();
		}else{
			jQuery('#jform_params_mapCenterCoordinate').parent().show();
			jQuery('#jform_params_mapCenterAddress').parent().hide();
		}
	});

	// show/hide option of style map
	if(jQuery('input[name="jform[params][enable-style]"]:checked').val() == 0){
		jQuery("#jform_params_style_title").parent().hide();
		jQuery("#jform_params_createNewOrApplyDefaultStyle").parent().hide();
		jQuery("#btg-style-message").parent().hide();
	}else{
		jQuery("#jform_params_style_title").parent().show();
		jQuery("#jform_params_createNewOrApplyDefaultStyle").parent().show();
		jQuery("#btg-style-message").parent().show();
	}
	jQuery("input[name='jform[params][enable-style]']").click(function(){
		if(jQuery('input[name="jform[params][enable-style]"]:checked').val() == 0){
			jQuery("#jform_params_style_title").parent().hide();
			jQuery("#jform_params_createNewOrApplyDefaultStyle").parent().hide();
			jQuery("#btg-style-message").parent().hide();
		}else{
			jQuery("#jform_params_style_title").parent().show();
			jQuery("#jform_params_createNewOrApplyDefaultStyle").parent().show();
			jQuery("#btg-style-message").parent().show();
		}
	});


	// show/hide option of custom infowindow
	if(jQuery('input[name="jform[params][enable-custom-infobox]"]:checked').val() == 1){
		jQuery('.option-custom-infobox').parent().show();
		jQuery('#jform_params_closeBoxURL').parent().parent().show();
	}else{
		jQuery('.option-custom-infobox').parent().hide();
		jQuery('#jform_params_closeBoxURL').parent().parent().hide();
	}
	jQuery("input[name='jform[params][enable-custom-infobox]']").click(function(){
		if(jQuery('input[name="jform[params][enable-custom-infobox]"]:checked').val() == 1){
			jQuery('.option-custom-infobox').parent().show();
			jQuery('#jform_params_closeBoxURL').parent().parent().show();
		}else{
			jQuery('.option-custom-infobox').parent().hide();
			jQuery('#jform_params_closeBoxURL').parent().parent().hide();
		}
	});
})

