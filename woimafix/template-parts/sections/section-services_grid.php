<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$text = get_the_excerpt();
$words = 20;
$excerpt_lenght = 20;
$more = '…';
$excerpt = wp_trim_words( $text, $words, $more );
?>

<!-- Blog Grid Area Start -->
<section class="services_grid">
    <div class="container">

        <div class="section-title text-center">
            <?php if( get_sub_field('h2_header') ): ?>
                <h2 class="title_ordering"><?php echo get_sub_field('h2_header'); ?></h2>
            <?php endif; ?>

            <?php if( get_sub_field('content') ): ?>
                <div class="services_grid-content text-center">
                    <?php echo get_sub_field('content'); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="row">

            <?php if( have_rows('custom_pages') ): ?>
                <?php while( have_rows('custom_pages') ) : the_row(); ?>
                        <div class="col-12 col-sm-6 col-md-4 col-xl-4 post_item equal-height mx-auto">
                            <div class="articles-item">
                                <div class="image">
                                    <a href="<?php the_sub_field('link');?>">
                                        <img src="<?php the_sub_field('image');?>" alt="<?php the_title(); ?>">
                                    </a>
                                </div>

                                <div class="articles-content">
                                    <h3>
                                        <a href="<?php the_sub_field('link');?>">
                                            <?php the_sub_field('title');?>
                                        </a>
                                    </h3>
                                </div>
                            </div>
                        </div>
                <?php endwhile; ?>
            <?php endif; ?>

        </div>
    </div>
</div>
</section>
<!-- Blog Grid Area END  -->

<script>
    // Ждем загрузки DOM
    document.addEventListener('DOMContentLoaded', function() {
      // Выбираем h2
      var h2Element = document.querySelector('h2.title_ordering');
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