<?php /*
* @package Js Menu
* @copyright (C) 2014 by JoomlaStars - All rights reserved!
* @license GNU/GPL, see LICENSE.php
*/
defined('_JEXEC') or die('Restricted access');
?>
<?php

require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');
$params->def('module_id',$module->id);
$jsnavigationmenu = new modJSNavigationMenuHelper();
require(JModuleHelper::getLayoutPath('mod_jsnavigationmenu'));

?>



