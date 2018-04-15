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
defined( '_JEXEC' ) or die( 'Restricted index access' );
$path = $this->baseurl.'/templates/'.$this->template;
$app = JFactory::getApplication();

//factory
$document = JFactory::getDocument();

//General
$app->getCfg('sitename');
$siteName = $this->params->get('siteName');
$templateparams	= $app->getTemplate(true)->params;

//Logo Options
$navigationlogo = $this->params->get('navigationlogo');
$navigationmoblogo = $this->params->get('navigationmoblogo');

//Color Options
$themecolor = $this->params->get('themecolor');
$_SESSION['themecolor'] = $themecolor;

//Font Options
$body_fontsize = $this->params->get('body_fontsize');
$body_fontstyle = $this->params->get('body_fontstyle');
 
//Social Media Options
$social = $this->params->get('social');
$_SESSION['social']=$social;

//Copyright Text
$copyright= $this->params->get('copyright');

//Contact Options
$ctitle= $this->params->get('ctitle');
$cdesc= $this->params->get('cdesc');

?>