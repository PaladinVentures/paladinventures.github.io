<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$count = count($items);
?>
<?php
 if( $ajaxRequest ){
	 ?>
<?php if( $count>0 ){
		?>
    <?php 
		$j=$start+1;
		foreach($items as $index=>$item){ 
		
		?>
    <li id="portfolio<?php echo $j; ?>" class="portfolio-item col-2 <?php echo modSPPortfolioJHelper::slug($item->tag); ?> visible"> <img src="<?php echo $item->image; ?>" alt="">
      <div class="portfolio-item-hover">
        <div class="portfolio-item-frame"></div>
        <div class="portfolio-item-text">
          <h4><?php echo $item->title; ?></h4>
          <div class="item-separator"></div>
          <h5><?php echo $item->tag; ?></h5>
        </div>
      </div>
      <div class="portfolio-full-content">
        <div class="portfolio-additional-photos">
          <p><?php echo $item->image_full; ?></p>
        </div>
        <div class="portfolio-full-text"> <?php echo $item->introtext; ?> </div>
        <div class="portfolio-related">
          <p>portfolio1</p>
          <p>portfolio2</p>
          <p>portfolio3</p>
          <p>portfolio4</p>
          <p>portfolio5</p>
          <p>portfolio6</p>
          <p>portfolio7</p>
          <p>portfolio8</p>
          <p>portfolio9</p>
          <p>portfolio10</p>
          <p>portfolio11</p>
          <p>portfolio12</p>
        </div>
      </div>
    </li>
    <?php $j++; } //end foreach ?>
<?php } ?>
<?php jexit(); ?>
<?php } ?>
<!--/Ajax Load-->

<div id="portfolio-module-<?php echo $uniqid; ?>" class="sp-portfolio default">
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
  <div id="portfolio-full-item">
    <div id="portfolio-full-item-holder">
      <div id="portfolio-full-item-inner" class="clearfix">
        <div id="portfolio-full-item-text" class="col-3">
          <h4></h4>
          <p></p>
        </div>
        <div id="portfolio-full-item-image" class="col-6">
          <div id="portfolio-full-item-navigation"> <a href="#" class="arrow-left"><i class="fa fa-arrow-left"></i></a> <a href="#" class="arrow-right"><i class="fa fa-arrow-right"></i></a> </div>
          <img src="#" alt=""> </div>
        <div id="portfolio-full-item-close" class="col-3"> <img src="images/close-button.png" alt=""> </div>
        <div class="clearfix"></div>
        <div class="separator-100"></div>
        <div id="portfolio-full-item-additional"> </div>
      </div>
      <div class="separator-100"></div>
      <div id="portfolio-full-item-related">
        <h2>Related projects</h2>
        <div id="portfolio-full-item-projects"> </div>
      </div>
    </div>
  </div>
  <?php if($count>0) { ?>
    <ul id="portfolio-holder" class="portfolio-items">
      <?php 
			$i=1;
			foreach($items as $index=>$item){ ?>
      <li id="portfolio<?php echo $i; ?>" class="portfolio-item col-2 <?php echo modSPPortfolioJHelper::slug($item->tag); ?> visible"> <img src="<?php echo $item->image; ?>" alt="">
        <div class="portfolio-item-hover">
          <div class="portfolio-item-frame"></div>
          <div class="portfolio-item-text">
            <h4><?php echo $item->title; ?></h4>
            <div class="item-separator"></div>
            <h5><?php echo $item->tag; ?></h5>
          </div>
        </div>
        <div class="portfolio-full-content">
          <div class="portfolio-additional-photos">
            <p><?php echo $item->image_full; ?></p>
          </div>
          <div class="portfolio-full-text"> <?php echo $item->introtext; ?> </div>
          <div class="portfolio-related">
            <p>portfolio1</p>
            <p>portfolio2</p>
            <p>portfolio3</p>
            <p>portfolio4</p>
            <p>portfolio5</p>
            <p>portfolio6</p>
            <p>portfolio7</p>
            <p>portfolio8</p>
            <p>portfolio9</p>
            <p>portfolio10</p>
            <p>portfolio11</p>
            <p>portfolio12</p>
          </div>
        </div>
      </li>
      <?php $i++; }  ?>
    </ul>
  
  <!--/.sp-portfolio-items-->
  
  <?php if(($ajax_loader && $show_filter) && ($count!=$total)) { ?>
  <a href="#" class="port-more-button"> <i class="icon-spinner icon-spin"></i> <span>Load More</span> </a>
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
				var $gallery = jQuery('.portfolio-items');
				var jsnocont=jQuery.noConflict();
								
				$gallery.isotope({
					// options
					itemSelector : '.portfolio-item',
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

				jQuery('a.port-more-button').on('click', function(e){
					
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
