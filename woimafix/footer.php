
<?php
/**
 * The template for displaying the footer.

 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

    get_template_part('template-parts/footer');
?>

    <?php wp_footer(); ?>

    <?php if (  get_field( 'google_analytics_footer', 'option') ) :?>
        <?php $footercode = get_field('google_analytics_footer', 'option');
            print $footercode; ?>
    <?php endif ?>

    <?php if (  get_field( 'footer_whatsapp_settings', 'option') ) :?>
        <?php $whatsapp = get_field('footer_whatsapp_settings', 'option');
            print $whatsapp; ?>
    <?php endif ?>


<?php if (is_page('')) :?>
    <script>
    jQuery.noConflict(); (function($) {
        function updateBlockHeight() {
    var blockB = document.querySelector('.gotrgf_summary_wrapper');
    var blockA = document.querySelector('.thanks_text');

    if (blockB && blockA) {
        var blockBHeight = blockB.clientHeight; // Получаем высоту блока Б

        // Присваиваем высоту блока Б блоку А
        blockA.style.height = blockBHeight + 'px';
    }
}

// Вызываем функцию каждую секунду
setInterval(updateBlockHeight, 500);

    })(jQuery);
    </script>
<?php endif;?>

</body>
</html>
