<?php
/**
 * @package      ITPSocialButtons
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined( "_JEXEC" ) or die;
?>
<div class="itp-socialbuttons-mod<?php echo $moduleclass_sfx;?>">
   <?php if($params->get('showTitle')){ ?>
   <h4><?php echo $params->get('title');?></h4>
   <?php }?>
   
   <div class="<?php echo $params->get('displayLines');?>">
        <div class="<?php echo $params->get('displayIcons');?>">
        <?php 
        if($params->get("displayDelicious")) {
          echo ItpSocialButtonsHelper::getDeliciousButton($title, $link, $stylePath);
        }
        if($params->get("displayDigg")) {
            echo ItpSocialButtonsHelper::getDiggButton($title, $link, $stylePath);
        }
        if($params->get("displayFacebook")) {
            echo ItpSocialButtonsHelper::getFacebookButton($title, $link, $stylePath);
        }
        if($params->get("displayGoogle")) {
            echo ItpSocialButtonsHelper::getGoogleButton($link, $stylePath);
        }
        if($params->get("displaySumbleUpon")) {
            echo ItpSocialButtonsHelper::getStumbleuponButton($title, $baseLink, $stylePath);
        }
        if($params->get("displayTechnorati")) {
            echo ItpSocialButtonsHelper::getTechnoratiButton($link, $stylePath);
        }
        if($params->get("displayTwitter")) {
            echo ItpSocialButtonsHelper::getTwitterButton($title, $link, $stylePath);
        }
        if($params->get("displayLinkedIn")) {
            echo ItpSocialButtonsHelper::getLinkedInButton($title, $link, $stylePath);
        }
        ?>
        <?php echo ItpSocialButtonsHelper::getExtraButtons($title, $link, $params); ?>
        </div>
   </div>
</div>
