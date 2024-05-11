<?php
/**
 * The template for displaying header.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$site_name = get_bloginfo( 'name' );
$tagline = get_bloginfo( 'description', 'display' );

if (is_tax()) {
    $hero = 'hero_on';
} else {
    $hero = 'hero_off';
};
?>
<?php if ( have_rows( 'sections' ) ) : ?>
    <?php while ( have_rows('sections' ) ) : the_row();
        if ( get_row_layout() == 'hero' ) :
            $hero = 'hero_on';
        elseif ( get_row_layout() == 'hero_register' ) :
            $hero = 'hero_on';
        ?>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>

<!-- Start main Header -->
<header class="header_area <?php if (isset($hero)):?><?php echo $hero;?><?php endif;?> full-width" role="banner">
    <!--Header-Upper-->

    <div class="site-header">
        <div class="site-branding align-items-center d-flex">

            <div class="navbar-brandlogo_area no_mobile">
                <?php the_custom_logo();?>
            </div>

            <!-- Main Menu -->
            <nav class="site-navigation">
                <div class="no_mobile" role="navigation">
                    <?php wp_nav_menu( array( 'theme_location' => 'menu-1' ) ); ?>
                </div>
            </nav>
            <!-- Main Menu End-->

            <!-- Mobile Menu -->
            <div class="navbar navbar-light bg-light <?php print $navbar_style;?> is_onmobile">
                <span class="navbar-brandlogo_area">
                    <span class="header-logo-darkmode">
                        <?php the_custom_logo();?>
                    </span>
                </span>

                <button class="navbar-toggler is_onmobile" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon <?php if (isset($hero)):?><?php echo $hero;?><?php endif;?>"></span>
                </button>
            </div>
            <!-- Mobole Menu End-->

            <div class="header_search no_mobile">
                <form class="services_search_form mx-auto" action="/haku" method="post">
                    <input type="search" class="search-field" placeholder="Palvelu" value="" name="service">
                    <input type="submit" value="Etsi →">
                </form>
            </div>
        </div>
    </div>

    <div class="collapse mob_menu" id="navbarToggleExternalContent">
        <div role="navigation">
            <?php wp_nav_menu( array( 'theme_location' => 'menu-1' ) ); ?>
        </div>
        <div class="header_search">
            <?php get_search_form();?>
        </div>
    </div>
    <!--End Header Upper-->
</header>
