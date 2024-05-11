<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

?>

<!-- Contact US Section Start -->
<section class="contactus_section wrap_two_columns">
    <div class="container">
        <div class="row mx-auto section_two_columns">

            <div class="col-sm-12 col-md-6 col-lg-6 contactus_content">
                <?php if( get_sub_field('contactus_title') ): ?>
                    <h1><?php the_sub_field('contactus_title'); ?></h1>
                <?php endif;?>

                <?php if( get_sub_field('contactus_content') ): ?>
                    <p><?php the_sub_field('contactus_content'); ?></p>
                <?php endif;?>

                <?php if( have_rows('contactus_adress')) {
                    while (have_rows('contactus_adress')) {
                        the_row(); ?>
                            <div class="physical_adress">
                                <i class="bi bi-geo-alt-fill"></i> <?php the_sub_field('adress'); ?>
                            </div>
                    <?php } ?>
                <?php } ?>

                <?php if (have_rows('emails')) {
                    while (have_rows('emails')) {
                        the_row(); ?>
                            <a href="mailto:<?php the_sub_field('contactus_email_link');?>" target="_blank">
                                <i class="bi bi-envelope-fill"></i> <?php the_sub_field('contactus_email');?>
                            </a>
                    <?php } ?>
                <?php } ?>

                <?php if (have_rows('contact_phones')) {
                    while (have_rows('contact_phones')) {
                        the_row(); ?>
                            <a href="tel:<?php the_sub_field('phone_link');?>" target="_blank">
                                <i class="bi bi-telephone-fill"></i><?php the_sub_field('contact_us_phone');?>
                            </a>
                    <?php } ?>
                <?php } ?>

                <?php if( have_rows('social_links') ): ?>
                    <div class="social_links">
                    <?php while( have_rows('social_links') ) : the_row(); ?>
                        <a target="_blank" href="<?php the_sub_field('url'); ?>">
                                <i class="bi <?php the_sub_field('service_ico'); ?>"></i>
                        </a>
                    <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6 mx-auto">
                <div class="contactus_team m-auto">

                    <?php if( have_rows('contactus_team') ): ?>
                        <div class="row">
                        <?php while( have_rows('contactus_team') ) : the_row(); ?>
                            <div class="col-12 col-sm-6 contactus_team_item">
                                <img src="<?php the_sub_field('photo'); ?>" alt="">

                                <h4><?php the_sub_field('name'); ?></h4>

                                <?php if( get_sub_field('team_adress')):?>
                                    <div class="physical_adress">
                                        <i class="bi bi-geo-alt-fill"></i> <?php the_sub_field('team_adress'); ?>
                                    </div>
                                <?php endif;?>

                                <?php if (have_rows('team_mails')) {
                                    while (have_rows('team_mails')) {
                                        the_row(); ?>
                                            <a href="mailto:<?php the_sub_field('contactus_email_link');?>" target="_blank">
                                                <i class="bi bi-envelope-fill"></i> <?php the_sub_field('contactus_email');?>
                                            </a>
                                    <?php } ?>
                                <?php } ?>

                                <?php if (have_rows('team_phones')) {
                                    while (have_rows('team_phones')) {
                                        the_row(); ?>
                                            <a href="tel:<?php the_sub_field('contactus_phone_link');?>" target="_blank">
                                                <i class="bi bi-telephone-fill"></i><?php the_sub_field('contactus_phone');?>
                                            </a>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        <?php endwhile; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
        <div class="col-12 col-sm-6 mx-auto mt-4">
            <?php if( get_sub_field('for_registration_form') ): ?>

            <?php $registration_form = get_sub_field('for_registration_form');
                echo apply_filters('the_content', $registration_form);?>

            <?php endif;?>
        </div>
    </div>
</section>
<!-- Contact US Section End -->