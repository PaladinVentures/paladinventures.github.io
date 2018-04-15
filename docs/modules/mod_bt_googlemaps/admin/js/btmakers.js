jQ = jQuery.noConflict();
var BT=BT||{};
(function(){
    BT.MarkerList = new Class({
        Implements:[Options,Events],
        options:{},
        initialize:function(options){
            
            this.setOptions(options);
            var markers =JSON.decode(BT.Base64.base64Decode(this.options.encodedItems));
            var self=this;
            this.sortables=new Sortables(this.options.container,{
                clone:true,
                revert:true,
                opacity:0.3,
                onStart:function(element, clone){
                    clone.setStyle("z-index",999)
                }
            });
            if(markers!=null){
                markers.each(function(marker){
                    self.add(marker)
                })
            }
            var submit =null;
            if(typeof document.adminForm.onsubmit=="function"){
                submit = document.adminForm.onsubmit
            }
            document.adminForm.onsubmit=function(){
                var markers=[];
                var i=0;
                $$("#btg-markers-container li").each(function(li){
                    var marker=li.retrieve("data");
                    if(marker!=null){
                        markers[i]=marker;
                        i++
                    }
                });
                $("btg-hidden").set("value",BT.Base64.base64Encode(JSON.encode(markers)));
                if(submit!=null){
                    submit()
                }
            }
        },
        /**
         * This method will show form to create marker or update marker
         * 
         * */
        
        openDialog: function(edit, li, marker){
            var self = this;
            var dialog = new Element('<div>', {
                id: "btg-dialog", 
                html: this.options.dialogTemplate
            })
           if(jQ("#btg-dialog").length >0){
        	   jQ('#btg-dialog').remove();
           }
            jQ("#marker-form").append(dialog);
         // this code will setup show/ hide form, bind event when create/update marker
            jQ('#btg-marker-type').chosen();
            jQ('.btg-optional').hide();
            jQ("#btg-marker-type").change(function(){
                jQ('.btg-optional').hide();
                jQ('.btg-optional.' + jQ(this).val().toLowerCase().replace(' ', '_')).show();
                
            });
            
            //bind event for cancel
            jQ(self.options.btnCancelID).bind('click', function(){
            	 jQ('#btg-dialog').remove();
            	 jQ("#btnAddMarker").removeAttr("disabled");
            	 jQ("#btnAddmarker").removeClass("disable-btn");
                return false; 
            });
           
            if(!edit){
            	if( parseInt(jQ("#btnAddMarker").attr('versionjl'),10) == 3){
            		jQ("#btnAddMarker").attr("disabled","disabled");
            		jQ("#btnAddMarker").addClass("disable-btn");
            	}
                jQ('.btg-optional.' + jQ("#btg-marker-type").val().toLowerCase().replace(' ', '_')).show();
                jQ(self.options.btnCreateID).show();
                jQ(self.options.btnUpdateID).hide();
                jQ(self.options.btnCreateID).unbind('click').bind('click', function(){
                    jQ('#btg-messages').html('');
                
                    //create marker
                    var msg = '';
                    var type = jQ("#btg-marker-type").val().toLowerCase().replace(' ', '_');
                    var marker = new Object();
                    marker.markerTitle = jQ('#btg-marker-title').val();
                    marker.markerType = jQ('#btg-marker-type').val();
                    marker.markerValue = jQ('#btg-marker-' + type).val();
                    marker.markerIcon = jQ('#btg-icon').val();
                    marker.markerShowInfoWindow = jQ('#btg-marker-showInfoOnload').val(); 
                    marker.markerInfoWindow = jQ('#btg-infowindow').val();
                    
                    if(marker.markerTitle == ''){
                        msg= 'Please enter title of marker';
                    }
                    if(marker.markerValue == ''){
                        msg= 'Please enter value of marker';
                    }
                    if(msg != ''){
                        alert(msg);
                        return false;
                    }
                    self.add(marker);
                    //khoi tao lai form
                    jQ('#btg-messages').html('').append(jQ('<div>').addClass('bt-message success').html(self.options.warningText.addMarkerSuccess));
                  
                	jQ('#btg-dialog').remove();
                	jQ("#btnAddMarker").removeAttr("disabled");
                	jQ("#btnAddMarker").removeClass("disable-btn");
                     
                    return false;
                });
            }else{
            	
            	if(parseInt(jQ("#btnAddMarker").attr('versionjl'),10) == 3){
            		jQ("#btnAddMarker").attr("disabled","disabled");
            		jQ("#btnAddMarker").addClass("disable-btn");
            	}
            	
                if(marker == null){
                	 jQ("#btnAddMarker").removeAttr("disabled");
                	 jQ("#btnAddMarker").removeClass("disable-btn");
                    return false
                }
                
               // scrolltoTop = jQ("#btnAddMarker").offset().top ;
               // jQ('html,body').animate({scrollTop: scrolltoTop},'fast');
				
                jQ(self.options.btnUpdateID).show();
                jQ(self.options.btnCreateID).hide();
                jQ(self.options.btnUpdateID).unbind('click');
                jQ('#btg-marker-type').val(marker.markerType).trigger('liszt:updated');
                
				jQ('.btg-optional').hide();
                jQ('.btg-optional.' + marker.markerType).show();
				
                jQ('#btg-marker-title').val(marker.markerTitle);
                jQ('.btg-optional.' + marker.markerType.toLowerCase().replace(' ', '_')).show();
                jQ('#btg-marker-' + marker.markerType.toLowerCase()).val(marker.markerValue).trigger('liszt:updated');
				if(!marker.markerIcon == ''){
                jQ('#btg-icon').val(marker.markerIcon);
					jQ('#btg-icon_preview').attr('src','../'+marker.markerIcon);
					jQ('#btg-icon_preview_empty').hide();
					jQ('#btg-icon_preview_img').show();
				}
                jQ('#btg-marker-showInfoOnload').val(marker.markerShowInfoWindow)
                jQ('#btg-infowindow').val(marker.markerInfoWindow);
                
                
                
                jQ(self.options.btnUpdateID).click(function(){
                    self.update(li, marker);
                    return false;
                });
            }
            $$('#marker-form .bt_switch').each(function(el)
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
        add:function(marker){
            var liHTML = '<div class="div-marker">'
            + 	'<div class="marker_title marker-title-label">'
            +		'<span class="value">'+marker.markerTitle+'</span>'
            + 	'</div>'
            +	'<div class="marker_type marker-title-label-hide">'
            +		'<span class="label">Type of marker : </span>'
            +		'<span class="value">'+marker.markerType +'</span>' 
            +	'</div>' 
            +	'<div class="marker_value  marker-title-label-hide">'
            +		'<span class="label">Marker Position : </span>'
            +		'<span class="value">'+ marker.markerValue +'</span>'
            +	'</div>'
            +  	'<div class="marker-icon marker-title-label-hide">'
            +		'<span class="label">Icon : </span>'
            +		'<span class="value" style="display:none">' + marker.markerIcon +'</span>'
            +			'<img class="img-marker" src="../'+marker.markerIcon+'"/>'
            +	'</div>'
            +   '<div class="marker-show-infowindow marker-title-label-hide">'
            +		'<span class="label">Show Infowindow : </span>'
            +		'<span class="value">'+this.htmlEntities(marker.markerShowInfoWindow)+'</span>'
            +	'</div>'
            +   '<div class="marker-infowindow marker-title-label-hide">'
            +		'<span class="label">Infowindow : </span>'
            +		'<span class="value">'+this.htmlEntities(marker.markerInfoWindow)+'</span>'
            +	'</div>'
            +	'<div class="edit-remove-link"><a href="javascript:void(0)" class="edit">Edit</a><a href="javascript:void(0)" class="remove">Remove</a><div class="clear"></div></div>';
            
            

            
            var li = new Element("li",{
                'class' : 'marker-display',
                html: liHTML
            });
            var self=this;
            li.getElement(".edit").addEvent("click",function(){
                self.edit(li)
            });
            li.getElement(".remove").addEvent("click",function(){
                self.remove(li)
            });
            
            li.store("data",marker);
            var container=$(this.options.container);
            li.set("opacity",0);
            container.grab(li);
            li.fade("in");
            this.sortables.addItems(li);
        },
        edit:function(li){
            var marker = li.retrieve("data");
            if(marker!= null){
                this.openDialog(true, li, marker);
                
            }
        },
        update: function(li, marker){
            var msg = '';
            var title = jQ('#btg-marker-title').val();
            var type = jQ("#btg-marker-type").val();
            if(type=='address'){
            	var value = jQ('#btg-marker-address').val();	
            }else{
            	value = jQ('#btg-marker-coordinate').val();
            }
            icon = jQ('#btg-icon').val();
            infowindow = jQ('#btg-infowindow').val();
            showInfoWindow = jQ('#btg-marker-showInfoOnload').val();
            
            if(title == ''){
                msg = "Please enter title of marker";
            }else if(value==''){
            	msg = 'Please enter position of marker';
            }
            
            if(msg != ''){
                alert(msg);
                return false;
            }
            //updateMarker
            marker.markerTitle = title;
            marker.markerType = type;
            marker.markerValue = value;
            marker.markerIcon = icon;
            marker.markerShowInfoWindow = showInfoWindow;
            marker.markerInfoWindow = infowindow;
            li.store('data', marker);
            //update li
            jQ(li).find('.marker_title .value').html(title);
            jQ(li).find('.marker_type .value').html(type);
            jQ(li).find('.marker_value .value').html(value);
            jQ(li).find('.marker-icon .value').html(icon);
            jQ(li).find('.marker-icon img').attr('src',icon);
            jQ(li).find('.marker-show-infowindow .value').html(showInfoWindow);
            jQ(li).find('.marker-infowindow .value').html(this.htmlEntities(infowindow));
       
            jQ('#btg-messages').html('').append(jQ('<div>').addClass('bt-message success').html(this.options.warningText.updateMarkerSuccess));
	
	    	jQ('#btg-dialog').remove();
	        jQ("#btnAddMarker").removeAttr("disabled");
	        jQ("#btnAddMarker").removeClass("disable-btn");
                  
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
        //this.showMessage("btt-messages","<b>Delete marker successful</b>")
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
                jQ('#btg-optional-container').show();
                //this.showMessage(this.options.warningText.deleteAllSuccess);
                setTimeout(function(){
                    jQ('#btg-optional-container').slideUp(500)
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