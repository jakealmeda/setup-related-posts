<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


$mytz = new SRPDisplayPosts();
class SRPDisplayPosts {

	/**
	 * Handle the display
	 */
	public function __construct() {

		if( !is_admin() ) {

			add_action( 'wp', array( $this, 'srp_main_repeater' ) );

		}

	}


	/**
	 * Repeater function
	 */
	public function srp_main_repeater() {

		// Check rows exists.
		if( have_rows( 'rp-entries', 'option' ) ):

		    // Loop through rows.
		    while( have_rows( 'rp-entries', 'option' ) ) : the_row();

		    	if( get_sub_field( 'rp-disable' ) === FALSE ) :

					$args = array(
						'fields'			=> get_sub_field( 'rp-show-fields' ),
						'max'				=> get_sub_field( 'rp-max-entries' ),
						'position'			=> get_sub_field( 'rp-time-stamp' ),
						'template'			=> get_sub_field( 'rp-template' ),
						'class'				=> get_sub_field( 'rp-class' ),
						'style'				=> get_sub_field( 'rp-style' ),
						'order'				=> get_sub_field( 'rp-order' ),
					);

					add_action( get_sub_field( 'rp-hook' ), function() use ( $args ) {

						$atts = array(
							'fields'			=> $args[ 'fields' ],
							'max'				=> $args[ 'max' ],
							'position'			=> $args[ 'position' ],
							'template'			=> $args[ 'template' ],
							'order'				=> $args[ 'order' ],
						);

						$container_class = $args[ 'class' ];
						$container_style = $args[ 'style' ];
						if( !empty( $container_class ) && !empty( $container_style ) ) {
							$opening = '<div class="'.$container_class.'" style="'.$container_style.'">';
						} else {

							if( !empty( $container_class ) ) {
								$opening = '<div class="'.$container_class.'">';
							} elseif( !empty( $container_style ) ) {
								$opening = '<div style="'.$container_style.'">';
							} else {
								$opening = '<div>';
							}

						}

						// output
						$data_out = $this->srp_related_posts( $atts );
						if( !empty( $data_out ) ) {
							echo $opening;
								echo $data_out;
							echo '</div>';	
						}
						

					});

				endif;

		    // End loop.
		    endwhile;

		// No value.
		// else :
		    // Do something...
		endif;
	}


	/**
	 * Handle repeater entries
	 */
	public function srp_related_posts( $atts ) {

		// https://wordpress.stackexchange.com/questions/182783/finding-the-next-5-posts
		
		$post_object = get_queried_object();
//		$terms = wp_get_post_terms( $post_object->ID, 'category', array( 'fields' => 'ids' ) ); // Set fields to get only term ID's to make this more effient

		$args = array(
//			'cat' => $terms[0],
			'posts_per_page' 				=> !empty( $atts[ 'max' ] ) ? $atts[ 'max' ] : 5,
			'no_found_rows' 				=> true,   // Get 5 poss and bail. Make our query more effiecient
			'suppress_filters' 				=> true,  // We don't want any filters to alter this query
			'date_query' 					=> array(
				array(
					$atts[ 'position' ] 	=> $post_object->post_date,  // Get posts after the current post, use current post post_date
					'inclusive' 			=> false, // Don't include the current post in the query
				)
			),
			'orderby' 						=> 'date',
			'order' 						=> ( $atts[ 'position' ] == 'after' ) ? 'ASC' : 'DESC', // if not set to ASC, it will always get the very latest post entry and not the entries closest to the current post. BUT we have to reverse it
		);

		$loop = new WP_Query( $args );

		if( $loop->have_posts() ):

			$out = '';

			// get all post IDs
			while( $loop->have_posts() ): $loop->the_post();

				$post_ids[] = get_the_ID();

			endwhile;

			/* Restore original Post Data 
			 * NB: Because we are using new WP_Query we aren't stomping on the 
			 * original $wp_query and it does not need to be reset.
			 */
			wp_reset_postdata();

		endif;
		
		if( isset( $post_ids ) && count( $post_ids ) >= 1 ) :

			global $bars;

			$bars[ 'field_control' ] = $atts[ 'fields' ];

			$return = '';

			/* POSITION
			 * after : Newer
			 * before : Older
			 */
			/*if( $atts[ 'position' ] == 'after' ) {
				// reverse array contents as wp_query arranged it in ASC order
				$post_ids = array_reverse( $post_ids );
			}*/
			if( $atts[ 'order' ] ) {
				$post_ids = array_reverse( $post_ids );
			}

			foreach( $post_ids as $pids ) {
				//$return .= get_the_title( $pids ).'<br />';
				$bars[ 'pid' ] = $pids;

				$return .= $this->setup_rp_view_template( $atts[ 'template' ], 'views' );
			}

			return $return;

		endif;

	}


