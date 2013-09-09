<?php
/**
 * Custom functions
 */
 if ( ! function_exists( 'tpd_setup' ) ):
/**
 * Sets up video post formats.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs before the init hook. The init hook is too late for some features, such as indicating support post thumbnails.
 */
function tpd_setup() {

	/**
	 * Add support for Video Post Formats
	 */
	add_theme_support( 'post-formats', array( 'video' ) );
}
endif; // peskydames_setup
add_action( 'after_setup_theme', 'tpd_setup' );

/**
 * Filter the home page posts, and remove any featured post ID's from it. Hooked onto the 'pre_get_posts' action, this changes the parameters of the query before it gets any posts.
 *
 * @global array $featured_post_id
 * @param WP_Query $query
 * @return WP_Query Possibly modified WP_query
 */
function tpd_home_posts( $query = false ) {

	// Bail if not home, not a query, not main query, or no featured posts
	if ( ! is_home() || ! is_a( $query, 'WP_Query' ) || ! $query->is_main_query() || ! featuring_posts() )
		return $query;

	// Exclude featured posts from the main query
	$query->query_vars['post__not_in'] = featuring_posts();

	return $query;
}
add_action( 'pre_get_posts', 'tpd_home_posts' );

/**
 * Test to see if any posts meet our conditions for featuring posts.
 * Current conditions are:
 *
 * - sticky posts
 * - with featured thumbnails
 *
 * We store the results of the loop in a transient, to prevent running this extra query on every page load. The results are an array of post ID's that match the result above. This gives us a quick way to loop through featured posts again later without needing to query additional times later.
 */
function featuring_posts() {
	if ( false === ( $featured_post_ids = get_transient( 'featured_post_ids' ) ) ) {

		// Proceed only if sticky posts exist.
		if ( get_option( 'sticky_posts' ) ) {

			// The Featured Posts query - The need to be sticky post and video post format
			$featured_args = array(
				'post__in'       => get_option( 'sticky_posts' ),
				'post_status'    => 'publish',
				'tax_query'      => array( array(
					'taxonomy'   => 'post_format',
					'field'      => 'slug',
					'terms'      => array( 'post-format-video' )
				) ),
				'posts_per_page' => 10,
				'no_found_rows'  => true
			);

			$featured = new WP_Query( $featured_args );

			// Proceed only if published posts with thumbnails exist
			if ( $featured->have_posts() ) {
				while ( $featured->have_posts() ) {
					$featured->the_post();
					$featured_post_ids[] = $featured->post->ID;
				}

				set_transient( 'featured_post_ids', $featured_post_ids );
			}
		}
	}

	return $featured_post_ids;
}
/**
 * Flush out the transients used in featuring_posts()
 */
function featured_post_checker_flusher() {
	delete_transient( 'featured_post_ids' );
}
add_action( 'save_post', 'featured_post_checker_flusher' );
