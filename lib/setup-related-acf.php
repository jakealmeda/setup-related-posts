<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page(array(
        'page_title'    => 'Related Posts',
        'menu_title'    => 'Related Posts',
        'menu_slug'     => 'related-posts',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
    
    /*acf_add_options_sub_page(array(
        'page_title'    => 'Settings',
        'menu_title'    => 'Header',
        'parent_slug'   => 'theme-general-settings',
    ));
    
    acf_add_options_sub_page(array(
        'page_title'    => 'Theme Footer Settings',
        'menu_title'    => 'Footer',
        'parent_slug'   => 'theme-general-settings',
    ));*/
    
}


/**
 * Auto fill Select options | HOOKS
 *
 */
add_filter( 'acf/load_field/name=rp-hook', 'srp_autofill_hooks' );
function srp_autofill_hooks( $field ) {

    $hookers = new SetupRelatedPosts();

    $field['choices'] = array();

    //Loop through whatever data you are using, and assign a key/value
    if( is_array( $hookers->genesis_hooks ) ) {

        foreach( $hookers->genesis_hooks as $value ) {

            $field['choices'][$value] = $value;
        }

        return $field;

    }

}


/**
 * Auto fill Select options | TEMPLATES
 *
 */
add_filter( 'acf/load_field/name=rp-template', 'rp_template_choices' );
function rp_template_choices( $field ) {
    
    $z = new SetupRelatedPosts();

    $file_extn = 'php';

    // get all files found in VIEWS folder
    $view_dir = $z->setup_plugin_dir_path().'templates/views/';

    $data_from_dir = setup_pulls_view_files( $view_dir, $file_extn );

    $field['choices'] = array();

    //Loop through whatever data you are using, and assign a key/value
    if( is_array( $data_from_dir ) ) {

        foreach( $data_from_dir as $field_key => $field_value ) {
            $field['choices'][$field_key] = $field_value;
        }

        return $field;

    }
    
}


/**
 * Pull all files found in $directory but get rid of the dots that scandir() picks up in Linux environments
 *
 */
if( !function_exists( 'setup_pulls_view_files' ) ) {

    function setup_pulls_view_files( $directory, $file_extn ) {

        $out = array();
        
        // get all files inside the directory but remove unnecessary directories
        $ss_plug_dir = array_diff( scandir( $directory ), array( '..', '.' ) );

        foreach( $ss_plug_dir as $filename ) {
            
            if( pathinfo( $filename, PATHINFO_EXTENSION ) == $file_extn ) {
                $out[ $filename ] = pathinfo( $filename, PATHINFO_FILENAME );
            }

        }

        /*foreach ($ss_plug_dir as $value) {
            
            // combine directory and filename
            $file = basename( $directory.$value, $file_extn );
            
            // filter files to include
            if( $file ) {
                $out[ $value ] = $file;
            }

        }*/

        // Return an array of files (without the directory)
        return $out;

    }
    
}
