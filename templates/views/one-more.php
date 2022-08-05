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

/**
 * CONTENT | START
 */

// WRAP | OPEN
echo '<div'.$classes.$inline_style.'>';

	// pull wp title
	echo '<div class="item-title-native">
			<a href="'.get_the_permalink( $pid ).'">'.get_the_title( $pid ).'</a>
		</div>';

// WRAP | CLOSE
echo '</div>';