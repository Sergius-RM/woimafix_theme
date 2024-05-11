<?php
$image = get_sub_field('hero_img');
$color_scheme = get_sub_field( 'title_color_scheme' );
?>

<!-- Hero Section Start -->
<section class="hero-section">
    <div class="banner_overlay"></div>
    <div class="hero-container container-fluid" style="<?php if (get_sub_field( 'hero_img')): ?>background-image: url('<?php echo esc_url($image['url']); ?>');<?php endif;?>">
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

                <?php if (get_sub_field( 'enable_hero_search')): ?>
                    <div class="hero_search">
                        <?php get_template_part( 'template-parts/sections/section-service_search' ); ?>
                    </div>
                <?php endif;?>

                <?php if (get_sub_field( 'enable_cta_button')): ?>
                    <a class="read_more_link mx-auto" <?php if (get_sub_field('hero_link_id')):?>id="<?php the_sub_field('hero_link_id'); ?>"<?php endif;?> href="<?php echo get_sub_field('hero_link_url');?>"><?php echo get_sub_field('hero_link_text');?></a>
                <?php endif;?>

            </div>
        </div>
        </div>
    </div>
</section>
<!-- Hero Section End -->

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