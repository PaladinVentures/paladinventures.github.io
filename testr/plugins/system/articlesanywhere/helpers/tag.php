<?php
/**
 * Plugin Helper File: Tag
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

class plgSystemArticlesAnywhereHelperTag
{
	public function tagArea(&$string, $area_type = '')
	{
		if (!$string || !$area_type)
		{
			return;
		}

		$start = '<!-- START: AA_' . strtoupper($area_type) . ' -->';
		$end = '<!-- END: AA_' . strtoupper($area_type) . ' -->';

		$string = $start . $string . $end;

		if ($area_type == 'article_text')
		{
			$string = preg_replace('#(<hr class="system-pagebreak".*?/>)#si', $end . '\1' . $start, $string);
		}
	}

	private function tagAreaByType(&$string, $area_type)
	{
		switch ($area_type)
		{
			case 'component':
				$this->tagComponent($string);
				break;
			case 'body':
				$this->tagBody($string);
				break;
			case 'head':
				$this->tagHead($string);
				break;
		}
	}

	public function getAreaByType(&$string, $area_type = '')
	{
		if (!$string || !$area_type)
		{
			return array();
		}

		$this->tagAreaByType($string, $area_type);

		$start = '<!-- START: AA_' . strtoupper($area_type) . ' -->';
		$end = '<!-- END: AA_' . strtoupper($area_type) . ' -->';

		$matches = explode($start, $string);
		array_shift($matches);

		foreach ($matches as $i => $match)
		{
			$orig = $start . $match;
			$text = $match;

			if (strpos($text, $end) !== false)
			{
				$text = substr($text, 0, strrpos($text, $end));
				$orig = $start . $text . $end;
			}

			$matches[$i] = array($orig, $text);
		}

		return $matches;
	}

	private function tagComponent(&$string)
	{
		if (!$string)
		{
			return;
		}

		$start = '<!-- START: AA_COMPONENT -->';

		if (JFactory::getDocument()->getType() == 'feed')
		{
			$this->tagByTagType($string, 'item', 'component');
		}

		if (strpos($string, $start) === false)
		{
			$this->tagArea($string, 'component');
		}
	}

	private function tagHead(&$string)
	{
		if (!$string)
		{
			return;
		}

		if (strpos($string, '</head>') === false)
		{
			return;
		}

		$this->tagByTagType($string, 'head', 'head');
	}

	private function tagBody(&$string)
	{
		if (!$string)
		{
			return;
		}

		$start = '<!-- START: AA_BODY -->';

		if (strpos($string, $start) !== false)
		{
			return;
		}

		if (strpos($string, '<body') !== false && strpos($string, '</body>') !== false)
		{
			$this->tagByTagType($string, 'body', 'body');

			return;
		}

		if (strpos($string, '<item') !== false && strpos($string, '</item>') !== false)
		{
			$this->tagByTagType($string, 'item', 'body');

			return;
		}

		$this->tagArea($string, 'body');
	}

	private function tagByTagType(&$string, $tag, $area_type)
	{
		if (!$string)
		{
			return;
		}

		$start = '<!-- START: AA_' . strtoupper($area_type) . ' -->';
		$end = '<!-- END: AA_' . strtoupper($area_type) . ' -->';

		$string = preg_replace('#(<' . $tag . '(\s[^>]*)?>)#s', '\1' . $start, $string);
		$string = str_replace('</' . $tag . '>', $end . '</' . $tag . '>', $string);
	}
}
