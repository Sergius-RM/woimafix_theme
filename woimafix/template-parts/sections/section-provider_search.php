<?php
/**
 * Template Name: Provider
 * Template Post Type: provider post
 * The template for displaying all single posts
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<!-- Provider Description Start -->
<div class="provider_search_info">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center col-sm-4 provider_thumbnail">
                <a href="<?php the_permalink();?>">
                    <?php the_post_thumbnail(); ?>
                </a>
            </div>
            <div class="col-12 col-sm-4">
                <div class="member-designation">

                    <?php if( get_field('team_first_name') ): ?>
                        <a href="<?php the_permalink();?>"><h4><?php echo the_field('team_first_name'); ?> <?php echo the_field('team_last_name'); ?></h4></a>
                    <?php else: ?>
                        <a href="<?php the_permalink();?>"><h4><?php the_title(); ?></h4></a>
                    <?php endif; ?>

                    <div class="provider_contact_info">

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
            </div>
        </div>
    </div>
</div>
<!-- Provider Description Area END  -->