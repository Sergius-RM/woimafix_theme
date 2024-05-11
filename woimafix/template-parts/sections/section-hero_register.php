<?php
$image = get_sub_field('hero_img');
?>

<!-- Hero Section Start -->
<section class="hero-section">
    <div class="banner_overlay"></div>
    <div class="hero-container hero-form-section container-fluid" style="<?php if (get_sub_field( 'hero_img')): ?>background-image: url('<?php echo esc_url($image['url']); ?>');<?php endif;?>">
        <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-8 text-center <?php if (get_sub_field( 'title_color_scheme' )):?><?php echo $color_scheme;?><?php endif;?> m-auto">
                <h1 class="hero_title <?php if (get_sub_field( 'title_color_scheme' )):?>color_scheme<?php endif;?> mx-auto">
                    <?php echo get_sub_field('header_title');?>
                </h1>

                <?php if (get_sub_field( 'content')): ?>
                    <hr>
                    <span class="hero-content d-block">
                        <?php echo get_sub_field('content');?>
                    </span>
                <?php endif;?>

                <?php if (get_sub_field( 'form_shortcode')): ?>
                    <div class="register_form_area">
                        <div class="banner_overlay"></div>
                        <div class="register_form_content">
                            <h3 class="form_color_scheme"><?php _e( 'TÄYTÄ LOMAKE JA REKISTERÖIDY', 'woimafix' );?> </h3>
                            <?php $form_shortcode = get_sub_field('form_shortcode');?>
                            <?php echo do_shortcode(''. $form_shortcode .'');?>

                            <?php $current_user = wp_get_current_user(); ?>
                            <?php if ( in_array( 'provider', (array) $current_user->roles ) ): ?>
                                <?php echo do_shortcode('[ihc-purchase-link id=1]<div class="um-button purchase-button">Maksa jäsenyydestä</div>[/ihc-purchase-link]');?>
                            <?php endif;?>
                        </div>

                    </div>
                <?php endif;?>

            </div>
        </div>
        </div>
    </div>
</section>
<!-- Hero Section End -->

<script>
    // Ждем загрузки DOM
    document.addEventListener('DOMContentLoaded', function() {
      // Выбираем h2
      var h2Element = document.querySelector('h3.form_color_scheme');
      // Получаем текст
      var text = h2Element.innerText.trim();
      // Разбиваем текст на слова
      var words = text.split(' ');

      // Если у нас есть второе и четвертое слова
      if (words.length > 3) {
        // Изменяем цвет для второго слова
        words[3] = '<span class="second-word">' + words[3] + '</span>';
        // Обновляем HTML
        h2Element.innerHTML = words.join(' ');
      }
    });
  </script>