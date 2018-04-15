<?php
/*
# SP Portfolio - Simple Portfolio module by JoomShaper.com
# -------------------------------------------------------------
# Author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2014 JoomShaper.com. All Rights Reserved.
# @license - GNU/GPL V2 or Later
# Websites: http://www.joomshaper.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_SITE.'/components/com_content/helpers/route.php';
jimport( 'joomla.plugin.helper' );
jimport( 'joomla.application.categories' );
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');

abstract class modSPPortfolioJHelper
{

	private $_items = null;

    private $_parent = null;

	public static function getList($params, $start, $limit){
		
		$app	= JFactory::getApplication();
		$db		= JFactory::getDbo();

		//Parameters
		$catids = self::categories($params->get('catid'));
		
		//Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		//Set application parameters in model
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('list.start', $start);
		$model->setState('list.limit', $limit);
		$model->setState('filter.published', 1);
		
		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);		
		
		// Category filter
		$model->setState('filter.category_id', $catids);

		// Filter by language
		$model->setState('filter.language', $app->getLanguageFilter());		

		//  Featured switch
		switch ($params->get('show_featured'))
		{
			case '1':
				$model->setState('filter.featured', 'only');
				break;
			case '0':
				$model->setState('filter.featured', 'hide');
				break;
			default:
				$model->setState('filter.featured', 'show');
				break;
		}
		
		// Set ordering
		$orderby 			= $params->get('orderby', 'a.ordering');
		$ordering			= $params->get('ordering', 'DESC');

		$model->setState('list.ordering', $orderby);
		$model->setState('list.direction', $ordering);

		$items = $model->getItems();
		
		foreach ($items as &$item) {
		
			$item->slug 			= $item->id.':'.$item->alias;
			$item->catslug 			= $item->catid.':'.$item->category_alias;
			$item->tag 				= $item->category_title;
			$item->image 			= JURI::base() . self::getImage($item->introtext,$item->images);
			$image_full 			= JURI::base() . self::getFullImage($item->introtext,$item->images);
			$item->image_full 		= ($image_full) ? $image_full : $item->image;
			$item->title 			= htmlspecialchars($item->title);
			$item->urls 			= json_decode($item->urls);
			$item->introtext 		= JHtml::_('content.prepare', $item->introtext);
			$nonsefurl 				= ContentHelperRoute::getArticleRoute($item->slug, $item->catslug);
			$nonsefurl				= preg_replace('/Itemid=(.+)/', '', $nonsefurl );
			$item->link 			= JRoute::_($nonsefurl);
		}	
		return $items;
		
	}


	public static function getTotal($params){


		$app	= JFactory::getApplication();
		$db		= JFactory::getDbo();

		//Parameters
		$catids = self::categories($params->get('catid'));
		
		//Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		//Set application parameters in model
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('filter.published', 1);
		
		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);		
		
		// Category filter
		$model->setState('filter.category_id', $catids);

		// Filter by language
		$model->setState('filter.language', $app->getLanguageFilter());		

		//  Featured switch
		switch ($params->get('show_featured'))
		{
			case '1':
				$model->setState('filter.featured', 'only');
				break;
			case '0':
				$model->setState('filter.featured', 'hide');
				break;
			default:
				$model->setState('filter.featured', 'show');
				break;
		}
		
		$items = $model->getItems();
		
		return count($items);
	}


	//Get categories
	public static function categories($parent){
		$db = JFactory::getDBO();
		$query = "SELECT id FROM #__categories WHERE `parent_id`='" . $parent. "' AND `published`='1'";
		$db->setQuery($query); 
		$catids = $db->loadColumn();
		return $catids;
	}

	//All Categories
	public static function getCategories($parent){
		$categories = JCategories::getInstance('Content');
        $parent = $categories->get($parent);
        if(is_object($parent))
            $_items = $parent->getChildren(false);
        else
            $_items = false;

        return self::loadCats($_items);
	}

	//Load list categories
	private static function loadCats($cats = array())
    {

        if(is_array($cats))
        {
            $i = 0;
            $return = array();
            foreach($cats as $JCatNode)
            {
                $return[$i] = $JCatNode->title;
                $i++;
            }

            return $return;
        }

        return false;
    }	

	//Create slug from title
	public static function slug($text) {
		return preg_replace('/[^a-z0-9_]/i','-', strtolower(trim($text)));
	}

	//Retrive image
	private static function getImage($text, $image_src="") {
		$image_src = json_decode($image_src);		
		if (JVERSION>=2.5 && @$image_src->image_intro) {
			return $image_src->image_intro;
		} else {
			preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $text, $matches);	
			if (isset($matches[1])) {
				return $matches[1];
			}			
		}
	}

	//Retrive image
	private static function getFullImage($text, $image_src="") {
		$image_src = json_decode($image_src);

		if (JVERSION>=2.5 && @$image_src->image_fulltext) {
			return $image_src->image_fulltext;
		} else {
			preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $text, $matches);	
			if (isset($matches[1])) {
				return $matches[1];
			}			
		}
		return null;
	}	
	
}