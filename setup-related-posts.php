<?php
/**
 * Plugin Name: Setup Related Posts
 * Description: Display posts that are related to the current entry being viewed.
 * Version: 1.0
 * Author: Jake Almeda
 * Author URI: https://smarterwebpackages.com/
 * Network: true
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


// include required functions that needs to be executed in the main directory
class SetupRelatedPosts {

    // simply return this plugin's main directory
    public function setup_plugin_dir_path() {

        return plugin_dir_path( __FILE__ );

    }

    // hook list
    public $genesis_hooks = array(
        'genesis_before',
        'genesis_before_header',
        'genesis_header',
        'genesis_site_title',
        'genesis_header_right',
        'genesis_site_description',
        'genesis_after_header',
        'genesis_before_content_sidebar_wrap',
        'genesis_before_content',
        'genesis_before_loop',
        'genesis_before_sidebar_widget_area',
        'genesis_after_sidebar_widget_area',
        'genesis_loop',
        'genesis_before_entry',
        'genesis_entry_header',
        'genesis_entry_content',
        'genesis_entry_footer',
        'genesis_after_entry',
        'genesis_after_endwhile',
        'genesis_after_loop',
        'genesis_after_content',
        'genesis_after_content_sidebar_wrap',
        'genesis_before_footer',
        'genesis_footer',
        'genesis_after_footer',
        'genesis_after',
    );
    /*
    // list of local fields to pull
    public function setup_block_fields() {

        return array(
            'title'             => 'Title',
            'summary'           => 'Summary',
            'innerblocks'       => 'InnerBlocks'
        );

    }

    // list of local default fields
    public function setup_block_default_fields() {
        //return array( 'title', 'content' );
        return array( 'title' );
    }

    // list of media fields to pull
    public function setup_block_fields_media() {

        return array(
            'image'             => 'Image',
            'video'             => 'Video',
        );

    }

    // list of media default fields
    public function setup_block_default_fields_media() {
        //return array( 'title', 'content' );
        return array( 'image' );
    }

    // enqueue
    public function setup_block_enqueue_scripts() {

        // enqueue styles
        wp_enqueue_style( 'setupblocksstyle', plugin_dir_url( __FILE__ ).'css/style.css' );

    }

    // Construct
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'setup_block_enqueue_scripts' ), 2000 );
    }
    */
}

// include files
//include_once( 'lib/setup-blocks-generator.php' );
include_once( 'lib/setup-related-acf.php' );
include_once( 'lib/setup-related-functions.php' );
