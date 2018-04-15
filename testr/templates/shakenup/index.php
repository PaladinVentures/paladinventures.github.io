<?php
/*
* @package Shakenup
* @copyright (C) 2017 by Joomlastars - All rights reserved!
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author Joomlastars <author@joomlastars.co.in>
* @authorurl <http://themeforest.net/user/joomlastars>
*/
?>
<?php 
ini_set("display_errors","0");
$app = JFactory::getApplication();
$menu   = $app->getMenu();
$menu1 = $menu->getActive();
require("php/variables.php");
?>
<?php
$app = JFactory::getApplication();
$this->setTitle( $this->getTitle());
$doc             = JFactory::getDocument();
$user            = JFactory::getUser();
$this->language  = $doc->language;
$pagetitle=$doc->getTitle();  

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->language; ?>" class="no-js"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<jdoc:include type="head" />
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/jquery.transit.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/jquery.simplr.smoothscroll.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/scrollReveal.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/jquery.mCustomScrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/viewport-units-buggyfill.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/script.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/isotope.pkgd.min.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/slimbox.js"></script>

<!-- CSS Files -->
<link type="text/css" rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/blog-post.css">
<link type="text/css" rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/blog.css">
<link type="text/css" rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/jquery.mCustomScrollbar.css">
<link type="text/css" rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/fonts/font-awesome/css/font-awesome.min.css">
<link type="text/css" rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/fonts/linecons/style.css">
<link type="text/css" rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/fonts/novecento-sans-wide/stylesheet.css">
<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>
<!-- Color Css -->