	/**
	 * Get VIEW template
	 */
	public function setup_rp_view_template( $layout, $dir_ext ) {

		$o = new SetupRelatedPosts();

		$layout_file = $o->setup_plugin_dir_path().'templates/'.$dir_ext.'/'.$layout;

		if( is_file( $layout_file ) ) {

			ob_start();

			include $layout_file;

			$new_output = ob_get_clean();

			if( !empty( $new_output ) ) {
				$output = $new_output;
			} else {
				$output = FALSE;
			}


		} else {

			$output = FALSE;

		}

		return $output;

	}


	/**
	 * Array validation
	 */
	public function setup_array_validation( $needles, $haystacks, $args = FALSE ) {

		if( is_array( $haystacks ) && array_key_exists( $needles, $haystacks ) && !empty( $haystacks[ $needles ] ) ) {

			return $haystacks[ $needles ];

		} else {

			return FALSE;

		}

	}


	/**
	 * Field Control Array Validation
	 */
	public function setup_field_control_validation( $field, $array ) {

		if( is_array( $array ) ) {

			if( in_array( $field, $array ) ) {
				return TRUE;
			} else {
				return FALSE;
			}

		} else {

			return FALSE;

		}

	}


	/**
	 * Combine Classes for the template
	 */
	public function setup_combine_classes( $classes ) {

		$block_class = !empty( $classes[ 'block_class' ] ) ? $classes[ 'block_class' ] : '';
		$item_class = !empty( $classes[ 'item_class' ] ) ? $classes[ 'item_class' ] : '';
		$manual_class = !empty( $classes[ 'manual_class' ] ) ? $classes[ 'manual_class' ] : '';

		$return = '';

		$ar = array( $block_class, $item_class, $manual_class );
		for( $z=0; $z<=( count( $ar ) - 1 ); $z++ ) {

			if( !empty( $ar[ $z ] ) ) {

				$return .= $ar[ $z ];

				if( $z != ( count( $ar ) - 1 ) ) {
					$return .= ' ';
				}

			}

		}

		return $return;

	}


	/**
	 * Combine Classes for the template
	 */
	public function setup_combine_styles( $styles ) {

		$manual_style = !empty( $styles[ 'manual_style' ] ) ? $styles[ 'manual_style' ] : '';
		$item_style = !empty( $styles[ 'item_style' ] ) ? $styles[ 'item_style' ] : '';

		if( !empty( $manual_style ) && !empty( $item_style ) ) {
			return $manual_style.' '.$item_style;
		} else {

			if( empty( $manual_style ) && !empty( $item_style ) ) {
				return $item_style;
			} else {
				return $manual_style;
			}

		}

	}


	/**
	 * Apply filters to WP-CONTENT
	 */
	public function setup_pull_apply_filters_to_content( $pid ) {

		$content = get_the_content( NULL, FALSE, $pid );
		/**
		 * Filters the post content.
		 *
		 * @since 0.71
		 *
		 * @param string $content Content of the current post.
		 */
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		return $content;

	}

}