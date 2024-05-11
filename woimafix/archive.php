<?php
/**
 * The template for displaying archive pages
 *
 */

get_header();

$text = get_the_excerpt();
$words = 20;
$excerpt_lenght = 20;
$more = '…';
$excerpt = wp_trim_words( $text, $words, $more );
?>

<?php get_template_part( 'template-parts/blocks-archive/archive-head' ); ?>

    <?php if ( have_posts() ) : ?>
    <!-- Posts Grid Area Start -->
    <section class="blogrid_articles">
        <div class="container">
            <div class="row">

                <?php
                // Start the Loop.
                while ( have_posts() ) : the_post(); ?>

                    <div class="col-12 col-sm-6 col-md-6 col-xl-4 equal-height" id="post-<?php the_ID(); ?>" id="post-<?php the_ID(); ?>">
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
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                                <p><?php echo custom_excerpt($excerpt_lenght); ?></p>

                                <a href="<?php the_permalink(); ?>" class="learn-more"><?php _e( 'Lue lisää', 'woimafix' );?> <i class="fas fa-long-arrow-alt-right"></i></a>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
            </div>

                <?php // Previous/next page navigation.
                    the_posts_pagination( array(
                        'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
                        'next_text'          => __( 'Next page', 'twentyfifteen' ),
                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
                    ) );
                ?>

            </div>
        </div>
    </section>
    <!-- Posts Grid Area End -->

    <?php else :
        get_template_part( 'template-parts/content-none' );

    endif; ?>

<?php
get_footer();
