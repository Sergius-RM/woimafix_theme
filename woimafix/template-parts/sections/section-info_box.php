<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$bg_img = get_sub_field('bg_img');
$title_color_scheme = get_sub_field( 'title_color_scheme' );
$content_type = get_sub_field( 'content_type' );
?>

<!-- Info Box Section Start -->
<section class="info_box_section">
    
    <div class="container-fluid" style="<?php if (get_sub_field( 'bg_img')): ?>background-image: url('<?php echo esc_url($bg_img['url']); ?>'); background-size: cover;<?php endif;?>">
        <div class="section_overlay"></div>
        <div class="container">

            <!-- Info Box Two Columns Section Start -->
            <div class="row align-items-center mx-auto info_box_two_columns">

                <div class="col-12 col-sm-6 col-md-6 col-lg-6 statements_content <?php if ( get_sub_field('swap_blocks') == true ) { echo 'right_side'; } ?>">

                    <h2 <?php if (get_sub_field( 'title_color_scheme' )):?>class="<?php the_sub_field( 'title_color_scheme' ) ;?>_scheme"<?php endif;?> ><?php the_sub_field('inf_box_title'); ?></h2>
                    <div class="content"><?php the_sub_field('body_content'); ?></div>

                    <?php if (get_sub_field('enable_cta_button')):?>
                        <a class="cta_btn" <?php if (get_sub_field('body_link_id')):?>id="<?php the_sub_field('body_link_id'); ?>"<?php endif;?> href="<?php the_sub_field('body_link_url'); ?>">
                            <?php the_sub_field('body_link'); ?>
                        </a>
                    <?php endif;?>
                </div>

                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-lg-6 <?php if ( get_sub_field('swap_blocks') == true ) { echo 'order-first'; } ?>">

                <?php if ($content_type == 'image'):?>

                    <?php if ( get_sub_field('body_img') ):?>
                        <?php $body_img = get_sub_field('body_img');?>
                        <img class="left_img" src="<?php echo $body_img;?>">
                    <?php endif; ?>

                <?php elseif ($content_type == 'features'):?>

                    <?php if( have_rows('features_list') ): ?>
                        <div class="features_list">
                        <?php while( have_rows('features_list') ) : the_row(); ?>
                            <div class="info_box_features_icon">
                                <?php if ( get_sub_field('icon') ):?>
                                    <?php $features_icon = get_sub_field('icon');?>
                                    <img src="<?php echo $features_icon;?>">
                                <?php endif; ?>
                                <h3><?php the_sub_field('title'); ?></h3>
                            </div>
                        <?php endwhile; ?>
                        </div>
                    <?php endif; ?>

                <?php endif;?>

                </div>
            </div>
            <!-- Info Box Two Columns Section END -->
        </div>
    </div>

</section>
<!-- Info Box Section End -->


<?php if ($title_color_scheme == 'pink'): ?>
    <script>
    // Ждем загрузки DOM
    document.addEventListener('DOMContentLoaded', function() {
      // Выбираем h2
      var h2Element = document.querySelector('h2.pink_scheme');
      // Получаем текст
      var text = h2Element.innerText.trim();
      // Разбиваем текст на слова
      var words = text.split(' ');

      // Если у нас есть второе слово
      if (words.length > 1) {
        // Изменяем цвет для второго слова
        words[1] = '<span class="second-word">' + words[1] + '</span>';
        // Обновляем HTML
        h2Element.innerHTML = words.join(' ');
      }
    });
  </script>
<?php elseif ($title_color_scheme == 'violet'): ?>
    <script>
    // Ждем загрузки DOM
    document.addEventListener('DOMContentLoaded', function() {
      // Выбираем h2
      var h2Element = document.querySelector('h2.violet_scheme');
      // Получаем текст
      var text = h2Element.innerText.trim();
      // Разбиваем текст на слова
      var words = text.split(' ');

      // Если у нас есть второе и четвертое слова
      if (words.length > 3) {
        // Изменяем цвет для второго слова
        words[1] = '<span class="second-word">' + words[1] + '</span>';
        // Изменяем цвет для четвертого слова
        words[3] = '<span class="fourth-word">' + words[3] + '</span>';
        // Обновляем HTML
        h2Element.innerHTML = words.join(' ');
      }
    });
  </script>

<?php endif;?>