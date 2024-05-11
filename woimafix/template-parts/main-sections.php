<?php
/**
 * All sections and template of EasyE theme
 *
 */
?>

<?php if ( have_rows( 'sections' ) ) : ?>
    <?php while ( have_rows('sections' ) ) : the_row();
        if ( get_row_layout() == 'hero' ) :
            get_template_part('template-parts/sections/section', 'hero');

        elseif ( get_row_layout() == 'hero_register' ) :
            get_template_part('template-parts/sections/section', 'hero_register');

        elseif ( get_row_layout() == 'two_columns' ) :
            get_template_part('template-parts/sections/section', 'two_columns');

        elseif ( get_row_layout() == 'info_box' ) :
            get_template_part('template-parts/sections/section', 'info_box');

        elseif ( get_row_layout() == 'highlighted_articles' ) :
            get_template_part('template-parts/sections/section', 'highlighted_articles');

        elseif ( get_row_layout() == 'contactus' ) :
            get_template_part('template-parts/sections/section', 'contactus');

        elseif ( get_row_layout() == 'services_grid' ) :
            get_template_part('template-parts/sections/section', 'services_grid');

        elseif ( get_row_layout() == 'maps' ) :
            get_template_part('template-parts/sections/section', 'maps');

        elseif ( get_row_layout() == 'blog_grid' ) :
            get_template_part('template-parts/sections/section', 'blog_grid');

        elseif ( get_row_layout() == 'related_articles' ) :
            get_template_part('template-parts/sections/section', 'related_articles');

        elseif ( get_row_layout() == 'service_cards' ) :
            get_template_part('template-parts/sections/section', 'service_cards');

        elseif ( get_row_layout() == 'slider' ) :
            get_template_part('template-parts/sections/section', 'slider');

            elseif ( get_row_layout() == 'video' ) :
                get_template_part('template-parts/sections/section', 'video');
            ?>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>