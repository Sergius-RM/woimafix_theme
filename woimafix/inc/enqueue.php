<?php
/**
 * Actions
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/*
 * Enqueue WP Styles to Header Part.
*/
function theme_styles()
{
    // Регистрирую стили
    wp_register_style('info_style', get_template_directory_uri() . '/style.css', '', '1.1', 'all');
    wp_register_style('main_style', get_template_directory_uri() . '/assets/css/style.css', '', '1.1', 'all');
    wp_register_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', '', '5.3', 'all');
    wp_register_style('bootstrap-icons', get_template_directory_uri() . '/assets/css/bootstrap-icons.css', '', '1.1', 'all');
    wp_register_style('fontawesome', get_template_directory_uri() . '/assets/css/font-awesome-5.9.0.min.css', '', '5.9.0', 'all');
    wp_register_style('leaflet', get_template_directory_uri() . '/assets/css/leaflet.css', '', '1', 'all');
    wp_register_style('animate', get_template_directory_uri() . '/assets/css/animate.css', '', '1', 'all');
    wp_register_style('tiny-slider', get_template_directory_uri() . '/assets/css/tiny-slider.css', '', '1', 'all');

    // Подключаю стили
    wp_enqueue_style('bootstrap');
    wp_enqueue_style('bootstrap-icons');
    wp_enqueue_style('info_style');
    wp_enqueue_style('fontawesome');
    wp_enqueue_style('leaflet');
    wp_enqueue_style('animate');
    wp_enqueue_style('tiny-slider');
    wp_enqueue_style('main_style');

};
// Создаем экшн в котором подключаем скрипты подключенные внутри функции theme_styles
add_action('wp_enqueue_scripts', 'theme_styles');

/*
 * Enqueue WP JS scripts to Footer Part.
*/
function theme_script() {
    // Подключаю скрипты с аргументом 'defer'
    wp_enqueue_script('jquery_script', get_template_directory_uri() . '/assets/js/jquery-3.6.0.min.js', array(), '', true);
    wp_enqueue_script('leaflet', get_template_directory_uri() . '/assets/js/leaflet.js', array(), '', false);
    wp_enqueue_script('bootstrap_script', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array(), '', true);
    wp_enqueue_script('tiny-slider', get_template_directory_uri() . '/assets/js/tiny-slider.js', array(), '', true);
    
    wp_enqueue_script('wow', get_template_directory_uri() . '/assets/js/wow.js', array(), '', true);
    wp_enqueue_script('custom_script', get_template_directory_uri() . '/assets/js/scripts.js', array(), '', true);
}
add_action('wp_enqueue_scripts', 'theme_script');
