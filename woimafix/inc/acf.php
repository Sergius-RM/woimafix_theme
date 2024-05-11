<?php
/**
 * ACF Functions
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add options page
 */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(
        [
            'page_title' => __('Site Settings', 'greatcompany'),
            'menu_title' => __('Site Settings', 'greatcompany'),
            'menu_slug' => 'theme-general-settings',
            'capability' => 'edit_posts',
            'redirect' => false,
        ]
    );
}


/**
 * Register ACF blocks
 */
function acf_init_block_types() {

    if (function_exists('acf_register_block_type')) {

        /**
         * Info-Box block
         */
        acf_register_block_type(
            array(
                'name'              => 'infobox',
                'title'             => __( 'Info-Box' ),
                'description'       => __( 'Info-Box' ),
                'render_callback'   => 'render_infobox_block',
                'category'          => 'embed',
                'icon'              => 'images-alt',
                'keywords'          => array('info box'),
                'multiple'          => true,
                'mode'              => 'edit',
            )
        );

    }
}

add_action( 'acf/init', 'acf_init_block_types' );

/**
 * Info Box Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function render_infobox_block ( $block, $content = '', $is_preview = false, $post_id = 0 ) {
    ?>

        <div class="container block_infobox">
            <div class="row align-items-center">
                <div class="col-sm-8 block_infobox_item">
                    <div class="item-content">
                        <h6><?php the_field('block_postinfo_subtitle'); ?></h6>
                        <h2><?php the_field('block_postinfo_title'); ?></h2>
                        <?php the_field('block_postinfo_content'); ?>
                    </div>
                </div>
                <div class="col-sm-4 block_infobox_btn mx-auto text-end">
                    <?php if (get_field('enable_cta_button')):?>
                        <a <?php if (the_field('block_postinfo_btnid')):?>id="<?php the_field('block_postinfo_btnid'); ?>"<?php endif;?> href="<?php the_field('block_postinfo_btnurl'); ?>" class="shop_btn">
                            <?php the_field('block_postinfo_btntext'); ?> <i class="fas fa-long-arrow-alt-right"></i>
                        </a>
                    <?php endif;?>
                </div>
            </div>
        </div>

<?php
}
