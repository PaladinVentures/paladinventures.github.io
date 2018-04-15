<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$count = count($items);
?>
<div id="blog-portfolio-module-<?php echo $uniqid; ?>" class="blog-portfolio default">
  <?php if($count>0) { ?>
  <?php foreach($items as $index=>$item){ ?>
  <div class="blog-item col-<?php echo $column . ' ' . modSPPortfolioJHelper::slug($item->tag); ?> visible" data-scroll-reveal>
    <div class="blog-item-inner"> <a href="<?php echo $item->link; ?>"> <img src="<?php echo $item->image; ?>" alt="<?php echo $item->title; ?>">
      <div class="transparent-frame"></div>
      <h4><?php echo $item->title; ?></h4>
      <div class="blog-item-date clearfix">
        <div class="blog-item-date-inner">
          <h5><?php echo JHtml::_('date', $item->created, JText::_('l')); ?> <span><?php echo JHtml::_('date', $item->created, JText::_('d')); ?></span>TH</h5>
          <h5><?php echo JHtml::_('date', $item->created, JText::_('M')); ?></h5>
          <h5><span><?php echo JHtml::_('date', $item->created, JText::_('Y')); ?></span></h5>
        </div>
        <div class="blog-item-square"></div>
      </div>
      </a> </div>
  </div>
  <?php } ?>
  <?php } else { ?>
  <p class="alert">No item found!</p>
  <?php } ?>
</div>
<!--/.blog-portfolio-->


