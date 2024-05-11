<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$number = get_sub_field( 'number_related_articles');
$orderby = get_sub_field( 'order_by');
$order = get_sub_field( 'sorting_order');

$categories = get_the_category($post->ID);
$category_ids = array();
foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
$args=array(
    'post_type'         => 'post',
    'posts_per_page'    => $number,
    'post__not_in'      => array( get_the_ID() ),
    'no_found_rows'     => true,
    'orderby'           => $orderby,
    'order'             => $order,
);

$text = get_the_excerpt();
$words = 20;
$excerpt_lenght = 20;
$more = '…';
$excerpt = wp_trim_words( $text, $words, $more );
?>

<!-- Related Articles Area Start -->
<section class="highlighted_articles">
    <div class="container-fluid">
        <div class="container section-title d-flex align-items-center">
            <?php if( get_sub_field('section_header_h2') ): ?>
                <h2><?php echo get_sub_field('section_header_h2'); ?></h2>
            <?php endif; ?>

            <?php if (get_sub_field('enable_cta_button')):?>
                <a class="more_posts" target="_blank" <?php if (get_sub_field('button_id')):?>id="<?php the_sub_field('button_id'); ?>"<?php endif;?> href="<?php the_sub_field('button_link'); ?>">
                    <?php the_sub_field('button_text'); ?>
                </a>
            <?php endif;?>
        </div>
        <div class="row justify-content-center slider related_articles_list">

            <?php $wpex_query = new WP_Query( $args );
            foreach( $wpex_query->posts as $post ) : setup_postdata( $post ); ?>
            <div class="col-xl-4 col-md-6" id="post-<?php the_ID(); ?>">
                <div class="articles-item">
                    <div class="image">
                        <?php if (has_post_thumbnail( $post->ID ) ): ?>
                            <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'image_full' ); ?>
                            <img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>">
                        <?php else: ?>
                            <img src="<?php bloginfo('template_directory'); ?>/assets/images/post_thumbnail.png" alt="<?php the_title(); ?>" />
                        <?php endif; ?>
                    </div>

                    <div class="articles-content">
                        <strong class="meta_data"><?php the_time( 'd.m.Y' );?></strong>
                        <h4><a href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></h4>

                        <!-- <p><?php echo custom_excerpt($excerpt_lenght); ?></p> -->

                        <a href="<?php the_permalink(); ?>" class="learn-more"><?php _e( 'Lue lisää', 'woimafix' );?> <i class="fas fa-long-arrow-alt-right"></i></a>
                    </div>
                </div>
            </div>
            <?php
            endforeach;
            wp_reset_postdata(); ?>
        </div>
    </div>
</div>
</section>
<!-- Related Articles Area END  -->