<?php

add_action('after_setup_theme', 'mytheme_theme_setup');

if ( ! function_exists( 'mytheme_theme_setup' ) ){
    function mytheme_theme_setup(){
        add_action( 'wp_enqueue_scripts', 'mytheme_scripts');
    }
}

if ( ! function_exists( 'mytheme_scripts' ) ){
    function mytheme_scripts() {
        // CSS
        wp_enqueue_style( 'theme_css', get_template_directory_uri().'/css/main.css' );
        wp_enqueue_style( 'custom_css', get_template_directory_uri().'/css/custom.css' );

        // Scripts
        wp_enqueue_script( 'theme_js', get_template_directory_uri().'/js/libs/jquery-3.6.0.min.js', array( 'jquery'), '1.0.0', true );
        wp_enqueue_script( 'theme_js_2', get_template_directory_uri().'/js/libs/jquery.scrollbar.min.js', array( 'jquery'), '1.0.0', true );
        wp_enqueue_script( 'theme_js_3', get_template_directory_uri().'/js/libs/ion.rangeSlider.min.js', array( 'jquery'), '1.0.0', true );
        wp_enqueue_script( 'theme_js_4', get_template_directory_uri().'/js/libs/jquery.magnific-popup.min.js', array( 'jquery'), '1.0.0', true );
        wp_enqueue_script( 'theme_js_5', get_template_directory_uri().'/js/libs/swiper-bundle.min.js', array( 'jquery'), '1.0.0', true );
        wp_enqueue_script( 'theme_js_6', get_template_directory_uri().'/js/main.js', array( 'jquery'), '1.0.0', true );

        wp_localize_script( 'theme_js_6', 'ajax_object', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'directory_uri' => get_template_directory_uri(),
            'bloginfo_url' => get_bloginfo('url'),
        ));
    }
}


/**
 * Registers a custom post type 'slider'.
 *
 * This function sets up a custom post type called 'slider' with specified labels,
 * support for title, editor, and thumbnail, and a custom menu icon. The post type
 * is public but does not have an archive.
 *
 * @see register_post_type()
 */

function create_slider_post_type() {
    register_post_type('slider',
        array(
            'labels'      => array(
                'name'          => __('Слайдери', 'textdomain'),
                'singular_name' => __('Слайдер', 'textdomain'),
            ),
            'public'      => true,
            'has_archive' => false,
            'supports'    => array('title', 'editor', 'thumbnail'),
            'menu_icon'   => 'dashicons-images-alt2',
        )
    );
}
add_action('init', 'create_slider_post_type');

add_theme_support('post-thumbnails');


/**
 * Handles AJAX request to get slider content by ID.
 *
 * Checks if POST request contains valid 'id' parameter, fetches the post with that ID,
 * and sends back the post title and content in JSON format.
 *
 * @return WP_Error|WP_HTTP_Response
 */
function get_slider_content() {
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        wp_send_json_error("Невірний ID слайда");
    }

    $post_id = intval($_POST['id']);
    $post = get_post($post_id);

    if ($post) {
        $response = array(
            'title'   => get_the_title($post),
            'content' => apply_filters('the_content', $post->post_content),
        );
        wp_send_json_success($response);
    } else {
        wp_send_json_error("Слайд не знайдено");
    }
}
add_action('wp_ajax_get_slider_content', 'get_slider_content');
add_action('wp_ajax_nopriv_get_slider_content', 'get_slider_content');