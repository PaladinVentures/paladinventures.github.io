<?php
/*
# SP Portfolio - Simple Portfolio module by JoomShaper.com
# -------------------------------------------------------------
# Author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2013 JoomShaper.com. All Rights Reserved.
# @license - GNU/GPL V2 or Later
# Websites: http://www.joomshaper.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$doc 								= JFactory::getDocument();
$rtl 								= JFactory::getLanguage()->get('rtl');
$direction 							= JRequest::getVar('direction');

if(($direction=='rtl') || ($rtl)) {
	$rtl 							= true;
} else {
	$rtl 							= false;
}

if( JVERSION >= 3 )
{
	JHtml::_('jquery.framework');
}

//Basic
$moduleclass_sfx 					= $params->get('moduleclass_sfx');
$moduleName         				= basename(dirname(__FILE__));
$uniqid								= $module->id;
$catid								= $params->get('catid', 'root');
$module_layout						= $params->get('module_layout');
$column								= $params->get('column', 2);
$show_title							= $params->get('show_title');
$linked_title						= $params->get('linked_title');
$show_category						= $params->get('show_category');
$show_url							= $params->get('show_url');
$show_introtext						= $params->get('show_introtext');
$show_readmore						= $params->get('show_readmore');
$ajax_loader						= $params->get('ajax_loader');
$show_filter						= $params->get('show_filter');
$show_title							= $params->get('show_title');
$start								= 0;
$limit								= $params->get('limit', 6);
$ajaxlimit							= $params->get('ajaxlimit', 3);

$ajaxRequest						= false;


$load_jquery						= $params->get('load_jquery');
//echo "<pre>";print_r($params);
if( JRequest::getInt('moduleID', 0) > 0 ){
	$start							= JRequest::getInt('start');
	$limit							= JRequest::getInt('limit', $ajaxlimit);
	$ajaxRequest					= true;	
	
	$mID							= JRequest::getInt('moduleID');
	
	$db=& JFactory::getDBO();
	$query = ' SELECT * FROM #__modules   WHERE id = '.$mID;
	$db->setQuery( $query );
	$rs=$db->loadObject();
	$newValues=json_decode($rs->params);

	$params["catid"] = $newValues->catid;
	$params["limit"] = $newValues->limit;
	$params["column"] = $newValues->column;
	$params["ajaxlimit"] = $newValues->ajaxlimit;
	$params["orderby"] = $newValues->orderby;
	$params["ordering"] = $newValues->ordering;
	$params["show_featured"] = $newValues->show_featured;
	$params["module_layout"] = $newValues->module_layout;
	$module_layout = $newValues->module_layout;
	$params["show_title"] = $newValues->show_title;
	$params["linked_title"] = $newValues->linked_title;
	$params["show_category"] = $newValues->show_category;
	$params["show_url"] = $newValues->show_url;
	$params["show_introtext"] = $newValues->show_introtext;
	$params["show_readmore"] = $newValues->show_readmore;
	$params["ajax_loader"] = $newValues->ajax_loader;
	$params["show_filter"] = $newValues->show_filter;
	$params["load_jquery"] = $newValues->load_jquery;
	$params["moduleclass_sfx"] = $newValues->moduleclass_sfx;
	$params["cache"] = $newValues->cache;
	$params["cache_time"] = $newValues->cache_time;
	$params["cachemode"] = $newValues->cachemode;
	$params["module_tag"] = $newValues->module_tag;
	$params["bootstrap_size"] = $newValues->bootstrap_size;
	$params["header_tag"] = $newValues->header_tag;
	$params["header_class"] = $newValues->header_class;
	$params["style"] = $newValues->style;
	
		
		
}
require_once (dirname(__FILE__).'/helper.php');

$items 								= modSPPortfolioJHelper::getList($params, $start, $limit);
$total  							= modSPPortfolioJHelper::getTotal($params);

//$doc->addStylesheet(JURI::base(true) . '/modules/'.$moduleName.'/assets/css/' . $moduleName .'_'.$module_layout.'.css');

if ($show_filter) $doc->addScript(JURI::base(true) . '/modules/'.$moduleName.'/assets/js/jquery.min.js');
if ($show_filter) $doc->addScript(JURI::base(true) . '/modules/'.$moduleName.'/assets/js/slimbox2.js');
if ($show_filter) $doc->addScript(JURI::base(true) . '/modules/'.$moduleName.'/assets/js/jquery.isotope.min.js');
require(JModuleHelper::getLayoutPath($moduleName, $module_layout));