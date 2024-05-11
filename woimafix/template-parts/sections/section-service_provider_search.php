<?php
/**
 * The template for displaying footer.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>

    <?php
        $posts = get_sub_field('team_list');
        if( $posts ): ?>

        <?php foreach( $posts as $post): ?>
            <?php setup_postdata($post); ?>

                    <div class="service_provider_search provider_<?php echo get_the_ID(); ?>">

                        <div class="service_card_icon">
                            <?php the_post_thumbnail(); ?>
                        </div>

                        <div class="provider_contact_info">
                            <?php if( get_field('team_first_name', $post->ID) ): ?>
                                <h4>
                                    <a class="read_more" href="<?php the_permalink(); ?>">
                                        <?php the_field('team_first_name', $post->ID); ?>
                                        <?php the_field('team_last_name', $post->ID); ?>
                                    </a>
                                </h4>
                            <?php else: ?>
                                <h4>
                                    <a class="read_more" href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h4>
                            <?php endif; ?>

                            <?php
                                if( have_rows('physical_adress') ):
                                    while ( have_rows('physical_adress') ) : the_row();
                                        $term = get_sub_field('link_locaion');
                                        if($term) {
                                        $term_link = get_term_link( $term, 'locations');
                                        ?>
                                            <div class="physical_adress">
                                                <a href="<?php echo $term_link; ?>">
                                                    <i class="bi bi-geo-alt-fill"></i> <?php the_sub_field('physical_adress'); ?>
                                                </a>
                                            </div>
                                        <?php
                                        }
                                    endwhile;
                                endif;
                            ?>

                            <?php if (have_rows('e-mails')) {
                                while (have_rows('e-mails')) {
                                    the_row(); ?>
                                        <a href="mailto:<?php the_sub_field('member_email_link');?>" target="_blank">
                                            <i class="bi bi-envelope-fill"></i> <?php the_sub_field('member_email');?>
                                        </a>
                                <?php } ?>
                            <?php } ?>

                            <?php if (have_rows('phones')) {
                                while (have_rows('phones')) {
                                    the_row(); ?>
                                        <a href="tel:<?php the_sub_field('member_phone_link');?>" target="_blank">
                                            <i class="bi bi-telephone-fill"></i><?php the_sub_field('member_phone');?>
                                        </a>
                                <?php } ?>
                            <?php } ?>
                        </div>

                    </div>

            <?php endforeach; ?>
            <?php wp_reset_postdata();?>
        <?php endif; ?>
