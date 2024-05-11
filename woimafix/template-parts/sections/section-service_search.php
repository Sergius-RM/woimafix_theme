<!-- Services Search Form Area Start -->
<section class="services_searchform_area">

    <form class="col-12 col-sm-6 services_search_form mx-auto" action="<?php echo home_url('/haku'); ?>" method="post">
        <input type="hidden" name="form_sent" value="1" />

        <input type="text" class="search-field" placeholder="<?php _e( 'Palvelu', 'woimafix' );?>" value="" name="service">

        <?php if (!is_singular('services')):?>
        <select name="location">
            <option value=""><?php _e( 'Paikkakunta', 'woimafix' );?></option>

            <?php
            $terms = get_terms(array(
                'taxonomy' => 'locations',
                'hide_empty' => false
            )); ?>

            <?php if($terms) {
                foreach($terms as $term) {
                echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
                }
            }
            ?>

        </select>
        <?php endif;?>

        <input class="cta_btn" type="submit" value="<?php _e( 'Etsi →', 'woimafix' );?>">
    </form>

</section>
<!-- Services Search Form Area End -->