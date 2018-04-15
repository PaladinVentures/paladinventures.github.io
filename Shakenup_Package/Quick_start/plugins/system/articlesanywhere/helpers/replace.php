<?php
/**
 * Plugin Helper File: Replace
 *
 * @package         Articles Anywhere
 * @version         3.7.0
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2014 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

class plgSystemArticlesAnywhereHelperReplace
{
	var $helpers = array();
	var $params = null;

	public function __construct()
	{
		require_once __DIR__ . '/helpers.php';
		$this->helpers = plgSystemArticlesAnywhereHelpers::getInstance();
		$this->params = $this->helpers->getParams();
	}

	public function replaceTags(&$string, $area = 'article')
	{
		if (!is_string($string) || $string == '')
		{
			return;
		}

		if ($area == 'component')
		{
			// allow in component?
			if (in_array(JFactory::getApplication()->input->get('option'), $this->params->disabled_components))
			{
				$this->helpers->get('protect')->protectTags($string);

				return;
			}
		}

		if (
			strpos($string, '{' . $this->params->article_tag) === false

		)
		{
			return;
		}

		$this->helpers->get('protect')->protect($string);

		$this->params->message = '';

		// COMPONENT
		if (JFactory::getDocument()->getType() == 'feed')
		{
			$s = '#(<item[^>]*>)#s';
			$string = preg_replace($s, '\1<!-- START: AA_COMPONENT -->', $string);
			$string = str_replace('</item>', '<!-- END: AA_COMPONENT --></item>', $string);
		}
		if (strpos($string, '<!-- START: AA_COMPONENT -->') === false)
		{
			$this->helpers->get('tag')->tagArea($string, 'component');
		}

		$components = $this->helpers->get('tag')->getAreaByType($string, 'component');

		foreach ($components as $component)
		{
			$this->helpers->get('process')->processArticles($component['1'], 'components');
			$string = str_replace($component['0'], $component['1'], $string);
		}

		// EVERYWHERE
		$this->helpers->get('process')->processArticles($string, 'other');

		NNProtect::unprotect($string);
	}
}
