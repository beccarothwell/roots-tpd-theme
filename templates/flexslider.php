<?php
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
		
<div class="flex-container">
	<div class="flexslider">
			<ul class="slides">
			<?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
				<li><?php the_content(); ?>
				</li><!-- #post-<?php the_ID(); ?> -->
			<?php endwhile; ?>
			</ul><!-- .slides -->
		</div><!-- .flexslider -->
	</div><!-- .flex-container -->
  
<?php endif; // End check for published posts. ?>
<?php endif; // End check for sticky posts. ?>