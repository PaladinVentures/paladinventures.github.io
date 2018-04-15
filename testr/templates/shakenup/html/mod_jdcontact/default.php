<?php

defined('_JEXEC') or die('Restricted access');

    $showdepartment  	     =        $params->get( 'showdepartment', '1' );
    $showsendcopy            =        $params->get( 'showsendcopy', '1' );
    $humantestpram           =        $params->get( 'humantestpram', '1' );
    $sales_address           =        $params->get( 'sales_address', 'sales@yourdomain.com' );
    $support_address         =        $params->get( 'support_address', 'support@yourdomain.com' );
    $billing_address         =        $params->get( 'billing_address', 'billing@yourdomain.com' );
    $backgroundcolor         =        $params->get( 'backgroundcolor', '#FFEFD5' );
    $wrp_width               =        $params->get( 'wrp_width', '320px' );
    $inputfield_width        =        $params->get( 'inputfield_width', '300px' );
    $inputfield_border       =        $params->get( 'inputfield_border', '#CCCCCC' );
    $result                  =        '';
    $name                    =        '';
    $email                   =        '';
    $phno                    =        '';
    $subject                 =        '';
    $msg                     =        '';
    $selfcopy                =        '';
    $sucs                    =        '';
    $varone                  =        rand(5, 15);
    $vartwo                  =        rand(5, 15);
    $sum_rand                =        $varone+$vartwo;

?>
    <link rel="stylesheet" href="modules/mod_jdcontact/tmpl/lib/contact.css" media="screen" />
    <script src="modules/mod_jdcontact/tmpl/lib/jquery-1.4.4.js"></script>
   
    <div id="contact-form" class="col-8 contact-item">
        <form name="contactform" id="form" method="post" action="<?php $_SERVER['PHP_SELF']?>">
            <?php if($showdepartment=='1') : ?>
              <label><?php echo JText::_('MOD_JDCONTACT_DEPARTMENT'); ?></label><br />
              <select style="width: <?php echo $inputfield_width; ?>;border:1px solid <?php echo $inputfield_border; ?>;" name="dept" class="text">
              	<option value="sales"><?php echo JText::_('MOD_JDCONTACT_SALES'); ?></option>
              	<option value="support"><?php echo JText::_('MOD_JDCONTACT_SUPPORT'); ?></option>
              	<option value="billing"><?php echo JText::_('MOD_JDCONTACT_BILLING'); ?></option>
              </select><br />
            <?php endif; ?>
           
           
             <input class="text form-control"  placeholder="Name" name="name" type="text" value="<?php echo $name; ?>" />
             <input class="text form-control" placeholder="Email" name="email" type="text" value="<?php echo $email; ?>" />
             <input class="text form-control" placeholder="Subject" name="subject" type="text" value="<?php echo $subject; ?>" />
             <textarea class="text form-control" placeholder="Message" rows="9" name="msg"><?php echo $msg; ?></textarea>
            
            
            <?php if($showsendcopy=='1') : ?>
            <div class="sendcopy col-12">
                <input class="col-1" type="checkbox" name="selfcopy" <?php if($selfcopy == "yes") echo "checked='checked'"; ?> value="yes" />
                <label class="col-11"><?php echo JText::_('MOD_JDCONTACT_SELFCOPY'); ?></label>
            </div>
            <?php endif; ?>
            
             <?php if($humantestpram=='1') : ?>
            <div class="col-12">
                <label for='message'><?php echo JText::_('MOD_JDCONTACT_HUMANTEST'); ?></label>
                <?php echo '<b>'.$varone.'+'.$vartwo.'=</b>'; ?>
                <input id="human_test" name="human_test" size="3" type="text" class="text" style="border:1px solid <?php echo $inputfield_border; ?>;"><br>
                <input type="hidden" id="sum_test" name="sum_test" value="<?php echo $sum_rand; ?>" />
            </div>
            <?php endif; ?>
            <input type="hidden" name="browser_check" value="false" />
            <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                <tr>
                    <td width="100%" style="float:left;" valign="top">
                     <p>
                        <input type="submit" class="load-more-button" name="submit" value="<?php echo JText::_('MOD_JDCONTACT_SUBMIT'); ?>" id="submit" />
                     </p>
                    </td>
                    <td width="100%" style="float:left;" valign="top" align="center">
                        <div id="result">
						<?php if($result) echo "<div class='alert alert-danger'>".$result."</div>"; ?></div>
                    </td>
                </tr>
            </table>
        </form>

        <script type="text/javascript">
	    document.contactform.browser_check.value = "true";
	    $("#submit").click(function(){
		$('#result').html('<img src="modules/mod_jdcontact/tmpl/images/loader.gif" class="loading-img" alt="loader image">').fadeIn();
		var input_data = $('#form').serialize();
				$.ajax({
				   type: "POST",
				   url:  "<?php echo "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>",
				   data: input_data,
				   success: function(msg){
					   $('.loading-img').remove(); //Removing the loader image because the validation is finished
					   $('<div class="alert alert-danger">').html(msg).appendTo('div#result').hide().fadeIn('slow'); //Appending the output of the php validation in the html div

                        if(msg=='<?php echo JText::_("MOD_JDCONTACT_SUCCESSMSG"); ?>'){
                          $('#form').each (function(){
                            this.reset();
                          });
                       }
				   }
				});
			return false;
	    });
	    </script>
    </div>