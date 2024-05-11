<?php
/**
 * Template name: Service Search
 *
 */

get_header();


?>

    <?php if ( have_rows( 'sections' ) ) : ?>
        <?php while ( have_rows('sections' ) ) : the_row();
            if ( get_row_layout() == 'hero' ) :
                get_template_part('template-parts/sections/section', 'hero');
                ?>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>

    <section class="service_search_area">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-6 providers_list">
                <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['form_sent'])) {
                        $service = sanitize_text_field($_POST['provider']);
                        $location = sanitize_text_field($_POST['location']);
                        $args = array(
                            'post_type' => 'provider',
                            'posts_per_page' => -1,
                            'tax_query' => array(),
                        );

                        // Добавляем параметры в аргументы
                        if (!empty($service)) {
                            $args['s'] = $service;
                        }

                        if (!empty($location)) {
                            $args['tax_query'][] = array(
                                'taxonomy' => 'locations',
                                'field' => 'slug',
                                'terms' => $location,
                            );
                        } ?>

                        <h2><?php echo ucfirst($location);?></h2>

                        <?php $query = new WP_Query($args); ?>

                        <?php if ($query->have_posts()) {
                            while ($query->have_posts()) { ?>

                                <?php $query->the_post(); ?>

                <?php get_template_part('template-parts/sections/section', 'provider_search'); ?>

                            <?php } ?>

                            <?php wp_reset_postdata(); ?>

                        <?php } else { ?>

                            <?php _e( 'Mitään ei löytynyt', 'woimafix' ); ?>

                       <?php }
                    }
                    ?>
                </div>

                <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['form_sent'])):?>
                <div class="col-12 col-sm-6 map_coordinates">

                        <div class="map" id="map"></div>

                        <script>
                            // Инициализируем карту
                            var map = L.map('map').setView([65.7796236, 24.5519743], 7);

                            // Добавляем слои карт OpenStreetMap
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                            }).addTo(map);

                            // Массив для маркеров
                            var markers = [];

                            <?php $query = new WP_Query($args);
                                if ($query->have_posts()) : ?>
                                <?php while ($query->have_posts()) : $query->the_post(); ?>
                                    <?php while( have_rows('physical_adress') ) : the_row(); ?>
                                        <?php
                                            $lat = get_sub_field('lat');
                                            $lng = get_sub_field('lng');
                                        ?>
                                    <?php endwhile; ?>
                                    <?php
                                        $name = get_the_title();
                                        $thumbnail = get_the_post_thumbnail();
                                        $link = get_permalink();
                                    ?>

                                    // Инициализация новой иконки
                                    var myIcon = L.icon({
                                        iconUrl: '/wp-content/themes/woimafix/assets/images/icons/marker.png',
                                        iconSize: [32, 32],
                                        iconAnchor: [16, 32],
                                        popupAnchor: [0, -32]
                                    });

                                    var marker = L.marker([<?php echo $lat; ?>, <?php echo $lng; ?>], { icon: myIcon }).addTo(map);

                                    // Добавляем попап с версткой
                                    marker.bindPopup(`
                                        <div class="provider_map_popup">
                                            <a href="<?php echo $link;?>">
                                                <?php echo $thumbnail; ?>
                                                <strong><?php echo $name; ?></strong>
                                            </a>
                                        </div>
                                    `);

                                    markers.push(marker);
                                <?php endwhile; ?>
                                <?php wp_reset_postdata(); ?>
                            <?php endif; ?>

                            var group = new L.featureGroup(markers);

                            map.fitBounds(group.getBounds());
                        </script>
                </div>
                <?php endif;?>

            </div>
        </div>
    </section>
<?php
get_footer();
?>


