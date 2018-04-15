<?php
/*
# SP Portfolio - Simple Portfolio module by JoomShaper.com
# -------------------------------------------------------------
# Author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2013 JoomShaper.com. All Rights Reserved.
# @license - GNU/GPL V2 or Later
# Websites: http://www.joomshaper.com
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$count = count($items);

if( $ajaxRequest ){
	if($count>0) {

		foreach($items as $index=>$item){ ?>
		<li class="sp-portfolio-item col-<?php echo $column . ' ' . modSPPortfolioJHelper::slug($item->tag); ?>">
			<div class="sp-portfolio-item-inner">

				<div class="sp-portfolio-thumb">
					<img src="<?php echo $item->image; ?>">
					<a class="sp-portfolio-preview" rel="lightbox" title="<?php echo $item->title; ?>" href="<?php echo $item->image_full; ?>"></a>
					<a class="sp-portfolio-link" href="<?php echo $item->link; ?>"></a>
				</div>

				<div class="sp-portfolio-item-details">
					<?php if($show_title) : ?>
					<?php if($linked_title) : ?>
					<a href="<?php echo $item->link; ?>">
					<?php endif; ?>
					<h4><?php echo $item->title; ?></h4>
					<?php if($linked_title) : ?>
				</a>
			<?php endif; ?>
		<?php endif; ?>

		<?php if(($item->urls->urla!='') && ($show_url)) : ?>
		<a href="<?php echo $item->urls->urla; ?>"><?php echo $item->urls->urlatext; ?></a>
	<?php endif; ?>

	<?php if($show_introtext) : ?>
	<div class="sp-portfolio-introtext">
		<?php echo $item->introtext; ?>
	</div>
<?php endif; ?>

<?php if($show_readmore) : ?>
	<a class="btn btn-primary" href="<?php echo $item->link; ?>"><?php echo jText::_('MORE'); ?></a>
<?php endif; ?>
</div>
<div style="clear:both"></div>	
</div>
</li>
<?php }
}
jexit();
} ?>

<div id="sp-portfolio-module-<?php echo $uniqid; ?>" class="sp-portfolio maxima">

	<?php if($count>0) { ?>

	<?php if($show_filter) : ?>
	<ul class="sp-portfolio-filter">
		<li><a class="btn active" href="#" data-filter="*"><?php echo JText::_('Show All'); ?></a></li>
		<?php foreach (modSPPortfolioJHelper::getCategories($catid) as $key => $value) { ?>
		<li>
			<a class="btn" href="#" data-filter=".<?php echo modSPPortfolioJHelper::slug($value) ?>">
				<?php echo ucfirst(trim($value)) ?>
			</a>
		</li>
		<?php } ?>
	</ul>
<?php endif; ?>

<?php } ?>

<?php
if($count>0) {
	?>
	<ul class="sp-portfolio-items">
		<?php
		foreach($items as $index=>$item) {
			?>
			<li class="sp-portfolio-item col-<?php echo $column . ' ' . modSPPortfolioJHelper::slug($item->tag); ?> visible">
				<div class="sp-portfolio-item-inner">

					<div class="sp-portfolio-thumb">
						<img src="<?php echo $item->image; ?>">
						<a class="sp-portfolio-preview" rel="lightbox" title="<?php echo $item->title; ?>" href="<?php echo $item->image_full; ?>"></a>
						<a class="sp-portfolio-link" href="<?php echo $item->link; ?>"></a>
					</div>

					<div class="sp-portfolio-item-details">
						<?php if($show_title) : ?>
						<?php if($linked_title) : ?>
						<a href="<?php echo $item->link; ?>">
						<?php endif; ?>
						<h4><?php echo $item->title; ?></h4>
						<?php if($linked_title) : ?>
					</a>
				<?php endif; ?>
			<?php endif; ?>

			<?php if(($item->urls->urla!='') && ($show_url)) : ?>
			<a href="<?php echo $item->urls->urla; ?>"><?php echo $item->urls->urlatext; ?></a>
		<?php endif; ?>

		<?php if($show_introtext) : ?>
		<div class="sp-portfolio-introtext">
			<?php echo $item->introtext; ?>
		</div>
	<?php endif; ?>

	<?php if($show_readmore) : ?>
	<a class="btn btn-primary" href="<?php echo $item->link; ?>"><?php echo jText::_('MORE'); ?></a>
<?php endif; ?>

</div>
<div style="clear:both"></div>	
</div>
</li>
<?php
}
?>
</ul>

<?php if(($ajax_loader && $show_filter) && ($count!=$total)) { ?>
<div class="sp-portfolio-loadmore">
	<a href="#" class="btn btn-primary btn-large">
		<i class="icon-spinner icon-spin"></i>
		<span>Load More</span>
	</a>
</div>
<?php } ?>

<?php
} else {
	?>
	<p class="alert">No item found!</p>
	<?php
}
?>
</div>

<?php if ($show_filter) : ?>
	<script type="text/javascript">
		jQuery.noConflict();
		jQuery(function($){
			$(window).load(function(){
				var $gallery = $('.sp-portfolio-items');
				
				<?php if($rtl) { ?>
					// RTL Support
					$.Isotope.prototype._positionAbs = function( x, y ) {
						return { right: x, top: y };
					};
				<?php } ?>

				$gallery.isotope({
					// options
					itemSelector : 'li',
					layoutMode : 'fitRows'
					<?php if($rtl) { ?>
						,transformsEnabled: false
					<?php } ?>	
				});
				
				$filter = $('.sp-portfolio-filter');
				$selectors = $filter.find('>li>a');
					
				$filter.find('>li>a').click(function(){
					var selector = $(this).attr('data-filter');
					
					$selectors.removeClass('active');
					$(this).addClass('active');
					
					$gallery.isotope({ filter: selector });
					return false;
				});

				var $currentURL = '<?php echo  JURI::getInstance()->toString(); ?>';
				var $start = <?php echo $limit ?>;  // ajax start from last limit
				var $limit = <?php echo $ajaxlimit ?>;
				var $totalitem = <?php echo $total ?>;

				$('.sp-portfolio-loadmore > a').on('click', function(e){
					var $this = $(this);
					$this.removeClass('done').addClass('loading');
					$.get($currentURL, { moduleID: <?php echo $uniqid?>, start:$start, limit: $limit }, function(data){

						$start += $limit;

						var $newItems = $(data);
						$gallery.isotope( 'insert', $newItems );

						if( $totalitem <= $start ){
							$this.removeClass('loading').addClass('hide');

							// AUTOLOAD CODE BLOCK (MAY BE CHANGED OR REMOVED)
							if (!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
								jQuery(function($) {
								$("a[rel^='lightbox']").slimbox({/* Put custom options here */}, null, function(el) {
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
								jQuery(function($) {
								$("a[rel^='lightbox']").slimbox({/* Put custom options here */}, null, function(el) {
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
<?php endif; ?>