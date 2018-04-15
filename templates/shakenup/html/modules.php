<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/*
 * xhtml (divs and font headder tags)
 */
function modChrome_footer($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
		<?php if ((bool) $module->showtitle) : ?>
			<h3><?php echo $module->title; ?></h3>
		<?php endif; ?>
			<?php echo $module->content; ?>
	<?php endif;
}

function modChrome_shakenup($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
		<?php if ((bool) $module->showtitle) : ?>
			<h2><?php echo $module->title; ?></h2>
		<?php endif; ?>
			<?php echo $module->content; ?>
	<?php endif;
}
?>
<?php 
function modChrome_contact($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
		<?php if ((bool) $module->showtitle) : ?>
			<h4><?php echo $module->title; ?></h4>
		<?php endif; ?>
			<?php echo $module->content; ?>
	<?php endif;
}
?>
<?php
function modChrome_rightleft($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
		<div class="moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
		<?php if ((bool) $module->showtitle) : ?>
			<h3><?php echo $module->title; ?></h3>
            <div class="blog-post-separator-small"></div>
		<?php endif; ?>
			<?php echo $module->content; ?>
		</div>
	<?php endif;
} ?>
