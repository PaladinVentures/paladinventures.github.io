<?php
/**
* Js Slideshow view file
* Author by Joomlastars.
* @copyright (C) 2014 by Joomlastars - All rights reserved!
* @package Js-Slideshow
* @license GNU/GPL, see LICENSE.php
*/
//No Direct Access to this file
defined('_JEXEC') or die('Restricted Access');

$modURL 	= JURI::base().'modules/mod_js-slideshow';


//get details of Slide 1

$slide1_img = $params->get('slide1_img');
$slide1_title1 = $params->get('slide1_title1');
$slide1_desc1 = $params->get('slide1_desc1');
$slide1_btn1 = $params->get('slide1_btn1');
$slide1_link1 = $params->get('slide1_link1');


//get details of Slide 2
$slide2_img = $params->get('slide2_img');
$slide2_title1 = $params->get('slide2_title1');
$slide2_desc2 = $params->get('slide2_desc2');
$slide2_btn1 = $params->get('slide2_btn1');
$slide2_link1 = $params->get('slide2_link1');


//get details of Slide 3

$slide3_img = $params->get('slide3_img');
$slide3_title1 = $params->get('slide3_title1');
$slide3_desc3 = $params->get('slide3_desc3');
$slide3_btn1 = $params->get('slide3_btn1');
$slide3_link1 = $params->get('slide3_link1');

require JModuleHelper::getLayoutPath('mod_js-slideshow');

?>
