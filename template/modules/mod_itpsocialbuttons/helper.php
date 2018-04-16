<?php
/**
 * @package      ITPSocialButtons
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

class ItpSocialButtonsHelper{
    
    /**
     * A method that make a long url to short url
     * 
     * @param string $link
     * @param JRegistry $params
     * @return string
     */
    public static function getShortUrl($link, $params){
        
        JLoader::register("ItpSocialButtonsModuleShortUrl", dirname(__FILE__).DIRECTORY_SEPARATOR."shorturl.php");

        $options = array(
            "login"     => $params->get("login"),
            "api_key"   => $params->get("apiKey"),
            "service"   => $params->get("shortUrlService"),
        );

        $shortLink = "";
        try {
            
            $shortUrl  = new ItpSocialButtonsModuleShortUrl($link,$options);
            $shortLink = $shortUrl->getUrl();
            
            // Get original link
            if(!$shortLink) {
                $shortLink = $link;
            } 
            
        } catch(Exception $e) {
            
            JLog::add($e->getMessage(), JLog::DEBUG);
            
            // Get original link
            if(!$shortLink) {
                $shortLink = $link;
            }
                
        }
        
        return $shortLink;
            
    }
    
    /**
     * Generate a code for the extra buttons. 
     * Is also replace indicators {URL} and {TITLE} with that of the article.
     * 
     * @param string $title Article Title
     * @param string $url   Article URL
     * @param JRegistry $params Plugin parameters
     * 
     * @return string
     */
    public static function getExtraButtons($title, $url, &$params) {
        
        $html  = "";
        // Extra buttons
        for($i=1; $i < 6;$i++) {
            $btnName = "ebuttons" . $i;
            $extraButton = $params->get($btnName, "");
            if(!empty($extraButton)) {
                
                // Parse ITPrism markup
                if(false !== strpos($extraButton, "<itp:email")) {
                    $matches     = array();
                    if(preg_match('/src="([^"]*)"/i', $extraButton, $matches)) {
                        $extraButton = self::sendToFriendIcon($matches[1], $url);
                    }
                }
                
                $extraButton = str_replace("{URL}", $url,$extraButton);
                $extraButton = str_replace("{TITLE}", $title,$extraButton);
                $html  .= $extraButton;
            }
        }
        
        return $html;
    }
    
	/**
     * 
     * Generate a link that displays a popup with e-mail form.
     * The form can be used to send page to your friends
     * 
     * @param string $imageSrc
     * @param string $link
     *
     * @return string
     */
    public static function sendToFriendIcon($imageSrc, $link) {
        
        JLoader::register("MailToHelper", JPATH_SITE . '/components/com_mailto/helpers/mailto.php');
        
        $link     = rawurldecode($link);
        
		$template = JFactory::getApplication()->getTemplate();
		$url	  = 'index.php?option=com_mailto&tmpl=component&template='.$template.'&link='.MailToHelper::addLink($link);

		$status   = 'width=400,height=350,menubar=yes,resizable=yes';

		$attribs  = array(
		    'title'   => JText::_('JGLOBAL_EMAIL'),
			'onclick' => "window.open(this.href,'win2','".$status."'); return false;"
		);

		$text   = '<img src="'.$imageSrc.'" alt="'. JText::_('MOD_ITPSOCIALBUTTONS_SHARE_WITH_FRIENDS').'" title="'. JText::_('MOD_ITPSOCIALBUTTONS_SHARE_WITH_FRIENDS').'" />';
		
		$output = JHtml::_('link', $url, $text, $attribs);
		return $output;
	}
	
    
    public static function getDeliciousButton($title, $link, $style){
            
        $img_url = $style. "/delicious.png";
        
        return '<a href="http://del.icio.us/post?url=' . $link . '&amp;title=' . $title . '" title="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "Delicious") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "Delicious") . '" /></a>';    
    }
    
    public static function getDiggButton($title, $link, $style){
        
        $img_url = $style . "/digg.png";
        
        return '<a href="http://digg.com/submit?url=' . $link . '&amp;title=' . $title . '" title="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "Digg") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "Digg") . '" /></a>';
    }
    
    public static function getFacebookButton($title, $link, $style){
        
        $img_url = $style . "/facebook.png";
        
        return '<a href="http://www.facebook.com/sharer.php?u=' . $link . '&amp;t=' . $title . '" title="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "Facebook") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "Facebook") . '" /></a>';
    }
    
    public static function getGoogleButton($link, $style){
        
        $img_url = $style . "/google.png";
        
        return '<a href="https://plus.google.com/share?url=' . $link . '" title="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "Google Plus") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "Google Plus") . '" /></a>';
    }
    
    public static function getStumbleuponButton($title, $link, $style){
        
        $img_url = $style . "/stumbleupon.png";
        
        return '<a href="http://www.stumbleupon.com/submit?url=' . $link . '&amp;title=' . $title . '" title="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "Stumbleupon") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "Stumbleupon") . '" /></a>';
    }
    
    public static function getTechnoratiButton( $link, $style){
        
        $img_url = $style . "/technorati.png";
        
        return '<a href="http://technorati.com/faves?add=' . $link . '" title="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "Technorati") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "Technorati") . '" /></a>';
    }
    
    public static function getTwitterButton($title, $link, $style){
        
        $img_url = $style . "/twitter.png";
        
        return '<a href="http://twitter.com/share?text=' . $title . "&amp;url=" . $link . '" title="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "Twitter") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "Twitter") . '" /></a>';
    }
    
    public static function getLinkedInButton($title, $link, $style){
        
        $img_url = $style . "/linkedin.png";
        
        return '<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=' . $link .'&amp;title=' . $title . '" title="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "LinkedIn") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_ITPSOCIALBUTTONS_SUBMIT", "LinkedIn") . '" /></a>';
    }
}