<?php
$number = 0;
/**
 * The template for featured posts.
 */

	/**
	 * Begin the featured posts section.
	 *
	 * See if we have any sticky posts and use them to create our featured posts.
	 */

	// Proceed only if sticky posts exist.
	if ( featuring_posts() ) :

		// The Featured Posts query - They need to be sticky post and video post format
		$featured_args = array(
			'post__in'            => featuring_posts(),
			'posts_per_page'      => 10,
			'no_found_rows'       => true,
			'ignore_sticky_posts' => 1
		);
		$featured = new WP_Query( $featured_args );

		// Proceed only if published posts exist
		if ( $featured->have_posts() ) : ?>
		
<div id="video-carousel" class="carousel slide">
  <!-- Indicators -->
  <ol class="carousel-indicators">
  <?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
    <li data-target="#video-carousel" data-slide-to="<?php echo $number++; ?>"></li>
    <?php endwhile; ?>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
  <?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
    <div class="item">
		<div class="carousel-caption">
			<h3>
			<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'roots-tpd-theme' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h3><!-- .entry-header -->
		<?php the_excerpt(); ?><!-- .featured-summary -->
		</div>
		<div class="carousel-content">
			<?php the_content(); ?>
		</div>
		<!-- #post-<?php the_ID(); ?> -->
    </div>
    <?php endwhile; ?>
  </div>
<?php endif; // End check for published posts. ?>
<?php endif; // End check for sticky posts. ?>
  <!-- Controls -->
  <a class="left carousel-control" href="#video-carousel" data-slide="prev">
    <span class="icon-prev"></span>
  </a>
  <a class="right carousel-control" href="#video-carousel" data-slide="next">
    <span class="icon-next"></span>
  </a>
</div>