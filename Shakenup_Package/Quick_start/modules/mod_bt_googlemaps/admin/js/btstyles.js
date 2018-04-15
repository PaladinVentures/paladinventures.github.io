jQ = jQuery.noConflict();
var BT=BT||{};
(function(){
    BT.StyleList = new Class({
        Implements:[Options,Events],
        options:{},
        initialize:function(options){
            
            this.setOptions(options);
            var styles =JSON.decode(BT.Base64.base64Decode(this.options.encodedItems));
            var self=this;
            this.sortables=new Sortables(this.options.container,{
                clone:true,
                revert:true,
                opacity:0.3,
                onStart:function(element, clone){
                    clone.setStyle("z-index",999)
                }
            });
            if(styles!=null){
                styles.each(function(style){
                    self.add(style)
                })
            }
            var submit =null;
            if(typeof document.adminForm.onsubmit=="function"){
                submit = document.adminForm.onsubmit
            }
            document.adminForm.onsubmit=function(){
                var styles=[];
                var i=0;
                $$("#btg-styles-container li").each(function(li){
                    var style=li.retrieve("data");
                    if(style!=null){
                        styles[i]=style;
                        i++
                    }
                });
                $("btg-style-hidden").set("value",BT.Base64.base64Encode(JSON.encode(styles)));
                if(submit!=null){
                    submit()
                }
            }
        },
        /**
         * This method will show form to create style or update style
         * 
         * */
        
        openDialog: function(edit, li, style){
            var self = this;
            var dialog = new Element('<div>', {
                id: "btg-style-dialog", 
                html: this.options.dialogTemplate
            })
           if(jQ("#btg-style-dialog").length >0){
        	   jQ('#btg-style-dialog').remove();
           }
            jQ("#style-form").append(dialog);
            
            // this code will setup show/ hide form, bind event when create/update style
            jQ('#btg-feature-type, #btg-style-element-type,#btg-style-visibility').chosen();
            // call color picker
            jQ('.bt_color').ColorPicker({
        		color: '#0000ff',
        		onShow: function (colpkr) {
        			jQ(colpkr).fadeIn(500);
        			return false;
        		},
        		onHide: function (colpkr) {
        			jQ(colpkr).fadeOut(500);
        			return false;
        		},
        		onSubmit: function(hsb, hex, rgb, el) {
        			jQ(el).val("#"+hex);
        			jQ(el).css('background',jQuery(el).val())
        			jQ(el).ColorPickerHide();
        		},
        		onBeforeShow: function () {
        			jQ(this).ColorPickerSetColor(this.value);
        		}
        	})
        	.bind('keyup', function(){
        		jQ(this).ColorPickerSetColor(this.value);
        	});
            
            //bind event for cancel
            jQ(self.options.btnCancelID).bind('click', function(){
            	 jQ('#btg-style-dialog').remove();
            	 jQ("#btnAddStyle").removeAttr("disabled");
            	 jQ("#btnAddStyle").removeClass("disable-btn");
                return false; 
            });
           
            if(!edit){
            	if( parseInt(jQ("#btnAddStyle").attr('versionjl'),10) == 3){
            		jQ("#btnAddStyle").attr("disabled","disabled");
            		jQ("#btnAddStyle").addClass("disable-btn");
            	}
                jQ(self.options.btnCreateID).show();
                jQ(self.options.btnUpdateID).hide();
                jQ(self.options.btnCreateID).unbind('click').bind('click', function(){
                    jQ('#btg-style-messages').html('');
                
                //create style
                var msg = '';
                var style = new Object();
                style.featureType 		= jQ('#btg-feature-type').val();
                style.elementType 		= jQ('#btg-style-element-type').val();
                style.visibility		= jQ('#btg-style-visibility').val();
                style.invertLightness 	= jQ('#btg-style-invert-lightness').val();
                style.mapColor 			= jQ('#btg-style-map-color').val();
                style.weight 			= jQ('#btg-style-weight').val();
                style.hue 				= jQ('#btg-style-hue').val();
                style.saturation 		= jQ('#btg-style-saturation').val();
                style.lightness 		= jQ('#btg-style-lightness').val();
                style.gamma 			= jQ('#btg-style-gamma').val();
                
                /* valid value of style element : 
                    weight (0.1->8.0),
                    saturation(-100,100),
                    lightness(-100,100), 
                    gamma(9.91,0.01)
                */
                var weightRegExp = /^[0-8]\.\d$/;
                var gammaRegExp = /^[0-9]\.[0-9][0-9]$/;
                if(!style.weight==''&& !weightRegExp.test(style.weight)){
                    msg= 'Weight of style not valid! \nPlease check it and try again!';
                }
                if(!style.saturation=='' && parseInt(style.saturation)<= -100 && parseInt(style.saturation)>= 100){
                	msg= 'Saturation of style not valid! \nPlease check it and try again!';
                }
                if(!style.lightness == '' && parseInt(style.lightness)<= -100 && parseInt(style.lightness)>= 100){
                	msg= 'Lightness of style not valid! \nPlease check it and try again!';
                }
                if(!style.gamma == '' && !gammaRegExp.test(style.gamma) && parseFloat(style.gamma)<= 0.01 && parseFloat(style.gamma)>= 9.91){
                    msg= 'Gamma of style not valid! \nPlease check it and try again!';
                }
                
                if(msg != ''){
                    alert(msg);
                    return false;
                }
                
                self.add(style);
                //khoi tao lai form
                jQ('#btg-style-messages').html('').append(jQ('<div>').addClass('bt-message success').html(self.options.warningText.addStyleSuccess));
              
            	jQ('#btg-style-dialog').remove();
            	jQ("#btnAddStyle").removeAttr("disabled");
            	jQ("#btnAddStyle").removeClass("disable-btn");
                 
                return false;
                });
            }else{
            	
            	if(parseInt(jQ("#btnAddStyle").attr('versionjl'),10) == 3){
            		jQ("#btnAddStyle").attr("disabled","disabled");
            		jQ("#btnAddStyle").addClass("disable-btn");
            	}
            	
                if(style == null){
                	 jQ("#btnAddStyle").removeAttr("disabled");
                	 jQ("#btnAddStyle").removeClass("disable-btn");
                    return false
                }
                
               // scrolltoTop = jQ("#btnAddStyle").offset().top ;
                //jQ('html,body').animate({scrollTop: scrolltoTop},'fast');
                
                jQ(self.options.btnUpdateID).show();
                jQ(self.options.btnCreateID).hide();
                jQ(self.options.btnUpdateID).unbind('click');
                
                jQ('#btg-feature-type').val(style.featureType).trigger('liszt:updated');
                jQ('#btg-style-element-type').val(style.elementType).trigger('liszt:updated');
                jQ('#btg-style-visibility').val(style.visibility).trigger('liszt:updated');
                jQ('#btg-style-invert-lightness').val(style.invertLightness);
                jQ('#btg-style-map-color').val(style.mapColor);
                jQ('#btg-style-weight').val(style.weight);
                jQ('#btg-style-hue').val(style.hue);
                jQ('#btg-style-saturation').val(style.saturation);
                jQ('#btg-style-lightness').val(style.lightness);
                jQ('#btg-style-gamma').val(style.gamma);
                
                jQ(self.options.btnUpdateID).click(function(){
                    self.update(li, style);
                    return false;
                });
            }
            $$('#btg-form-style .bt_switch').each(function(el)
            {
                var options = el.getElements('option');		
                if(options.length==2){
	
                    el.setStyle('display','none');
                    var value = new Array();
                    value[0] = options[0].value;
                    value[1] = options[1].value;
		
                    var text = new Array();
                    text[0] = options[0].text.replace(" ","-").toLowerCase().trim();
                    text[1] = options[1].text.replace(" ","-").toLowerCase().trim();
		
                    var switchClass = (el.value == value[0]) ? text[0] : text[1];
	
                    var switcher = new Element('div',{
                        'class' : 'switcher-'+switchClass
                    });

                    switcher.inject(el, 'after');
                    switcher.addEvent("click", function(){
                        if(el.value == value[1]){
                            switcher.setProperty('class','switcher-'+text[0]);
                            el.value = value[0];
            
                        } else {
                            switcher.setProperty('class','switcher-'+text[1]);
                            el.value = value[1];
                        }
                    });
                }
            });
            //End code show/hid, bind event,
            return false;
        },
        add:function(style){
            var liHTML ='<div class="div-style">'
            + '				<div class="style-feature-type style-title-label"><span class="label">Feature Type : </span><span class="value">'+style.featureType+'</span></div>'
            + '				<div class="style-element-type style-title-label"><span class="label">Element Type : </span><span class="value">'+style.elementType+'</span></div>'
            + '				<div class="style-stylers-label style-title-label">Stylers :</div>'
            + '				<div class="style-stylers style-title-label">'
            + '					<div class="style-stylers-element visibility style-title-label"><span class="label">Visibility : </span><span class="value">'+style.visibility+'</span></div>'
            + '					<div class="style-stylers-element invert-lightness style-title-label"><span class="label">Invert Lightness : </span><span class="value">'+ style.invertLightness +'</span></div>'
            + '					<div class="style-stylers-element color style-title-label"><span class="label">Color : </span><span class="value">'+style.mapColor+'</span></div>'
            + '					<div class="style-stylers-element weight style-title-label"><span class="label">Weight : </span><span class="value">'+style.weight+'</span></div>'
            + '					<div class="style-stylers-element hue style-title-label"><span class="label">Hue : </span><span class="value">'+style.hue+'</span></div>'
            + '					<div class="style-stylers-element saturation style-title-label"><span class="label">Saturation : </span><span class="value">'+style.saturation+'</span></div>'
            + '					<div class="style-stylers-element lightness style-title-label"><span class="label">Lightness : </span><span class="value">'+style.lightness+'</span></div>'
            + '					<div class="style-stylers-element gamma style-title-label"><span class="label">Gamma : </span><span class="value">'+style.gamma+'</span></div>'
            + '				</div>'
            + '				<div class="edit-remove-link"><a href="javascript:void(0)" class="edit">Edit</a><a href="javascript:void(0)" class="remove">Remove</a><div class="clear"></div></div>'
            + '			</div>';
            
            

            
            var li = new Element("li",{
                'class' : 'style-display',
                html: liHTML
            });
            var self=this;
            li.getElement(".edit").addEvent("click",function(){
                self.edit(li)
            });
            li.getElement(".remove").addEvent("click",function(){
                self.remove(li)
            });
            
            li.store("data",style);
            var container=$(this.options.container);
            li.set("opacity",0);
            container.grab(li);
            li.fade("in");
            this.sortables.addItems(li);
        },
        edit:function(li){
            var style = li.retrieve("data");
            if(style!= null){
                this.openDialog(true, li, style);
                
            }
        },
        update: function(li, style){
            var msg = '';
           
            var featureType 		= jQ('#btg-feature-type').val();
            var elementType 		= jQ('#btg-style-element-type').val();
            var visibility			= jQ('#btg-style-visibility').val();
            var invertLightness 	= jQ('#btg-style-invert-lightness').val();
            var mapColor 			= jQ('#btg-style-map-color').val();
            var weight 				= jQ('#btg-style-weight').val();
            var hue 				= jQ('#btg-style-hue').val();
            var saturation 			= jQ('#btg-style-saturation').val();
            var lightness 			= jQ('#btg-style-lightness').val();
            var gamma 				= jQ('#btg-style-gamma').val();
            
            var weightRegExp = /^[0-8]\.\d$/;
            var gammaRegExp = /^[0-9]\.?[0-9]?[0-9]?$/;
            if(!weight==''&& !weightRegExp.test(weight)){
                msg= 'Weight of style not valid! \n Please check it and try again!' + weight;
            }
            if(!saturation=='' &&( parseInt(saturation)<= -100 || parseInt(saturation)>= 100) || isNaN(saturation)){
            	msg= 'Saturation of style not valid! \n Please check it and try again!';
            }
            if(!lightness=='' && (parseInt(lightness)<= -100 || parseInt(lightness)>= 100) || isNaN(lightness)){
            	msg= 'Lightness of style not valid! \n Please check it and try again!';
            }
            if(!gamma == '' && !gammaRegExp.test(gamma)|| parseFloat(gamma)<= 0.01 || parseFloat(gamma)>= 9.91){
                msg= 'Gamma of style not valid! \n Please check it and try again!';
            }
            
            if(msg != ''){
                alert(msg);
                return false;
            }
            //updateMarker
            style.featureType = featureType;
            style.elementType = elementType;
            style.visibility = visibility;
            style.invertLightness = invertLightness;
            style.mapColor = mapColor;
            style.weight = weight;
            style.hue = hue;
            style.saturation = saturation;
            style.lightness = lightness;
            style.gamma = gamma;
            li.store('data', style);
            //update li
            jQ(li).find('.style-feature-type .value').html(featureType);
            jQ(li).find('.style-element-type .value').html(elementType);
            jQ(li).find('.visibility .value').html(visibility);
            jQ(li).find('.invert-lightness .value').html(invertLightness);
            jQ(li).find('.color .value').html(mapColor);
            jQ(li).find('.weight .value').html(weight);
            jQ(li).find('.hue .value').html(hue);
            jQ(li).find('.saturation .value').html(saturation);
            jQ(li).find('.lightness .value').html(lightness);
            jQ(li).find('.gamma .value').html(gamma);
       
            jQ('#btg-style-messages').html('').append(jQ('<div>').addClass('bt-message success').html(this.options.warningText.updateMarkerSuccess));
	
	    	jQ('#btg-style-dialog').remove();
	        jQ("#btnAddStyle").removeAttr("disabled");
	        jQ("#btnAddStyle").removeClass("disable-btn");
                  
            return false;
            
        },
        remove:function(li){
            if(confirm(this.options.warningText.confirmDelete)){
                var b=new Fx.Morph(li);
                b.start({
                    height:0,
                    opacity:0
                }).chain(function(){
                    li.dispose()
                });
            }
        //this.showMessage("btt-messages","<b>Delete style successful</b>")
        },
        removeAll: function(){
            if(confirm(this.options.warningText.confirmDeleteAll)){
                var a = $(this.options.container);
                var b = a.getElements("li");
                b.each(function(c){
                    var d=new Fx.Morph(c);
                    d.start({
                        width:0,
                        height:0,
                        opacity:0
                    }).chain(function(){
                        c.dispose()
                    });
                });
                jQ('#btg-styles-container').show();
                //this.showMessage(this.options.warningText.deleteAllSuccess);
                setTimeout(function(){
                    jQ('#btg-styles-container').slideUp(500)
                }, 1500);
            }
            return false;
        },
        showMessage:function(messageText){
            
            var messageContainer = $(this.options.messageContainer);
            var message = new Element("div", {
                'class': 'bt-message'
            });
            message.set("html",messageText);
            message.set("opacity",0);
            messageContainer.grab(message,"top");
            var b=new Fx.Morph(message,{
                link:"chain"
            });
            b.start({
                opacity:1,
                visibility:'visible'
            });
            this.removeLog();
        },
        removeLog:function(){
            $(this.options.messageContainer).getElements("div.bt-message").each(function(d,b,c){
                setTimeout(function(){
                    var e=new Fx.Morph(d,{
                        link:"chain"
                    });
                    e.start({
                        height:0,
                        opacity:0
                    }).chain(function(){
                        d.dispose()
                    })
                },1000)
            })
        },
        htmlEntities: function(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }       
    })
})();