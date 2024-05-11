<?php
/**
 * The template for displaying footer.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$type_video = get_sub_field('type_video');
?>

<!-- Video Area Start -->
<section class="video_section">
    <div class="container">
        <div class="row">

        <?php if (get_sub_field('video_title')):?>
            <h2 class="mx-auto text-center"><?php the_sub_field('video_title'); ?></h2>
        <?php endif;?>

        <?php if (get_sub_field('video_content')):?>
            <div class="col-12 col-lg-6 mx-auto text-center video_content">
                <?php the_sub_field('video_content'); ?>
            </div>
        <?php endif;?>

        <?php if ($type_video == 'simple'):?>
            <video class="mx-auto" controls
                    <?php if (get_sub_field('poster')):?>
                        poster="<?php the_sub_field('poster'); ?>"
                    <?php endif;?>>
                <source src="<?php the_sub_field('video_url'); ?>" type="video/mp4">
                <source src="<?php the_sub_field('video_url'); ?>" type="video/x-msvideo">
                Your browser does not support the video tag.
            </video>
        <?php elseif ($type_video == 'youtube'):?>
            <?php the_sub_field('youtube_link'); ?>

            <!-- <iframe width="100%" height="450px" src="https://www.youtube.com/embed/<?php the_sub_field('youtube_link'); ?>?controls=0&amp;showinfo=0&amp;rel=0" allow="autoplay; encrypted-media" allowfullscreen></iframe> -->
        <?php endif;?>
        </div>
    </div>
</section>
<!-- Video Lists Area End -->