<?php
/**
 * @package      ITPSocialButtons
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor.iliev@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined( "_JEXEC" ) or die;

JLoader::register('ItpSocialButtonsHelper', dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');

$urlPath         = JUri::base() . "modules/mod_itpsocialbuttons/";
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$doc = JFactory::getDocument();
/** @var $doc JDocumentHTML **/

// Loading style.css
if($params->get("loadCss")) {
    $doc->addStyleSheet($urlPath . "style.css");
}

$link  	 	= JUri::getInstance()->toString();
$title  	= $doc->getTitle();

$title      = rawurlencode($title);
$link       = $baseLink = rawurlencode($link);

// Short URL service
if($params->get("shortUrlService")) {
    $link = ItpSocialButtonsHelper::getShortUrl($link, $params);
}

$stylePath = $urlPath."images/".$params->get("icons_package");

require JModuleHelper::getLayoutPath('mod_itpsocialbuttons', $params->get('layout', 'default'));

