<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Software (http://extstore.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die();

jimport('joomla.plugin.plugin');

/**
 * System - Scroll To Top Plugin
 *
 * @package		Joomla.Plugin
 * @subpakage	Skyline.ScrollToTop
 */
class plgSystemSL_ScrollToTop extends JPlugin {

	/**
	 * Constructor.
	 *
	 * @param 	$subject
	 * @param	array $config
	 */
	function __construct(&$subject, $config = array()) {
		// call parent constructor
		parent::__construct($subject, $config);
	}

	/**
	 * onAfterRoute Hook.
	 */
	function onAfterRoute() {
		// initialize variables
		$app			= JFactory::getApplication();
		$admin_enable	= $this->params->get('admin_enable');

		if (!$admin_enable && $app->isAdmin()) {
			return;
		}

		$style				= $this->params->get('style');
		$text				= htmlspecialchars($this->params->get('text'));
		$title				= htmlspecialchars($this->params->get('title'));
		$duration			= (int) $this->params->get('duration', 500);
		$transition			= $this->params->get('transition', 'Fx.Transitions.linear');
		$custom_css			= $this->params->get('custom_css');
		$engine				= $this->params->get('engine', 'mootools');
		$image				= $this->params->get('image', '');
		$position			= $this->params->get('position', 'bottom_right');
		$border_radius		= $this->params->get('border_radius', '3');
		$offset_x			= $this->params->get('offset_x', '20');
		$offset_y			= $this->params->get('offset_y', '20');
		$padding_x			= $this->params->get('padding_x', '12');
		$padding_y			= $this->params->get('padding_y', '12');
		$background_color	= $this->params->get('background_color', '#121212');
		$color				= $this->params->get('color', '#fff');
		$hover_background_color	= $this->params->get('hover_background_color', '#08C');
		$hover_color		= $this->params->get('hover_color', '#fff');

		if ($image) {
			$image	= JHtml::_('image', $image, '', null, false, true);
		}

		//
		$position_css	= '';

		switch ($position) {
			case 'top_left':
				$position_css	= "left: {$offset_x}px; top: {$offset_y}px;";
				break;
			case 'top_right':
				$position_css	= "right: {$offset_x}px; top: {$offset_y}px;";
				break;
			case 'bottom_left':
				$position_css	= "left: {$offset_x}px; bottom: {$offset_y}px;";
				break;
			case 'bottom_right':
				$position_css	= "right: {$offset_x}px; bottom: {$offset_y}px;";
				break;
		}

		$document	= JFactory::getDocument();

		$class		= 'scrollToTop';

		// Build Custom CSS
		$css	= <<<CSS
#scrollToTop {
	cursor: pointer;
	font-size: 0.9em;
	position: fixed;
	text-align: center;
	z-index: 9999;
	-webkit-transition: background-color 0.2s ease-in-out;
	-moz-transition: background-color 0.2s ease-in-out;
	-ms-transition: background-color 0.2s ease-in-out;
	-o-transition: background-color 0.2s ease-in-out;
	transition: background-color 0.2s ease-in-out;

	background: $background_color;
	color: $color;
	border-radius: {$border_radius}px;
	padding-left: {$padding_x}px;
	padding-right: {$padding_x}px;
	padding-top: {$padding_y}px;
	padding-bottom: {$padding_y}px;
	$position_css
}

#scrollToTop:hover {
	background: $hover_background_color;
	color: $hover_color;
}

#scrollToTop > img {
	display: block;
	margin: 0 auto;
}
CSS;

		$document->addStyleDeclaration($css);

		if ($custom_css) {
			$document->addStyleDeclaration($custom_css);
		}

		if ($engine != 'jquery') {
			JHtml::_('behavior.framework', true);
			JHtml::_('script', 'plg_system_sl_scrolltotop/scrolltotop_mt.js', false, true, false, false, true);

			$js			= <<<SCRIPTHERE
document.addEvent('domready', function() {
	new Skyline_ScrollToTop({
		'image':		'$image',
		'text':			'$text',
		'title':		'$title',
		'className':	'$class',
		'duration':		$duration,
		'transition':	$transition
	});
});
SCRIPTHERE;
		} else {
			JHtml::_('jquery.framework');
			JHtml::_('script', 'plg_system_sl_scrolltotop/scrolltotop_jq.js', false, true, false, false, true);

			$js			= <<<SCRIPTHERE
jQuery(document).ready(function() {
	jQuery(document.body).SLScrollToTop({
		'image':		'$image',
		'text':			'$text',
		'title':		'$title',
		'className':	'$class',
		'duration':		$duration
	});
});
SCRIPTHERE;
		}

		$document->addScriptDeclaration($js);
	}
}