<?php
if($themecolor == "default") { ?>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/style.css" type="text/css" />
<?php } else { ?>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/style.css" type="text/css" />
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/colors/<?php echo  $_SESSION['themecolor']; ?>.css" rel="stylesheet" type="text/css" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/responsive.css" >
<?php
	if((!$this->countModules('js-left')) && (!$this->countModules('js-right')) ) {
	$cls = "col-12";
	}
	if((!$this->countModules('js-left')) && ($this->countModules('js-right')) )
    {
	$cls = "col-8";
	}
	if(($this->countModules('js-left')) && (!$this->countModules('js-right')) )
    {
	$cls = "col-8";
	}
	if(($this->countModules('js-left')) && ($this->countModules('js-right')) )
    {
	$cls = "col-4";
	}
?>
<!-- Custom css -->
<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template;?>/css/custom.css" type="text/css" />
<!-- End CSS Files -->
</head>
<body>

<!-- Site_wraper-->
<div class="site_wrapper"> 
  <!-- Page Loader-->
  <div id="pageloader">
    <div class="loader-item"> <img src="templates/shakenup/images/loading.gif" alt='Loading...' /> </div>
  </div>
  <!-- End Page Loader--> 
  
  <!-- Container -->
  <div id="navigation-wrapper">
    <div id="navigation-inner">
      <?php
			 if($this->params->get('navigationlogo')==NULL)
			 {
	    		?>
      <a id="navigation-logo" href="index.php"><img src="templates/shakenup/images/menu-logo.png" alt="navigation-logo"></a>
      <?php
					   }
					  else
					 {
					 ?>
      <a id="navigation-logo" href="index.php"><img src="<?php echo $navigationlogo; ?>" alt="navigation-logo"></a>
      <?php } ?>
      <div id="navigation-menu-wrapper">
        <?php
			 if($this->params->get('navigationmoblogo')==NULL)
			 {
	    		?>
        <img src="templates/shakenup/images/mobile-menu-icon-black.png" class="mobile-menu-icon" alt="mobile-menu-icon">
        <?php
					   }
					  else
					 {
					 ?>
        <img src="<?php echo $navigationmoblogo; ?>" class="mobile-menu-icon" alt="mobile-menu-icon">
        <?php } ?>
        <!-- Start Navigation menu -->
        <?php if($this->countModules('js-navigationmenu')) { ?>
        <jdoc:include type="modules" name="js-navigationmenu" style="shakenup" />
        <?php } ?>
        <!-- End Navigation Menu --> 
      </div>
    </div>
  </div>
  
  <!-- Start Slideshow -->
  <?php if($this->countModules('js-slideshow')) { ?>
  <jdoc:include type="modules" name="js-slideshow" style="xhtml" />
  <?php } ?>
  <!-- End Slideshow -->
  
  <div id="content-wrapper">
    <div class="separator-100"></div>
    <!-- Content -->
    <?php if ($menu->getActive() != $menu->getDefault()) { ?>
    <section id="blog-post-content-wrapper" class="clearfix">
      <?php if($this->countModules('js-left')) { ?>
      <div id="sidebar-wrapper" class="col-4">
        <jdoc:include type="modules" name="js-left" style="rightleft" />
      </div>
      <?php } ?>
      <h2 class="blogmasonary"><?php echo $pagetitle; ?></h2>
      <?php 
        if($pagetitle=='BLOG MASONRY') { $comclass='blog-wrapper'; } else { $comclass='blog-post-wrapper'; }
        ?>
      <div id="<?php echo $comclass; ?>" class="blogpage <?php echo $cls; ?>">
        <jdoc:include type="message" />
        <jdoc:include type="component" />
      </div>
      <?php if($this->countModules('js-right')) { ?>
      <div id="sidebar-wrapper" class="col-4">
        <jdoc:include type="modules" name="js-right" style="rightleft" />
      </div>
      <?php } ?>
    </section>
    <?php } ?>
    <!-- End Content -->
    
    <?php if($this->countModules('js-aboutwrapper')) { ?>
    <section id="about-wrapper" class="clearfix">
      <jdoc:include type="modules" name="js-aboutwrapper" style="shakenup" />
    </section>
    <div class="separator-70"></div>
    <?php } ?>
    
    <?php if($this->countModules('js-portfoliowrapper')) { ?>
    <section id="portfolio-wrapper" class="clearfix">
      <h2>Portfolio</h2>
      <p class="section-subtitle">CHECK OUR CREATIVE IDEAS ON OUR LATEST WORKS</p>
      <div class="separator-small"></div>
      <jdoc:include type="modules" name="js-portfoliowrapper" style="shakenup" />
    </section>
    <div class="separator-100"></div>
    <?php } ?>
    
    <?php if($this->countModules('js-teamwrapper')) { ?>
    <section id="team-wrapper" class="clearfix">
      <jdoc:include type="modules" name="js-teamwrapper" style="shakenup" />
    </section>
    <div class="separator-70"></div>
    <?php } ?>
    
    <?php if($this->countModules('js-numberswrapper')) { ?>
    <section id="parallax-numbers-wrapper" class="clearfix">
      <jdoc:include type="modules" name="js-numberswrapper" style="shakenup" />
    </section>
    <div class="separator-100"></div>
    <?php } ?>
    
    <?php if($this->countModules('js-serviceswrapper')) { ?>
    <section id="services-wrapper" class="clearfix">
      <jdoc:include type="modules" name="js-serviceswrapper" style="shakenup" />
    </section>
    <div class="separator-70"></div>
    <?php } ?>
    
    <?php if($this->countModules('js-showcasewrapper')) { ?>
    <section id="showcase-wrapper" class="clearfix">
      <jdoc:include type="modules" name="js-showcasewrapper" style="shakenup" />
    </section>
    <div class="separator-100"></div>
    <?php } ?>
    
    <?php if($this->countModules('js-blog')) { ?>
    <section id="blog-wrapper" class="clearfix">
      <h2>Our Blog</h2>
      <p class="section-subtitle">LATEST INFORMATIONS FROM ORBITAL INDUSTRIES</p>
      <div class="separator-small"></div>
      <jdoc:include type="modules" name="js-blog" style="shakenup" />
    </section>
    <div class="separator-70"></div>
    <?php } ?>
    
    <?php if($this->countModules('js-testimonialswrapper')) { ?>
    <section id="testimonials-wrapper" class="clearfix">
      <jdoc:include type="modules" name="js-testimonialswrapper" style="shakenup" />
    </section>
    <div class="separator-100"></div>
    <?php } ?>
    
    <?php if($this->countModules('js-learnmorewrapper')) { ?>
    <section id="learn-more-wrapper" class="clearfix">
      <jdoc:include type="modules" name="js-learnmorewrapper" style="shakenup" />
    </section>
    <div class="separator-100"></div>
    <?php } ?>
    
    <?php if($this->countModules('js-informationwrapper')) { ?>
    <section id="information-wrapper" class="clearfix">
      <jdoc:include type="modules" name="js-informationwrapper" style="shakenup" />
    </section>
    <div class="separator-100"></div>
    <?php } ?>
    
    <?php if($this->countModules('js-buythemewrapper')) { ?>
    <section id="buy-theme-wrapper" class="clearfix">
      <jdoc:include type="modules" name="js-buythemewrapper" style="shakenup" />
    </section>
    <div class="separator-100"></div>
    <?php } ?>
    
    <?php if($this->countModules('js-clientswrapper')) { ?>
    <section id="clients-wrapper" class="clearfix">
      <jdoc:include type="modules" name="js-clientswrapper" style="shakenup" />
    </section>
    <div class="separator-100"></div>
    <?php } ?>
    
    <section id="contact-wrapper" class="clearfix">
      <h2><?php echo $ctitle; ?></h2>
      <p class="section-subtitle"><?php echo $cdesc; ?></p>
      <div class="separator-small"></div>
      <?php if($this->countModules('js-contactform')) { ?>
      <jdoc:include type="modules" name="js-contactform" style="contact" />
      <?php } ?>
      <?php if($this->countModules('js-map')) { ?>
      <div id="map-info" class="col-4">
        <jdoc:include type="modules" name="js-map" style="contact" />
      </div>
      <?php } ?>
      <?php if($this->countModules('js-contactinfo')) { ?>
      <jdoc:include type="modules" name="js-contactinfo" style="contact" />
      <?php } ?>
    </section>
    <div class="separator-100"></div>
  </div>
  
  <!-- Footer Section -->
  <?php if($this->countModules('js-footer')) { ?>
  <div id="footer-wrapper">
    <jdoc:include type="modules" name="js-footer" style="footer" />
  </div>
  </section>
  <?php } ?>
  <!-- End Footer Section --> 
</div>
<!-- End Container --> 

</div>
<!-- End Site_wraper-->
</body>
</html>
