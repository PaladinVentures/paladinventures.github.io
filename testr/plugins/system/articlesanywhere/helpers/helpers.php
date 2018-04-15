<?php
/**
 * Plugin Helper File: Helpers
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

class plgSystemArticlesAnywhereHelpers
{
	protected static $instance = null;
	protected static $params = null;
	var $helpers = array();

	public static function getInstance($params = 0)
	{
		if (!self::$instance)
		{
			self::$instance = new static;
		}

		if ($params)
		{
			self::$params = $params;
		}

		return self::$instance;
	}

	public function getParams()
	{
		return self::$params;
	}

	public function get($name)
	{
		if (isset($this->helpers[$name]))
		{
			return $this->helpers[$name];
		}

		require_once __DIR__ . '/' . $name . '.php';
		$class = rtrim(__CLASS__, 's') . ucfirst($name);
		$this->helpers[$name] = new $class;

		return $this->helpers[$name];
	}
}
