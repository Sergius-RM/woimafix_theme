<?php
/**
 * Template Name: Provider
 * Template Post Type: provider post
 * The template for displaying all single posts
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

?>

<!-- Provider Description Start -->
<section class="provider_info_section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center col-sm-4 provider_thumbnail">
                <?php the_post_thumbnail(); ?>
            </div>
            <div class="col-12 col-sm-4">
                <div class="member-designation">

                    <?php if( get_field('team_first_name') ): ?>
                        <h2><?php echo the_field('team_first_name'); ?> <?php echo the_field('team_last_name'); ?></h2>
                    <?php else: ?>
                        <h2><?php the_title(); ?></h2>
                    <?php endif; ?>

                    <?php if( get_field('team_job_title') ): ?>
                        <div class="provider_status">
                            <?php echo the_field('team_job_title'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if( get_the_excerpt() ): ?>
                        <div class="provider_excerpt">
                            <?php the_excerpt(); ?>
                        </div>
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

                        <?php if( have_rows('social_links') ): ?>
                            <div class="social_links">
                            <?php while( have_rows('social_links') ) : the_row(); ?>
                                <a target="_blank" href="<?php the_sub_field('url'); ?>">
                                        <i class="bi <?php the_sub_field('service_ico'); ?>"></i>
                                </a>
                            <?php endwhile; ?>
                            </div>
                        <?php endif; ?>

                        <?php
                        $post_id = get_the_ID();
                        $categories = wp_get_post_terms($post_id, 'provider_category');

                        if (!empty($categories)):?>
                            <div class="provider_category">
                            <?php foreach ($categories as $category):?>
                                <a href="<?php echo  get_term_link($category); ?>">
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            <?php endforeach;?>
                            </div>
                        <?php endif;?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<!-- Provider Description Area END  -->

<?php if ( have_rows( 'sections' ) ) : ?>
    <?php while ( have_rows('sections' ) ) : the_row();
        if ( get_row_layout() == 'provider_description' ) :
            get_template_part('template-parts/sections/section', 'provider_description');

        elseif ( get_row_layout() == 'slider' ) :
            get_template_part('template-parts/sections/section', 'slider');

        elseif ( get_row_layout() == 'video' ) :
            get_template_part('template-parts/sections/section', 'video');
            ?>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>


<?php get_footer();