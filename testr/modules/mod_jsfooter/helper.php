<?php
/**
 * Helper class for Hello World! module
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:modules/
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class modFooterHelper
{
    /**
     * Retrieves the hello message
     *
     * @param array $params An object containing the module parameters
     * @access public
     */    
    public static function getFooter( $params )
    {
       $app = JFactory::getApplication();
	   $template   = $app->getTemplate(true);
	   $params     = $template->params;
	
	  	 
	 // fetach the detil of footer
	 
	 // Social icons
	 
	 $social = $params->get('social');
	 
	 // fetach the detil of radio button
	 
	 $fb_b=$params->get('nav_facebook_sw');
	 $s_b=$params->get('nav_flicker_sw');
	 $tw_b=$params->get('nav_twitter_sw');
	 $g_b=$params->get('nav_google_sw');
	 $ln_b=$params->get('nav_linkedin_sw');
	 $fl_b=$params->get('nav_flicker_sw');
	 $youtube_b=$params->get('nav_youtube_sw');
	 $rss_b=$params->get('nav_rss_sw');
	 $html_b=$params->get('nav_html5_sw');
	 $behance=$params->get('nav_rss_behance');
	 $dribble=$params->get('nav_rss_dribbble');
	 $flickr=$params->get('nav_flicker_sw');
	 
	
	 $fb=$params->get('facebook');
	 $tw=$params->get('twitter');
	 $g=$params->get('google');
	 $ln=$params->get('linkedin');
	 $fl=$params->get('flicker');
	 $y=$params->get('youtube');
	 $rss=$params->get('rss');
	 $h=$params->get('html5');
	 $s=$params->get('skype');
	 $behancelink=$params->get('behance');
	 $dribblelink=$params->get('dribbble');
	 $flickrlink=$params->get('flicker');
	 
	 $copyright=$params->get('copyright');
		 
	 ?>


<div id="footer-logo-wrapper">
			<img id="footer-logo" src="templates/shakenup/images/header-logo.png" alt="">
		</div>
		<p><?php echo $copyright; ?></p>
        <?php 
			if($social=='1')
			{ ?>
		<div id="footer-social" class="clearfix">
			 <?php
			     if($fb_b=='1'){	
				?>
    <a target="_blank" href="<?php echo $fb;?>"><i class="fa fa-facebook"></i></a>
    <?php } ?>
    <?php
                  if($tw_b=='1'){	
                 ?>
    <a target="_blank" href="<?php echo $tw;?>"><i class="fa fa-twitter"></i></a>
    <?php } ?>
    <?php
                  if($g_b=='1'){	
                 ?>
    <a target="_blank" href="<?php echo $g;?>"><i class="fa fa-google-plus"></i></a>
    <?php } ?>
    <?php
                  if($ln_b=='1'){	
                 ?>
    <a target="_blank" href="<?php echo $ln;?>"><i class="fa fa-linkedin"></i></a>
    <?php } ?>
    <?php
                  if($s_b=='1'){	
                 ?>
    <a target="_blank" href="<?php echo $s;?>"><i class="fa fa-skype"></i></a>
    <?php } ?>
    <?php
                  if($fl_b=='1'){	
                 ?>
    <a target="_blank" href="<?php echo $fl;?>"><i class="fa fa-flickr"></i></a>
    <?php } ?>
    <?php
                  if($html_b=='1'){	
                 ?>
    <a target="_blank" href="<?php echo $h;?>"><i class="fa fa-html5"></i></a>
    <?php } ?>
    <?php
                  if($youtube_b=='1'){	
                 ?>
    <a target="_blank" href="<?php echo $y;?>"><i class="fa fa-youtube"></i></a>
    <?php } ?>
    <?php
                  if($rss_b=='1'){	
                 ?>
    <a target="_blank" href="<?php echo $rss;?>"><i class="fa fa-rss"></i></a>
    <?php } ?>
    
     <?php
                  if($behance=='1'){	
                 ?>
    <a target="_blank" href="<?php echo $behancelink;?>"><i class="fa fa-behance"></i></a>
    <?php } ?>
    
     <?php
                  if($dribble=='1'){	
                 ?>
    <a target="_blank" href="<?php echo $dribblelink;?>"><i class="fa fa-dribbble"></i></a>
    <?php } ?>
    
    <?php
                  if($flickr=='1'){	
                 ?>
    <a target="_blank" href="<?php echo $flickrlink;?>"><i class="fa fa-flickr"></i></a>
    <?php } ?>
    
		</div>
       <?php } ?>
       
       
     
<?php
	  
    }
}
?>
