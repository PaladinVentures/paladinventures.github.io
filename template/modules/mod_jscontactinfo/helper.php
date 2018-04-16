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
class modJsContactinfoHelper
{
    /**
     * Retrieves the hello message
     *
     * @param array $params An object containing the module parameters
     * @access public
     */    
    public static function getJsContactinfo( $params )
    {
       $app = JFactory::getApplication();
	   $template   = $app->getTemplate(true);
	   $params     = $template->params;
	
//Contact Options

$cagencytitle= $params->get('cagencytitle');
$cagencydesc= $params->get('cagencydesc');
$calltitle= $params->get('calltitle');
$call1= $params->get('call1');
$call2= $params->get('call2');
$visittitle= $params->get('visittitle');
$visitadd= $params->get('visitadd');
$visitcity= $params->get('visitcity');
$visitcode= $params->get('visitcode');
$visitcountry= $params->get('visitcountry');
	?>

<div id="contact-info" class="col-4 contact-item">
  <h4><?php echo $cagencytitle; ?></h4>
  <p><?php echo $cagencydesc; ?></p>
  <h4><?php echo $calltitle; ?></h4>
  <p><?php echo $call1; ?><br>
    <?php echo $call2; ?></p>
  <h4><?php echo $visittitle; ?></h4>
  <p><?php echo $visitadd; ?><br>
    <?php echo $visitcity; ?><br>
    <?php echo $visitcode; ?><br>
    <?php echo $visitcountry; ?></p>
</div>
<?php  

    
	  
    }
}
?>
