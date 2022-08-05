<?php

global $bars;

$mfunc = new SRPDisplayPosts();
$pid = $mfunc->setup_array_validation( 'pid', $bars );

// class
$cs = array(
	'manual_class'		=> 'item-pullentry',
	//'item_class' 		=> $mfunc->setup_array_validation( 'wrap_sel', $bars ),
	//'block_class'		=> $mfunc->setup_array_validation( 'block_class', $bars ),
);
$css = $mfunc->setup_combine_classes( $cs );
$classes = !empty( $css ) ? ' class="'.$css.'"' : '';

// styles
$ss = array(
	'manual_style'		=> '',
	//'item_style' 		=> $mfunc->setup_array_validation( 'wrap_sty', $bars ),
);
$stayls = $mfunc->setup_combine_styles( $ss );
$inline_style = !empty( $stayls ) ? ' style="'.$stayls.'"' : '';

//$bsf = $mfunc->setup_array_validation( "fields", $bars );
/**
 * CONTENT | START
 */

// WRAP | OPEN
echo '<div'.$classes.$inline_style.'>';

	// wp-title
	$wp_title = get_the_title( $pid );
	if( !empty( $wp_title ) && !empty( $mfunc->setup_field_control_validation( 'title', $mfunc->setup_array_validation( "field_control", $bars ) ) ) ) {
		echo '<div class="item-title-native"><b>WP TITLE:</b> <a href="'.get_the_permalink( $pid ).'">'.$wp_title.'</a></div>';	
	}
	
	// wp-content
	$wp_content = $mfunc->setup_pull_apply_filters_to_content( $pid );
	if( !empty( $wp_content ) && !empty( $mfunc->setup_field_control_validation( 'content', $mfunc->setup_array_validation( "field_control", $bars ) ) ) ) {
		echo '<div class="item-content-native"><b>WP CONTENT:</b> ';
			echo $wp_content;
		echo '</div>';
	}

	// featured media/image
	$feat_img = get_the_post_thumbnail_url( $pid, "large" );
	if( !empty( $feat_img ) && !empty( $mfunc->setup_field_control_validation( 'featured_media', $mfunc->setup_array_validation( "field_control", $bars ) ) ) ) {
		echo '<div class="item-thumbnail"><b>FEATURED IMAGE:</b><br />
			<img src="'.$feat_img.'" border="0" />
		</div>';
	}

	// wp-excerpt
	$wp_excerpt = get_the_excerpt( $pid );
	if( !empty( $wp_excerpt ) && !empty( $mfunc->setup_field_control_validation( 'excerpt', $mfunc->setup_array_validation( "field_control", $bars ) ) ) ) {
		echo '<div class="item-excerpt"><b>WP EXCERPT:</b> '.$wp_excerpt.'</div>';
	}

	// date modified
	if( !empty( $mfunc->setup_field_control_validation( 'modified', $mfunc->setup_array_validation( "field_control", $bars ) ) ) ) {
		echo '<div class="item-modified"><b>MODIFIED:</b> '.get_the_modified_date( "F j, Y, g:i a", $pid ).'</div>';
	}
	
	// date published
	if( !empty( $mfunc->setup_field_control_validation( 'date_published', $mfunc->setup_array_validation( "field_control", $bars ) ) ) ) {
		echo '<div class="item-published"><b>PUBLISHED:</b> '.get_the_date( "F j, Y, g:i a", $pid ).'</div>';
	}

// WRAP | CLOSE
echo '</div>';