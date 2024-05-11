<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

?>

<!-- Image Slider Loop Start -->
<section class="slider_loop_section">
    <div class="container">
        <div class="row align-items-center mx-auto slider slide_list">
            <?php if( have_rows('photo_slider') ): ?>
                <?php while( have_rows('photo_slider') ) : the_row(); ?>
                    <div class="slide_item">
                        <?php if ( get_sub_field('photo_item') ):?>
                            <?php $image_item = get_sub_field('photo_item');?>
                            <img src="<?php echo $image_item;?>">
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</section>
<!-- Image Slider Loop Area END  -->
