<?php
/**
 * The template for displaying footer.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$copyright_data = get_field('copyright_data', 'option');

?>

<!-- Footer Area Start -->
<footer id="site-footer" class="site-footer" role="contentinfo">

<?php
if (
    !(is_page_template('template-service_search.php') && $query && $query->have_posts())
) : ?>
    <div class="container-fluid footer-bg" style="background: url(<?php the_field('foter_bg', 'option');?>) 50% 50% no-repeat; background-size: cover;"></div>
<?php endif; ?>


    <div class="container">
        <div class="row footer-info">

            <!-- Footer Nav Area Start -->
            <div class="col-12 col-xs-6 col-sm-6 col-md-6 col-xl-3 footer_nav" role="navigation">
                <?php if (have_rows('physical_adress', 'option')) { ?>
                    <h3><?php _e( 'POSTIOSOITE', 'woimafix' );?></h3>
                    <?php while (have_rows('physical_adress', 'option')) {
                        the_row(); ?>
                            <div class="physical_adress"><i class="bi bi-geo-alt-fill"></i> <?php the_sub_field('short_physical_adress');?></div>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="col-12 col-xs-6 col-sm-6 col-md-6 col-xl-3 footer_nav" role="navigation">
                <?php if (have_rows('topbarphones', 'option')) { ?>
                    <h3><?php _e( 'PUHELIN', 'woimafix' );?></h3>
                    <?php while (have_rows('topbarphones', 'option')) {
                        the_row(); ?>
                            <a href="tel:<?php the_sub_field('top_bar_phone_link');?>" target="_blank">
                                <i class="bi bi-telephone-fill"></i><?php the_sub_field('top_bar_phone');?></a>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="col-12 col-xs-6 col-sm-6 col-md-6 col-xl-3 footer_nav" role="navigation">
                <?php if (have_rows('topbaremails', 'option')) { ?>
                    <h3><?php _e( 'SÄHKÖPOSTI', 'woimafix' );?></h3>
                    <?php while (have_rows('topbaremails', 'option')) {
                        the_row(); ?>
                            <a href="mailto:<?php the_sub_field('top_bar_email_link');?>" target="_blank">
                                <i class="bi bi-envelope-fill"></i> <?php the_sub_field('top_bar_email');?></a>
                    <?php } ?>
                <?php } ?>
            </div>
            <!-- Footer Nav Area -->

            <!-- Ordering Area Start -->
            <div class="col-12 col-xs-6 col-sm-6 col-md-63 col-xl-3 footer_ordering">
                <?php if( have_rows('orderind_link', 'option') ): ?>
                    <?php while( have_rows('orderind_link', 'option') ) : the_row(); ?>
                        <h3 class="title_ordering">
                            <?php the_sub_field('title'); ?>
                        </h3>
                        <a target="_blank" class="orderind_link" id="<?php the_sub_field('link_id'); ?>" href="<?php the_sub_field('url'); ?>">
                            <?php the_sub_field('link_text'); ?> <i class="bi bi-arrow-right"></i>
                        </a>

                        <?php if (get_sub_field( 'enable_phone')): ?>
                            <a href="tel:<?php the_sub_field('orderind_phone_link');?>" target="_blank">
                                <i class="bi bi-telephone-fill"></i><?php the_sub_field('orderind_phone');?>
                            </a>
                        <?php endif;?>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
            <!-- END Ordering Area -->
        </div>
    </div>

</footer>
 <!-- Footer Area End -->

<!-- START Copyright Area -->
<div class="container-fluid footer_copyright">
    <div class="row align-items-center">
        <div class="col-12 col-sm-6 col-xl-6 footer_copyright_menu">
            <?php wp_nav_menu( array( 'theme_location' => 'copyright-1' ) ); ?>
        </div>
        <div class="col-12 col-sm-6 col-xl-6 copyright_data">
            <?php echo $copyright_data;?>
        </div>
    </div>
</div>
<!-- END Copyright Area -->


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Выбираем h3
        var h3Element = document.querySelector('h3.title_ordering');
        // Получаем текст
        var text = h3Element.innerText.trim();
        // Находим индекс первого пробела
        var firstSpaceIndex = text.indexOf(' ');
        // Если пробел найден, добавляем span для второго слова
        if (firstSpaceIndex !== -1) {
          var firstWord = text.substr(0, firstSpaceIndex);
          var restOfText = text.substr(firstSpaceIndex);
          h3Element.innerHTML = `<span>${firstWord}</span><span class="second-word">${restOfText}</span>`;
        }
      });
</script>