<style type="text/css">
.blogmasonary {
	margin-bottom: 100px;
}
</style>
<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$count = count($items);
?>
<?php

 if( $ajaxRequest ){
	 ?>
<?php if( $count>0 ){ ?>
<ul class="sp-portfolio-items">
  <?php foreach($items as $index=>$item){
		 ?>
  <div class="blog-item col-6 <?php echo modSPPortfolioJHelper::slug($item->tag); ?> visible">
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
  <?php } //end foreach ?>
</ul>
<?php } ?>
<?php jexit(); ?>
<?php } ?>
<!--/Ajax Load-->

<div id="sp-portfolio-module-<?php echo $uniqid; ?>" class="sp-portfolio default">
  <?php if($count>0){ ?>
  <?php if($show_filter){ ?>
  <div class="filter-wrapper">
    <div class="filter-holder"> <a class="filter-button button-active" href="#" data-filter="*"><?php echo JText::_('Show All'); ?></a>
      <?php foreach (modSPPortfolioJHelper::getCategories($catid) as $key => $value) { ?>
      <a class="filter-button" href="#" data-filter=".<?php echo modSPPortfolioJHelper::slug($value) ?>"> <?php echo ucfirst(trim($value)) ?> </a>
      <?php } ?>
    </div>
  </div>
  <?php } ?>
  <?php } ?>
  <?php if($count>0) { ?>
  <ul class="sp-portfolio-items">
    <?php foreach($items as $index=>$item){ ?>
    <div class="blog-item col-6 <?php echo modSPPortfolioJHelper::slug($item->tag); ?> visible">
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
  </ul>
  <!--/.sp-portfolio-items-->
  
  <?php if(($ajax_loader && $show_filter) && ($count!=$total)) { ?>
  <a href="#" class="load-more-button"> <i class="icon-spinner icon-spin"></i> <span>Load More</span> </a>
  <?php } ?>
  <?php } else { ?>
  <p class="alert">No item found!</p>
  <?php } ?>
</div>
<!--/.sp-portfolio-->

<?php if ($show_filter){ ?>
<script type="text/javascript">
		jQuery.noConflict();
		jQuery(function(jQuery){
			jQuery(window).load(function(){
				var $gallery = jQuery('.sp-portfolio-items');
				
				$gallery.isotope({
					// options
					itemSelector : '.blog-item',
					layoutMode : 'fitRows'
					
				});
				
				$filter = jQuery('.filter-wrapper');
				$selectors = $filter.find('a');
					
				$filter.find('a').click(function(){
					var selector = jQuery(this).attr('data-filter');
					
					$selectors.removeClass('button-active');
					jQuery(this).addClass('button-active');
					
					$gallery.isotope({ filter: selector });
					return false;
				});

				var $currentURL = '<?php echo  JURI::getInstance()->toString(); ?>';
				var $start = <?php echo $limit ?>;  // ajax start from last limit
				var $limit = <?php echo $ajaxlimit ?>;
				var $totalitem = <?php echo $total ?>;

				jQuery('a.load-more-button').on('click', function(e){
					var $this = jQuery(this);
					$this.removeClass('done').addClass('loading');
					jQuery.get($currentURL, { moduleID: <?php echo $uniqid?>, start:$start, limit: $limit }, function(data){

						$start += $limit;

						var $newItems = jQuery(data);
						$gallery.isotope( 'insert', $newItems );

						if( $totalitem <= $start ){
							$this.removeClass('loading').addClass('hide');

							// AUTOLOAD CODE BLOCK (MAY BE CHANGED OR REMOVED)
							if (!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
								jQuery(function(jQuery) {
								jQuery("a[rel^='lightbox']").slimbox({/* Put custom options here */}, null, function(el) {
									return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
								});
							});
							}
							////

							return false;
						} else {
							$this.removeClass('loading').addClass('done');
							////

							// AUTOLOAD CODE BLOCK (MAY BE CHANGED OR REMOVED)
							if (!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
								jQuery(function(jQuery) {
								jQuery("a[rel^='lightbox']").slimbox({/* Put custom options here */}, null, function(el) {
									return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
								});
							});
							}

						}

						});

					return false;
				});

			});
		});
	</script>
<?php }
?>
<div class="separator-100"></div>
