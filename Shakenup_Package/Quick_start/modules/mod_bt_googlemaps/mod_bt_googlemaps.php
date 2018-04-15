<?php
/**
 * @package 	mod_bt_googlemaps - BT Google Maps
 * @version		2.0.5
 * @created		Jun 2012

 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2012 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// Include the syndicate functions only once
require_once (dirname ( __FILE__ ) . '/helper.php');
modbt_googlemapsHelper::fetchHead ( $params, $module );

require (JModuleHelper::getLayoutPath ( 'mod_bt_googlemaps' ));

?>

