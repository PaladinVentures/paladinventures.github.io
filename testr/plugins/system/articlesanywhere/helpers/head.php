<?php
/**
 * Plugin Helper File: Head
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

class plgSystemArticlesAnywhereHelperHead
{
	var $helpers = array();

	public function __construct()
	{
		require_once __DIR__ . '/helpers.php';
		$this->helpers = plgSystemArticlesAnywhereHelpers::getInstance();
	}

	// replace head with newly generated head
	// this is necessary because the plugins might have added scripts/styles to the head
	public function updateHead(&$html)
	{
		if (strpos($html, '</head>') === false)
		{
			return;
		}

		$orig_document = clone(JFactory::getDocument());

		// get line endings
		$lnEnd = JFactory::getDocument()->_getLineEnd();
		$tab = JFactory::getDocument()->_getTab();
		$tagEnd = ' />';
		$string = '';

		// Generate link declarations
		foreach (JFactory::getDocument()->_links as $link)
		{
			if (in_array($link, $orig_document->_links))
			{
				continue;
			}

			$string .= $tab . $link . $tagEnd . $lnEnd;
		}

		// Generate stylesheet links
		foreach (JFactory::getDocument()->_styleSheets as $source => $attributes)
		{
			if (array_key_exists($source, $orig_document->_styleSheets))
			{
				continue;
			}

			$string .= $tab . '<link rel="stylesheet" href="' . $source . '" type="' . $attributes['mime'] . '"';
			if (!is_null($attributes['media']))
			{
				$string .= ' media="' . $attributes['media'] . '" ';
			}

			$temp = JArrayHelper::toString($attributes['attribs']);
			if ($temp)
			{
				$string .= ' ' . $temp;
			}

			$string .= $tagEnd . $lnEnd;
		}

		// Generate stylesheet declarations
		foreach (JFactory::getDocument()->_style as $type => $content)
		{
			if (in_array($content, $orig_document->_style))
			{
				continue;
			}

			$string .=
				$tab . '<style type="' . $type . '">' . $lnEnd
				. $tab . $tab . (JFactory::getDocument()->_mime == 'text/html' ? '<!--' : '<![CDATA[') . $lnEnd
				. $content . $lnEnd
				. $tab . $tab . (JFactory::getDocument()->_mime == 'text/html' ? '-->' : ']]>') . $lnEnd
				. $tab . '</style>' . $lnEnd;
		}

		// Generate script file links
		foreach (JFactory::getDocument()->_scripts as $source => $type)
		{
			if (array_key_exists($source, $orig_document->_scripts))
			{
				continue;
			}
			$string .= $tab . '<script type="' . $type . '" src="' . $source . '"></script>' . $lnEnd;
		}

		// Generate script declarations
		foreach (JFactory::getDocument()->_script as $type => $content)
		{
			if (in_array($content, $orig_document->_script))
			{
				continue;
			}

			$string .= $tab . '<script type="' . $type . '">' . $lnEnd
				. (JFactory::getDocument()->_mime == 'text/html' ? '' : $tab . $tab . '<![CDATA[' . $lnEnd)
				. $content . $lnEnd
				. (JFactory::getDocument()->_mime == 'text/html' ? '' : $tab . $tab . '// ]]>' . $lnEnd)
				. $tab . '</script>' . $lnEnd;
		}

		foreach (JFactory::getDocument()->_custom as $custom)
		{
			if (in_array($custom, $orig_document->_custom))
			{
				continue;
			}

			$string .= $tab . $custom . $lnEnd;
		}

		JResponse::setBody(str_replace('</head>', $string . "\n" . '</head>', JResponse::getBody()));

		unset($orig_document);
	}
}
