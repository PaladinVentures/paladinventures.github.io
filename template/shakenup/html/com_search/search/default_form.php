<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');

$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();
?>

<form id="searchForm"  action="<?php echo JRoute::_('index.php?option=com_search');?>" method="post">
 
		<fieldset>
        <section>
		<div class="row">
        <div class="col col-6">
		  <label class="input">
			<input type="text" name="searchword" placeholder="<?php echo JText::_('COM_SEARCH_SEARCH_KEYWORD'); ?>" id="search-searchword" size="30" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $this->escape($this->origkeyword); ?>" class="inputbox" />
          </label>
        </div>
        
        <div class="col col-6">
        
           <button class="load-more-button" name="Search" onclick="this.form.submit()" value="<?php echo JHtml::tooltipText('COM_SEARCH_SEARCH');?>" title="<?php echo JHtml::tooltipText('COM_SEARCH_SEARCH');?>"><?php echo JHtml::tooltipText('COM_SEARCH_SEARCH');?></button>
        
        </div>
		</div>
        </section>
		</fieldset>
		<input type="hidden" name="task" value="search" />
	
    <div class="searchintro<?php echo $this->params->get('pageclass_sfx'); ?>">
		<?php if (!empty($this->searchword)):?>
		<p><?php echo JText::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', '<span class="badge badge-info">'. $this->total. '</span>');?></p>
		<?php endif;?>
	</div>

	<fieldset>
		   <section>
					<div class="row">
					<label class="col col-4">
           				 <?php echo JText::_('COM_SEARCH_FOR');?>
                    </label>
                    <div class="col col-8">
                     <label class="radio">
		  				<?php echo $this->lists['searchphrase']; ?>
                     </label>
                    </div>
                   </div>
           </section>
			
			
            <section>
					<div class="row">
					<label class="col col-4">
           				<?php echo JText::_('COM_SEARCH_ORDERING');?>
                    </label>
                    <div class="col col-4">
                    
		  				<?php echo $this->lists['ordering'];?>
                    </div>
                   </div>
           </section>
	
			
	</fieldset>

	<?php if ($this->params->get('search_areas', 1)) : ?>
		 <section>
					<div class="row">
					<label class="col col-4">
					<?php echo JText::_('COM_SEARCH_SEARCH_ONLY');?>
                    </label>
                   
                    <div class="col col-8">
		<?php foreach ($this->searchareas['search'] as $val => $txt) :
			$checked = is_array($this->searchareas['active']) && in_array($val, $this->searchareas['active']) ? 'checked="checked"' : '';
		?>
                     
                     <label for="area-<?php echo $val;?>">
					<input type="checkbox" name="areas[]" value="<?php echo $val;?>" id="area-<?php echo $val;?>" <?php echo $checked;?> >
                    </label>
                   
			<?php echo JText::_($txt); ?>
	
		<?php endforeach; ?>
         </div>
        </div>
        </section>
		
	<?php endif; ?>

<?php if ($this->total > 0) : ?>

	 <section>
					<div class="row">
					<label class="col col-4">
			       <?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
		           </label>
         <div class="col col-4">          
		<?php echo $this->pagination->getLimitBox(); ?>
	     

		<?php echo $this->pagination->getPagesCounter(); ?>
           </div>
           </div>
        </section>

<?php endif; ?>

</form>



