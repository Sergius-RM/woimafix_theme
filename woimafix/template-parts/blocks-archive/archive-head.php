<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// получаем ID термина на странице термина
$term_id = get_queried_object_id();
// получим ID картинки из метаполя термина
$image_id = get_term_meta( $term_id, '_thumbnail_id', 1 );
// ссылка на полный размер картинки по ID вложения
$tax_image = wp_get_attachment_image_url( $image_id, 'full' );

$queried_object = get_queried_object();
$taxonomy = $queried_object->taxonomy;

if (get_field('taxonomy_background', $taxonomy . '_' . $queried_object->term_id)) {
    $header_image = get_field('taxonomy_background', $taxonomy . '_' . $queried_object->term_id);
} else if ( is_tax('category') && get_field( 'category_header_image', 'option') ) {
    $header_image = get_field('category_header_image', 'option');
} else {
    $header_image = '/wp-content/themes/woimafix/assets/images/hero_head_img.jpg';
}

$title_color_scheme = get_field('title_color_scheme', $taxonomy . '_' . $queried_object->term_id);
?>

<!-- Archive Hero Section Start -->
<section class="page_header_section" >
    <div class="section_overlay"></div>
    <div class="hero-container container-fluid" style="background-image: url('<?php echo $header_image;?>'); background-size: cover;">
        <div class="container">
            <div class="row align-items-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-8 mx-auto text-center <?php if ($title_color_scheme):?><?php echo esc_html($title_color_scheme);?><?php endif;?>">
                <h1 class="hero_title mx-auto <?php if ($title_color_scheme):?>color_scheme<?php endif;?>">
                    <?php single_cat_title();?>
                </h1>

                <?php if (category_description()):?>
                    <span class="hero-content d-block">
                        <hr>
                        <?php echo category_description();?>
                    </span>
                <?php endif;?>
            </div>
        </div>
        </div>
    </div>
</section>
<!-- Archive Hero Section End -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var h3Element = document.querySelector('h1.color_scheme');
        var text = h3Element.innerText.trim();
        var firstSpaceIndex = text.indexOf(' ');
        if (firstSpaceIndex !== -1) {
          var firstWord = text.substr(0, firstSpaceIndex);
          var restOfText = text.substr(firstSpaceIndex);
          h3Element.innerHTML = `<span>${firstWord}</span><span class="second-word">${restOfText}</span>`;
        }
      });
</script>