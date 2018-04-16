<?php 
/**
* Js Slideshow view file
* @copyright (C) 2014 by Joomlastars - All rights reserved!
* @package Js-Slideshow
* @license GNU/GPL, see LICENSE.php
*/

//No Direct Access
defined('_JEXEC') or die('Restricted Access');
$document = &JFactory::getDocument();
$path=JURI::base();
$document->addStyleDeclaration('
		#header-image-1 {
			background:url('.$path.'/'.$slide1_img.') no-repeat #000000 !important;	
			background-size: cover !important;
		}');

$document->addStyleDeclaration('
		#header-image-2 {
			background:url('.$path.'/'.$slide2_img.') no-repeat #000000 !important;	
			background-size: cover !important;
		}');
		
$document->addStyleDeclaration('
		#header-image-3 {
			background:url('.$path.'/'.$slide3_img.') no-repeat #000000 !important;	
			background-size: cover !important;
		}');		
		
?>

<div id="header-wrapper">
  <div id="header-slider" class="clearfix">
    <div id="header-image-1" class="header-image">
      <div class="transparent-frame"></div>
      <div class="header-slide">
        <p class="header-title"><?php echo $slide1_title1; ?></p>
        <p class="header-desc"><?php echo $slide1_desc1; ?></p>
        <a class="header-link filter-button" href="<?php echo $slide1_link1; ?>"><?php echo $slide1_btn1; ?></a> </div>
    </div>
    <div id="header-image-2" class="header-image">
      <div class="transparent-frame"></div>
      <div class="header-slide">
        <p class="header-title"><?php echo $slide2_title1; ?></p>
        <p class="header-desc"><?php echo $slide2_desc2; ?></p>
        <a class="header-link filter-button" href="<?php echo $slide2_link1; ?>"><?php echo $slide2_btn1; ?></a> </div>
    </div>
    <div id="header-image-3" class="header-image">
      <div class="transparent-frame"></div>
      <div class="header-slide">
        <p class="header-title"><?php echo $slide3_title1; ?></p>
        <p class="header-desc"><?php echo $slide3_desc3; ?></p>
        <a class="header-link filter-button" href="<?php echo $slide3_link1; ?>"><?php echo $slide3_btn1; ?></a> </div>
    </div>
  </div>
  <div id="header-slidernav"> <i class="fa fa-angle-right header-slidernav-arrow"></i>
    <div id="header-slidernav-page" class="clearfix">
      <div id="header-slidernav-page-inner">
        <p id="header-slidernav-current">1</p>
        <p id="header-slidernav-total">/1</p>
      </div>
    </div>
    <i class="fa fa-angle-left header-slidernav-arrow"></i> </div>
  <div id="header-navigation"> 
    <a id="header-logo" href="index.php"><img src="templates/shakenup/images/header-logo.png" alt=""></a>
        
    <div id="header-menu-wrapper"> <img src="templates/shakenup/images/mobile-menu-icon.png" class="mobile-menu-icon" alt=""> 
      <!-- Start Header menu -->
      <?php
								 // Header Menu
								 $document = &JFactory::getDocument();
								 $renderer       = $document->loadRenderer('modules');
								 $position       = 'js-headermenu';
								 $options        = array('style' => 'raw');
								 echo $renderer->render($position, $options, null); 
								 ?>
      <!-- End Header Menu --> 
    </div>
  </div>
  <div id="header-getstarted">
    <p id="getstarted-text">SCROLL DOWN</p>
    <i id="getstarted-arrow" class="fa fa-angle-down"></i> </div>
</div>
