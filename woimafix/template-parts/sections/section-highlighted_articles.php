<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$number = get_sub_field('number_of_posts');
$orderby = get_sub_field('order_by');
$order = get_sub_field('sorting_order');

$category = get_sub_field('from_category');
$category_array = array( $category );

$args = array(
    'post_type'         => 'post',
    'posts_per_page'    => $number,
    'orderby'           => $orderby,
    'order'             => $order,
    'tax_query' => array(
        array(
            'taxonomy' => 'category',
            'field' => 'id',
            'terms'    => $category_array[0],
        )
    ),
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
            <?php if( get_sub_field('h2_header') ): ?>
                <h2><?php echo get_sub_field('h2_header'); ?></h2>
            <?php endif; ?>

                <?php if (get_sub_field('enable_cta_button')):?>
                    <a class="more_posts" target="_blank" <?php if (get_sub_field('button_link')):?>id="<?php the_sub_field('button_id'); ?>"<?php endif;?> href="<?php the_sub_field('button_link'); ?>">
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
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>">
                            </a>
                        <?php else: ?>
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php bloginfo('template_directory'); ?>/assets/images/post_thumbnail.png" alt="<?php the_title(); ?>" />
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="articles-content">
                        <?php if (get_sub_field('enable_metadata')):?>
                            <strong class="meta_data">
                            <?php if (get_field('data')):?>
                                <?php the_field('data'); ?>
                            <?php else: ?>
                                <?php the_time( 'd.m.Y' );?>
                            <?php endif; ?>
                            </strong>
                        <?php endif; ?>
                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

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
