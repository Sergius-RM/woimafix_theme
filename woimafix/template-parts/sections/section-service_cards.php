<?php
/**
 * The template for displaying footer.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$sourse = get_sub_field( 'sourse');
?>

<!-- Argument Lists Area Start -->
<section class="service_cards_section">
    <div class="container">
        <div class="row mx-auto">

            <?php if (get_sub_field( 'service_title')): ?>
                <h2 class="title_color_scheme"><?php the_sub_field('service_title');?></h2>
            <?php endif;?>

            <?php if ($sourse == 'manual'):?>

                <?php if( have_rows('argument_list_loop') ): ?>
                    <?php while( have_rows('argument_list_loop') ) : the_row(); ?>

                        <div class="col-12 col-sm-6 col-md-4 col-xl-4 service_cards_item mx-auto">
                            <div class="service_card_wrap">
                                <div class="service_card_icon">
                                    <img class="service_card_icon" src="<?php the_sub_field('icon');?>">
                                </div>

                                <?php if (get_sub_field( 'title')): ?>
                                    <h3><?php the_sub_field('title');?></h3>
                                <?php endif;?>

                                <?php if (get_sub_field( 'content')): ?>
                                    <div class="service_card_content">
                                        <?php the_sub_field('content');?>
                                    </div>
                                <?php endif;?>

                                <?php if (get_sub_field('enable_cta_button')):?>
                                    <a class="read_more" <?php if (get_sub_field('link_id')):?>id="<?php the_sub_field('link_id'); ?>"<?php endif;?> href="<?php the_sub_field('link_url');?>"><?php the_sub_field('link_name');?></a>
                                <?php endif;?>

                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php endif; ?>

            <?php elseif ($sourse == 'request'):?>

                <?php
                    $posts = get_sub_field('team_list');
                    if( $posts ): ?>

                    <?php foreach( $posts as $post_id): ?>
							<?php $post = get_post($post_id); ?>
                            <div class="col-12 col-sm-6 col-md-4 col-xl-4 service_cards_item mx-auto">
                                <div class="service_card_wrap">
                                    <div class="service_card_icon">
                                        <?php the_post_thumbnail(); ?>
                                    </div>
                                    <?php if( get_the_excerpt() ): ?>
                                        <div class="provider_excerpt">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if( get_field('team_first_name', $post->ID) ): ?>
                                        <a class="read_more" href="<?php the_permalink(); ?>">
                                            <?php the_field('team_first_name', $post->ID); ?>
                                            <?php the_field('team_last_name', $post->ID); ?>
                                        </a>
                                    <?php else: ?>

                                        <a class="read_more" href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>

                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php wp_reset_postdata();?>
                    <?php endif; ?>

            <?php endif;?>
        </div>
    </div>
</section>
<!-- Argument Lists Area End -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var h2Element = document.querySelector('h2.title_color_scheme');
        var text = h2Element.innerText.trim();
        var words = text.split(' ');
        if(words.length > 2) {
        words[0] = '<span class="first-word">' + words[0] + '</span>';
        words[2] = '<span class="third-word">' + words[2] + '</span>';
        h2Element.innerHTML = words.join(' ');
    }
    });
</script>